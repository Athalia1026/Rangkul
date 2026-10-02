<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use DatabaseTransactions;

    private function user(): User
    {
        return User::create([
            'nama' => 'Uji Reset Password',
            'email' => 'uji-reset-' . uniqid() . '@example.com',
            'password' => Hash::make('passwordlama1'),
            'account_type' => 'donatur',
            'status' => 'aktif',
        ]);
    }

    public function test_pages_render_and_login_links_to_forgot_password(): void
    {
        $this->get('/login')->assertOk()->assertSee(route('password.request'), false);
        $this->get('/lupa-password')->assertOk()->assertSee('Lupa Password?')->assertSee('Kirim Instruksi');
        $this->get('/lupa-password/cek-email')->assertOk()->assertSee('Cek Email Anda')->assertSee('Kirim Ulang Email');
        $this->get('/reset-password?token=abc&email=a%40b.com')->assertOk()
            ->assertSee('Atur Ulang Password')->assertSee('data-token="abc"', false)
            ->assertSee('Password Berhasil Diubah');
    }

    public function test_forgot_password_sends_link_to_reset_page(): void
    {
        Notification::fake();
        $user = $this->user();

        $this->postJson('/api/forgot-password', ['email' => $user->email])->assertOk();

        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use ($user) {
            $url = $notification->toMail($user)->actionUrl;

            return str_contains($url, '/reset-password?token=' . $notification->token)
                && str_contains($url, 'email=' . urlencode($user->email));
        });
    }

    public function test_reset_password_validates_and_updates_password(): void
    {
        $user = $this->user();
        $token = Password::createToken($user);

        $this->postJson('/api/reset-password', [
            'token' => $token, 'email' => $user->email,
            'password' => 'passwordbaru1', 'password_confirmation' => 'beda12345',
        ])->assertUnprocessable()->assertJsonPath('errors.password.0', 'Password dan konfirmasi password tidak cocok.');

        $this->postJson('/api/reset-password', [
            'token' => $token, 'email' => $user->email,
            'password' => 'hanyahuruf', 'password_confirmation' => 'hanyahuruf',
        ])->assertUnprocessable()->assertJsonValidationErrors('password');

        $this->postJson('/api/reset-password', [
            'token' => $token, 'email' => $user->email,
            'password' => 'passwordbaru1', 'password_confirmation' => 'passwordbaru1',
        ])->assertOk();

        $this->assertTrue(Hash::check('passwordbaru1', $user->fresh()->password));
    }
}
