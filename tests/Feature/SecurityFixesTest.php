<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\BankAccount;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\FundDisbursement;
use App\Models\Organization;
use App\Models\Subscription;
use App\Models\User;
use App\Services\PremiumService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Regresi perbaikan audit keamanan: pengajuan ulang pendaftaran sebagai celah tebak password,
 * batas nominal & rekening tujuan pencairan dana, verifikasi ganda, dan middleware admin.
 */
class SecurityFixesTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        // Data uji di-rollback, tetapi index Meilisearch tidak: jangan sinkronkan ke mesin pencari.
        config(['scout.driver' => 'null']);
    }

    private function user(string $type, array $attributes = []): User
    {
        return User::create(array_merge([
            'nama' => 'Uji ' . $type,
            'email' => 'uji-' . $type . '-' . uniqid() . '@example.com',
            'password' => Hash::make('passwordbenar1'),
            'account_type' => $type,
            'status' => 'aktif',
        ], $attributes));
    }

    /** @return array{0: User, 1: Organization, 2: BankAccount} */
    private function organization(string $status = 'disetujui', string $bankStatus = 'diterima'): array
    {
        $user = $this->user('organisasi');
        $organization = Organization::create([
            'user_id' => $user->id,
            'tipe' => 'Panti Asuhan',
            'nama_lembaga' => 'Panti Uji ' . uniqid(),
            'no_telp' => '081234567890',
            'deskripsi' => 'Lembaga uji',
            'kota' => 'Bandung',
            'alamat' => 'Jl. Uji',
            'verification_status' => $status,
        ]);
        $bank = BankAccount::create([
            'id_organisasi' => $organization->id,
            'bank' => 'BCA',
            'no_rekening' => '1111111111',
            'pemilik_rekening' => 'Panti Uji',
            'status_verifikasi' => $bankStatus,
        ]);

        return [$user, $organization, $bank];
    }

    private function campaign(Organization $organization, int $paid, string $status = 'aktif'): Campaign
    {
        $categoryId = Category::value('id');
        if (!$categoryId) $this->markTestSkipped('Requires a category seed.');

        $campaign = Campaign::create([
            'id_organisasi' => $organization->id,
            'judul' => 'Kampanye Uji ' . uniqid(),
            'deskripsi' => 'Uji',
            'tanggal_mulai' => now()->subDays(5)->toDateString(),
            'tanggal_selesai' => now()->addDays(30)->toDateString(),
            'target_dana' => 10000000,
            'id_categories' => $categoryId,
            'status' => $status,
            'foto_cover' => 'campaigns/covers/uji.jpg',
        ]);

        if ($paid > 0) {
            $donor = Donor::create(['user_id' => $this->user('donatur')->id, 'tipe' => 'individu', 'no_telp' => '081200000000', 'kota' => 'Bandung']);
            Donation::create([
                'id_campaign' => $campaign->id,
                'id_donatur' => $donor->id,
                'nominal' => $paid,
                'status' => 'sudah_bayar',
                'payment_fee' => 3000,
                'paid_at' => now(),
            ]);
        }

        return $campaign;
    }

    private function admin(string $role): User
    {
        $user = $this->user('admin');
        Admin::create(['user_id' => $user->id, 'tipe' => $role, 'status_akun' => 'aktif']);

        return $user;
    }

    private function disbursement(Campaign $campaign, BankAccount $bank, float $amount): FundDisbursement
    {
        return FundDisbursement::create([
            'id_campaign' => $campaign->id,
            'id_bank_account' => $bank->id,
            'alokasi_dana' => 'Beli sembako',
            'nominal_diajukan' => $amount,
            'alasan' => 'Kebutuhan bulanan',
            'status' => 'menunggu',
        ]);
    }

    private function resubmit(string $email, string $password, int $ip)
    {
        return $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.' . $ip])
            ->postJson('/api/registration/resubmit', [
                'email' => $email,
                'password' => $password,
                'nama_lembaga' => 'Panti Uji',
                'tipe' => 'Panti Asuhan',
                'no_telp' => '081234567890',
                'deskripsi' => 'Uji',
                'kota' => 'Bandung',
                'alamat' => 'Jl. Uji',
                'bank' => 'BNI',
                'no_rekening' => '2222222222',
                'pemilik_rekening' => 'Panti Uji',
            ]);
    }

    // ---------------------------------------------------------------------
    // Pengajuan ulang pendaftaran
    // ---------------------------------------------------------------------

    public function test_resubmit_does_not_reveal_password_of_non_organization_accounts(): void
    {
        $admin = $this->admin(Admin::TIPE_MANAGER);
        $invalid = 'Kredensial email atau password tidak valid.';

        // Password benar dan password salah untuk akun non-organisasi memberi respons yang sama.
        $this->resubmit($admin->email, 'passwordbenar1', 1)->assertStatus(422)->assertJson(['message' => $invalid]);
        $this->resubmit($admin->email, 'passwordsalah1', 2)->assertStatus(422)->assertJson(['message' => $invalid]);
        // Email tak terdaftar juga sama (tidak ada lagi pesan "email tidak terdaftar").
        $this->resubmit('tidak-ada-' . uniqid() . '@example.com', 'passwordsalah1', 3)
            ->assertStatus(422)->assertJson(['message' => $invalid]);
    }

    public function test_resubmit_shares_the_login_lockout(): void
    {
        [$user] = $this->organization('ditolak');

        foreach (range(1, 5) as $ip) {
            $this->resubmit($user->email, 'passwordsalah1', $ip)->assertStatus(422);
        }

        // Terkunci untuk resubmit maupun login, dari IP mana pun, walau password benar.
        $this->resubmit($user->email, 'passwordbenar1', 6)->assertStatus(429);
        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.7'])
            ->postJson('/api/login', ['email' => $user->email, 'password' => 'passwordbenar1'])
            ->assertStatus(429);
    }

    public function test_rejected_organization_can_still_resubmit_and_bank_change_is_audited(): void
    {
        [$user, $organization, $bank] = $this->organization('ditolak', 'ditolak');

        $this->resubmit($user->email, 'passwordbenar1', 1)->assertOk();

        $this->assertSame('menunggu', $organization->fresh()->verification_status);
        $this->assertSame('2222222222', $bank->fresh()->no_rekening);
        $this->assertSame('menunggu', $bank->fresh()->status_verifikasi);
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'module' => 'bank_account',
            'subject_id' => $bank->id,
        ]);
    }

    // ---------------------------------------------------------------------
    // Pengajuan & persetujuan pencairan dana
    // ---------------------------------------------------------------------

    public function test_disbursement_request_cannot_exceed_campaign_balance(): void
    {
        [$user, $organization] = $this->organization();
        $campaign = $this->campaign($organization, 500000);
        Sanctum::actingAs($user);

        $this->postJson('/api/organizations/disbursements', [
            'id_campaign' => $campaign->id,
            'alokasi_dana' => 'Beli sembako',
            'nominal_diajukan' => 500001,
            'alasan' => 'Kebutuhan bulanan',
        ])->assertStatus(422)->assertJsonPath('status', 'error');

        $this->postJson('/api/organizations/disbursements', [
            'id_campaign' => $campaign->id,
            'alokasi_dana' => 'Beli sembako',
            'nominal_diajukan' => 500000,
            'alasan' => 'Kebutuhan bulanan',
        ])->assertCreated();

        // Pengajuan kedua tertahan karena yang pertama masih menunggu verifikasi.
        $this->postJson('/api/organizations/disbursements', [
            'id_campaign' => $campaign->id,
            'alokasi_dana' => 'Beli sembako',
            'nominal_diajukan' => 10000,
            'alasan' => 'Kebutuhan bulanan',
        ])->assertForbidden();
    }

    public function test_unverified_organization_cannot_request_disbursement_through_api(): void
    {
        [$user, $organization] = $this->organization('menunggu');
        $campaign = $this->campaign($organization, 500000);
        Sanctum::actingAs($user);

        $this->postJson('/api/organizations/disbursements', [
            'id_campaign' => $campaign->id,
            'alokasi_dana' => 'Beli sembako',
            'nominal_diajukan' => 100000,
            'alasan' => 'Kebutuhan bulanan',
        ])->assertForbidden();
    }

    public function test_manager_approval_is_capped_and_snapshots_destination_account(): void
    {
        [, $organization, $bank] = $this->organization();
        $campaign = $this->campaign($organization, 300000);
        $disbursement = $this->disbursement($campaign, $bank, 200000);
        Sanctum::actingAs($this->admin(Admin::TIPE_MANAGER));
        $url = '/api/admin/disbursements/' . $disbursement->id;

        $this->getJson($url)->assertOk()
            ->assertJsonPath('data.campaign_balance', 300000)
            ->assertJsonPath('data.destination_account.no_rekening', '1111111111');

        $this->putJson($url . '/verify', ['status' => 'diterima', 'nominal_dicairkan' => 200001])->assertStatus(422);
        $this->assertSame('menunggu', $disbursement->fresh()->status);

        $this->putJson($url . '/verify', ['status' => 'diterima'])->assertOk();
        $this->putJson($url . '/verify', ['status' => 'diterima'])->assertStatus(409);

        // Rekening lembaga diganti setelah disetujui: tujuan transfer tetap rekening yang disetujui.
        $bank->update(['no_rekening' => '9999999999']);
        $fresh = $disbursement->fresh();
        $this->assertSame('1111111111', $fresh->rekening_nomor);
        $this->assertSame('1111111111', $fresh->destinationAccount()['no_rekening']);

        $this->postJson($url . '/manual-transfer', ['referensi_transfer' => 'TRF-1'])->assertOk();
        $this->postJson($url . '/manual-transfer', ['referensi_transfer' => 'TRF-2'])->assertStatus(409);
        $this->assertSame('TRF-1', $disbursement->fresh()->transaction_id);
    }

    public function test_manager_cannot_approve_more_than_campaign_balance_or_to_unverified_account(): void
    {
        [, $organization, $bank] = $this->organization();
        $campaign = $this->campaign($organization, 100000);
        Sanctum::actingAs($this->admin(Admin::TIPE_MANAGER));

        // Pengajuan lama yang melebihi saldo (dibuat sebelum ada batas) tetap tidak bisa disetujui.
        $tooLarge = $this->disbursement($campaign, $bank, 150000);
        $this->putJson('/api/admin/disbursements/' . $tooLarge->id . '/verify', ['status' => 'diterima'])->assertStatus(422);

        $tooLarge->update(['status' => 'ditolak']);
        $bank->update(['status_verifikasi' => 'menunggu']);
        $pendingBank = $this->disbursement($campaign, $bank, 50000);
        $this->putJson('/api/admin/disbursements/' . $pendingBank->id . '/verify', ['status' => 'diterima'])->assertStatus(409);

        // Penolakan tetap bisa dilakukan.
        $this->putJson('/api/admin/disbursements/' . $pendingBank->id . '/verify', ['status' => 'ditolak', 'alasan_tolak' => 'Rekening belum valid'])->assertOk();
    }

    public function test_pages_showing_destination_account_still_render(): void
    {
        [$user, $organization, $bank] = $this->organization();
        $campaign = $this->campaign($organization, 100000);
        $disbursement = $this->disbursement($campaign, $bank, 50000);
        $disbursement->update(['status' => 'diterima', 'rekening_bank' => 'BCA', 'rekening_nomor' => '1111111111', 'rekening_pemilik' => 'Panti Uji']);
        $bank->update(['no_rekening' => '9999999999']);

        $this->actingAs($user, 'web')
            ->get(route('organisasi.kampanye.detail', $campaign->id))
            ->assertOk()
            ->assertSee('1111111111');

        $this->get(route('manager.detailpengajuan', $disbursement->id))
            ->assertOk()
            ->assertSee('saldoKampanye', false)
            ->assertSee('rekeningTujuan', false);
    }

    public function test_purchase_proof_amount_must_be_positive(): void
    {
        [$user, $organization, $bank] = $this->organization();
        $disbursement = $this->disbursement($this->campaign($organization, 100000), $bank, 50000);
        $disbursement->update(['status' => 'diterima']);
        Sanctum::actingAs($user);

        $this->post('/api/organizations/disbursements/' . $disbursement->id . '/proofs', [
            'bukti_file' => \Illuminate\Http\UploadedFile::fake()->create('bukti.pdf', 10, 'application/pdf'),
            'nominal' => -50000,
            'deskripsi' => 'Bukti',
        ], ['Accept' => 'application/json'])->assertStatus(422)->assertJsonValidationErrors('nominal');
    }

    // ---------------------------------------------------------------------
    // Callback premium
    // ---------------------------------------------------------------------

    public function test_premium_callback_uses_gateway_status_and_checks_amount(): void
    {
        $existing = Subscription::whereHas('company')->first();
        if (!$existing) $this->markTestSkipped('Requires Rangkul premium seed.');

        config(['services.midtrans.server_key' => 'premium-test-secret']);
        $orderId = 'PREM-UJI' . strtoupper(uniqid());
        $subscription = Subscription::create(['id_perusahaan' => $existing->id_perusahaan, 'status' => 'menunggu_bayar', 'transaction_id' => $orderId]);

        $service = new class extends PremiumService {
            public array $gatewayStatus = [];
            protected function fetchGatewayStatus(string $orderId): array { return $this->gatewayStatus; }
        };
        $total = $service->pricing()['total'];
        $payload = ['order_id' => $orderId, 'status_code' => '200', 'gross_amount' => $total . '.00', 'transaction_status' => 'settlement'];
        $payload['signature_key'] = hash('sha512', $orderId . '200' . $total . '.00premium-test-secret');

        // Notifikasi menyebut settlement, tetapi Midtrans menyatakan pending: belum aktif.
        $service->gatewayStatus = ['order_id' => $orderId, 'gross_amount' => $total . '.00', 'transaction_status' => 'pending'];
        $this->assertSame(200, $service->handleCallback($payload)['statusCode']);
        $this->assertSame('menunggu_bayar', $subscription->fresh()->status);

        // Nominal yang dibayar tidak sama dengan harga paket: ditolak.
        $service->gatewayStatus = ['order_id' => $orderId, 'gross_amount' => '1000.00', 'transaction_status' => 'settlement'];
        $this->assertSame(422, $service->handleCallback($payload)['statusCode']);
        $this->assertSame('menunggu_bayar', $subscription->fresh()->status);

        $service->gatewayStatus = ['order_id' => $orderId, 'gross_amount' => $total . '.00', 'transaction_status' => 'settlement'];
        $this->assertSame(200, $service->handleCallback($payload)['statusCode']);
        $this->assertSame('aktif', $subscription->fresh()->status);
    }

    // ---------------------------------------------------------------------
    // Verifikasi kampanye & middleware admin
    // ---------------------------------------------------------------------

    public function test_campaign_can_only_be_verified_while_pending(): void
    {
        [, $organization] = $this->organization();
        $pending = $this->campaign($organization, 0, 'menunggu');
        $rejected = $this->campaign($organization, 0, 'ditolak');
        Sanctum::actingAs($this->admin(Admin::TIPE_STAFF));

        $this->putJson('/api/admin/campaigns/' . $rejected->id . '/verify', ['status' => 'aktif'])->assertStatus(409);
        $this->assertSame('ditolak', $rejected->fresh()->status);

        $this->putJson('/api/admin/campaigns/' . $pending->id . '/verify', ['status' => 'aktif'])->assertOk();
        $this->assertSame('aktif', $pending->fresh()->status);
    }

    public function test_admin_without_admin_profile_is_denied(): void
    {
        Sanctum::actingAs($this->user('admin'));

        $this->getJson('/api/admin/dashboard')->assertForbidden();
    }

    public function test_active_admin_still_has_access(): void
    {
        Sanctum::actingAs($this->admin(Admin::TIPE_MANAGER));

        $this->getJson('/api/admin/users')->assertOk();
    }
}
