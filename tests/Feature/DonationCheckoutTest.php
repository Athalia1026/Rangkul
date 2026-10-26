<?php
namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use App\Services\DonationService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DonationCheckoutTest extends TestCase
{
    use DatabaseTransactions;

    private function donor(): User
    {
        $user = User::where('email', 'donatur@rangkul.test')->first();
        if (!$user) $this->markTestSkipped('Requires Rangkul demo seed.');
        return $user;
    }

    public function test_transaction_status_is_private(): void
    {
        $user = $this->donor();
        $this->getJson('/api/donations/unknown')->assertUnauthorized();
        Sanctum::actingAs($user);
        $own = Donation::where('id_donatur', $user->donor->id)->firstOrFail();
        $foreign = Donation::where('id_donatur', '!=', $user->donor->id)->firstOrFail();
        $this->getJson('/api/donations/'.$own->id)->assertOk()->assertJsonPath('id', $own->id);
        $this->getJson('/api/donations/'.$foreign->id)->assertNotFound();
    }

    public function test_checkout_validates_amount_and_handles_missing_gateway(): void
    {
        Sanctum::actingAs($this->donor());
        // Kampanye demo punya tanggal selesai tetap; pakai kampanye yang masih menerima donasi.
        $campaign = Campaign::openForDonation()->first();
        if (!$campaign) $this->markTestSkipped('Requires an open campaign.');
        $this->postJson('/api/donations', ['id_campaign' => $campaign->id, 'nominal' => 9999])->assertUnprocessable();
        config(['services.midtrans.server_key' => null]);
        $this->postJson('/api/donations', ['id_campaign' => $campaign->id, 'nominal' => 10000])->assertStatus(503);
    }

    public function test_gateway_called_once_and_payment_requires_verified_callback(): void
    {
        $user = $this->donor();
        config(['services.midtrans.server_key' => 'checkout-test-secret']);
        $service = new class extends DonationService {
            public int $calls = 0;
            public array $payload = [];
            public array $gatewayStatus = [];
            protected function createGatewayTransaction(array $params): object {
                $this->calls++; $this->payload = $params;
                return (object) ['token' => 'test-snap', 'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/test'];
            }
            protected function fetchGatewayStatus(string $orderId): array {
                return $this->gatewayStatus;
            }
        };
        $result = $service->createDonationTransaction(['id_campaign' => 'demo-campaign-1', 'nominal' => 100000, 'anonim' => true], $user);
        $donation = $result['data']['donation'];
        $this->assertSame(1, $service->calls);
        $this->assertSame(103000, $service->payload['transaction_details']['gross_amount']);
        $this->assertSame('test-snap', $donation->fresh()->snap_token);
        $this->assertSame('belum_bayar', $donation->fresh()->status);
        $payload = ['order_id' => $donation->id, 'status_code' => '200', 'gross_amount' => '103000.00', 'transaction_status' => 'settlement'];
        $this->assertSame(401, $service->handleCallback($payload)['statusCode']);
        $payload['signature_key'] = hash('sha512', $donation->id.'200103000.00checkout-test-secret');

        // Notifikasi bertanda tangan sah tetapi Midtrans menyatakan transaksi masih pending:
        // transaction_status di notifikasi tidak ditandatangani, jadi donasi belum boleh lunas.
        $service->gatewayStatus = ['order_id' => $donation->id, 'gross_amount' => '103000.00', 'transaction_status' => 'pending'];
        $this->assertSame(200, $service->handleCallback($payload)['statusCode']);
        $this->assertSame('belum_bayar', $donation->fresh()->status);

        $service->gatewayStatus = ['order_id' => $donation->id, 'gross_amount' => '103000.00', 'transaction_status' => 'settlement', 'transaction_id' => 'mid-trx-1'];
        $this->assertSame(200, $service->handleCallback($payload)['statusCode']);
        $this->assertSame('sudah_bayar', $donation->fresh()->status);
        $paidAt = $donation->fresh()->paid_at;
        $service->handleCallback($payload);
        $this->assertSame($paidAt, $donation->fresh()->paid_at);
    }
}
