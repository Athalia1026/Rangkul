<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Donasi/langganan yang tidak dibayar harus berakhir (gagal / nonaktif), dan kampanye yang
 * sudah lewat tanggal selesainya tidak boleh ditawarkan untuk donasi.
 */
class DonationExpiryTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        // Tanpa server key, cek status ke Midtrans gagal seperti saat transaksi belum ada (404).
        config(['services.midtrans.server_key' => '']);
    }

    private function donor(): User
    {
        $user = User::where('email', 'donatur@rangkul.test')->first();
        if (!$user) $this->markTestSkipped('Requires Rangkul demo seed.');

        return $user;
    }

    private function campaign(string $endsAt, string $judul = 'Kampanye Uji Kedaluwarsa'): Campaign
    {
        $source = Campaign::where('status', 'aktif')->first();
        if (!$source) $this->markTestSkipped('Requires Rangkul demo seed.');

        $campaign = $source->replicate(['sisa_hari']);
        $campaign->fill(['judul' => $judul . ' ' . uniqid(), 'status' => 'aktif', 'tanggal_selesai' => $endsAt]);
        $campaign->save();

        return $campaign;
    }

    private function donation(Campaign $campaign, array $attributes = []): Donation
    {
        $donation = Donation::create(array_merge([
            'id_campaign' => $campaign->id,
            'id_donatur' => $this->donor()->donor->id,
            'nominal' => 10000,
            'status' => 'belum_bayar',
            'payment_fee' => 3000,
        ], $attributes));

        if (isset($attributes['created_at'])) {
            $donation->forceFill(['created_at' => $attributes['created_at']])->saveQuietly();
        }

        return $donation;
    }

    public function test_unpaid_donation_for_ended_campaign_expires(): void
    {
        $donation = $this->donation($this->campaign(now()->subDays(2)->toDateString()));

        $this->artisan('donations:sync-midtrans')->assertSuccessful();

        $this->assertSame('gagal', $donation->fresh()->status);
    }

    public function test_unpaid_donation_past_payment_window_expires_even_with_snap_token(): void
    {
        $donation = $this->donation($this->campaign(now()->addDays(10)->toDateString()), [
            'snap_token' => 'token-uji', 'created_at' => now()->subHours(25),
        ]);

        $this->artisan('donations:sync-midtrans')->assertSuccessful();

        $this->assertSame('gagal', $donation->fresh()->status);
    }

    public function test_recent_unpaid_donation_for_running_campaign_stays_pending(): void
    {
        $donation = $this->donation($this->campaign(now()->addDays(10)->toDateString()), ['snap_token' => 'token-uji']);

        $this->artisan('donations:sync-midtrans')->assertSuccessful();

        $this->assertSame('belum_bayar', $donation->fresh()->status);
    }

    public function test_campaign_stays_open_until_end_of_its_last_day(): void
    {
        $this->assertTrue($this->campaign(today()->toDateString())->isOpenForDonation());
        $this->assertFalse($this->campaign(today()->subDay()->toDateString())->isOpenForDonation());
    }

    public function test_history_offers_only_actions_that_can_still_work(): void
    {
        $user = $this->donor();
        $ended = $this->donation($this->campaign(now()->subDay()->toDateString()));
        $running = $this->campaign(now()->addDays(10)->toDateString());
        $pending = $this->donation($running, ['snap_token' => 'token-uji']);
        $failed = $this->donation($running, ['status' => 'gagal']);

        Sanctum::actingAs($user);
        $items = collect($this->getJson('/api/donors/activities')->assertOk()->json('data.riwayat_donasi'))->keyBy('id');

        // Donasi untuk kampanye yang sudah berakhir langsung ditandai kedaluwarsa saat riwayat dibuka.
        $this->assertSame('gagal', $items[$ended->id]['status_asli']);
        $this->assertFalse($items[$ended->id]['can_continue_payment']);
        $this->assertFalse($items[$ended->id]['campaign_open']);

        $this->assertTrue($items[$pending->id]['can_continue_payment']);

        // Donasi gagal untuk kampanye yang masih berjalan: tawarkan donasi lagi, bukan lanjutkan bayar.
        $this->assertFalse($items[$failed->id]['can_continue_payment']);
        $this->assertTrue($items[$failed->id]['campaign_open']);
    }

    public function test_ended_campaign_is_hidden_from_search_and_cannot_be_donated(): void
    {
        $ended = $this->campaign(now()->subDay()->toDateString(), 'Kampanye Sudah Berakhir');
        $running = $this->campaign(now()->addDays(5)->toDateString(), 'Kampanye Masih Berjalan');

        $titles = collect($this->getJson('/api/search?type=campaign&limit=50')->json('data.campaigns'))->pluck('judul');
        $this->assertContains($running->judul, $titles);
        $this->assertNotContains($ended->judul, $titles);

        $this->get(route('campaign.detail', $ended->id))->assertOk()->assertSee('Kampanye Telah Berakhir');
        $this->get(route('campaign.detail', $running->id))->assertOk()->assertDontSee('Kampanye Telah Berakhir');
        $this->get(route('donatur.donasi', $ended->id))->assertRedirect(route('campaign.detail', $ended->id));
    }

    public function test_unpaid_premium_subscription_expires_after_payment_window(): void
    {
        $subscription = Subscription::whereHas('company')->first();
        if (!$subscription) $this->markTestSkipped('Requires Rangkul demo seed.');

        $stale = Subscription::create(['id_perusahaan' => $subscription->id_perusahaan, 'status' => 'menunggu_bayar', 'transaction_id' => 'PREM-UJI' . uniqid()]);
        $stale->forceFill(['created_at' => now()->subHours(25)])->saveQuietly();
        $fresh = Subscription::create(['id_perusahaan' => $subscription->id_perusahaan, 'status' => 'menunggu_bayar', 'transaction_id' => 'PREM-UJI' . uniqid()]);

        $this->artisan('donations:sync-midtrans')->assertSuccessful();

        $this->assertSame('nonaktif', $stale->fresh()->status);
        $this->assertSame('menunggu_bayar', $fresh->fresh()->status);
    }
}
