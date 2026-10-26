<?php

namespace Tests\Feature;

use App\Mail\AdminInvitationMail;
use App\Mail\AdminPasswordChangedMail;
use App\Mail\DisbursementVerificationMail;
use App\Mail\OrganizationVerificationMail;
use App\Mail\PremiumActivatedMail;
use App\Mail\PremiumExpiringMail;
use App\Mail\PurchaseProofVerificationMail;
use App\Models\ActivityLog;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/** Penerapan CIA triad: header keamanan, penguncian login, audit trail append-only, antrean email, jadwal. */
class SecurityHardeningTest extends TestCase
{
    use DatabaseTransactions;

    private function user(): User
    {
        return User::create([
            'nama' => 'Uji Keamanan',
            'email' => 'uji-keamanan-' . uniqid() . '@example.com',
            'password' => Hash::make('passwordbenar1'),
            'account_type' => 'donatur',
            'status' => 'aktif',
        ]);
    }

    /** Setiap percobaan dari IP berbeda, seperti brute force terdistribusi yang lolos dari rate limit per IP. */
    private function attemptLogin(string $email, string $password, int $ip)
    {
        return $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.' . $ip])
            ->postJson('/api/login', ['email' => $email, 'password' => $password]);
    }

    public function test_security_headers_are_sent_on_every_response(): void
    {
        $response = $this->get('/login');

        $response->assertHeader('X-Frame-Options', 'DENY')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('Content-Security-Policy', "frame-ancestors 'none'; object-src 'none'; base-uri 'self'; form-action 'self'")
            ->assertHeaderMissing('Strict-Transport-Security');

        $this->getJson('/api/campaigns')->assertHeader('X-Frame-Options', 'DENY');
    }

    public function test_hsts_is_only_sent_over_https_when_enabled(): void
    {
        config(['security.hsts_max_age' => 300]);

        $this->get('http://localhost/login')->assertHeaderMissing('Strict-Transport-Security');
        $this->get('https://localhost/login')->assertHeader('Strict-Transport-Security', 'max-age=300; includeSubDomains');
    }

    public function test_account_is_locked_after_repeated_failures_from_many_ips(): void
    {
        $user = $this->user();

        foreach (range(1, 5) as $ip) {
            $this->attemptLogin($user->email, 'passwordsalah1', $ip)->assertStatus(401);
        }

        // Password benar pun ditolak selama terkunci.
        $this->attemptLogin($user->email, 'passwordbenar1', 6)
            ->assertStatus(429)
            ->assertJson(['message' => 'Terlalu banyak percobaan login gagal. Coba lagi dalam 15 menit.']);
    }

    public function test_successful_login_resets_failure_counter(): void
    {
        $user = $this->user();

        foreach (range(1, 4) as $ip) {
            $this->attemptLogin($user->email, 'passwordsalah1', $ip)->assertStatus(401);
        }
        $this->attemptLogin($user->email, 'passwordbenar1', 5)->assertOk();

        foreach (range(6, 9) as $ip) {
            $this->attemptLogin($user->email, 'passwordsalah1', $ip)->assertStatus(401);
        }
        $this->attemptLogin($user->email, 'passwordbenar1', 10)->assertOk();
    }

    public function test_unregistered_email_is_locked_the_same_way(): void
    {
        $email = 'tidak-terdaftar-' . uniqid() . '@example.com';

        foreach (range(1, 5) as $ip) {
            $this->attemptLogin($email, 'passwordsalah1', $ip)->assertStatus(401);
        }

        // Respons sama dengan akun terdaftar, sehingga penguncian tidak membocorkan email mana yang terdaftar.
        $this->attemptLogin($email, 'passwordsalah1', 6)->assertStatus(429);
    }

    public function test_activity_log_cannot_be_updated_or_deleted(): void
    {
        $log = app(ActivityLogService::class)->log('login', 'auth', 'Uji audit trail', null, null, null, $this->user());
        $this->assertNotNull($log);

        try {
            $log->update(['description' => 'diubah']);
            $this->fail('Log aktivitas seharusnya tidak bisa diubah.');
        } catch (\LogicException) {
        }

        try {
            $log->delete();
            $this->fail('Log aktivitas seharusnya tidak bisa dihapus.');
        } catch (\LogicException) {
        }

        // Query mentah di luar Eloquent juga ditolak trigger database.
        $this->expectException(QueryException::class);
        DB::table('activity_logs')->where('id', $log->id)->update(['description' => 'diubah']);
    }

    public function test_activity_log_rows_are_still_readable_after_blocked_changes(): void
    {
        $log = app(ActivityLogService::class)->log('login', 'auth', 'Uji baca log', null, null, null, $this->user());

        $this->assertSame('Uji baca log', ActivityLog::find($log->id)->description);
    }

    public function test_notification_mails_are_queued_except_admin_invitation(): void
    {
        foreach ([
            AdminPasswordChangedMail::class,
            DisbursementVerificationMail::class,
            OrganizationVerificationMail::class,
            PremiumActivatedMail::class,
            PremiumExpiringMail::class,
            PurchaseProofVerificationMail::class,
        ] as $mail) {
            $this->assertTrue(is_subclass_of($mail, ShouldQueue::class), "{$mail} harus lewat antrean.");
        }

        // Undangan admin dikirim langsung agar super admin tahu saat itu juga bila email gagal terkirim.
        $this->assertFalse(is_subclass_of(AdminInvitationMail::class, ShouldQueue::class));
    }

    public function test_scheduler_runs_reminders_queue_and_backups(): void
    {
        $this->artisan('schedule:list')
            ->expectsOutputToContain('premium:send-expiry-reminders')
            ->expectsOutputToContain('queue:work')
            ->expectsOutputToContain('backup:create')
            ->expectsOutputToContain('backup:verify')
            ->assertSuccessful();
    }
}
