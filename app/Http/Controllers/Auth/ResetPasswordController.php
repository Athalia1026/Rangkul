<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController extends Controller
{
    // 1. Kirim Email Link Reset Password
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email:rfc,filter',
        ]);

        // Meminta Laravel Password Broker mengirimkan email token reset
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Jawaban sama untuk email terdaftar maupun tidak, agar daftar akun tidak bisa ditebak.
        $handled = [Password::RESET_LINK_SENT, Password::INVALID_USER, Password::RESET_THROTTLED];

        return in_array($status, $handled, true)
            ? response()->json([
                'success' => true,
                'message' => 'Jika email terdaftar, link reset password telah dikirimkan ke email tersebut.'
            ], 200)
            : response()->json([
                'success' => false,
                'message' => 'Gagal mengirimkan link reset password.'
            ], 400);
    }

    // 2. Eksekusi Reset Password Baru
    public function resetPassword(Request $request)
    {
        $this->validateResetPasswordRequest($request);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            $this->resetUserPasswordCallback()
        );

        return $this->buildResetPasswordResponse($status);
    }

    private function validateResetPasswordRequest(Request $request): void
    {
        // Aturan password disamakan dengan registrasi: minimal 8 karakter, berisi huruf dan angka.
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email:rfc,filter',
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[A-Za-z])(?=.*[0-9]).+$/'],
        ], [
            'password.min' => 'Password harus terdiri dari minimal 8 karakter, termasuk huruf dan angka.',
            'password.regex' => 'Password harus terdiri dari minimal 8 karakter, termasuk huruf dan angka.',
            'password.confirmed' => 'Password dan konfirmasi password tidak cocok.',
        ]);
    }

    private function resetUserPasswordCallback(): callable
    {
        return function ($user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->setRememberToken(Str::random(60));

            $user->save();

            // Akhiri semua sesi login lama agar token yang mungkin dicuri tidak berlaku lagi.
            $user->tokens()->delete();

            event(new PasswordReset($user));
        };
    }

    private function buildResetPasswordResponse(string $status)
    {
        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'success' => true,
                'message' => 'Password Anda berhasil diperbarui. Silakan login kembali.',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Token reset password tidak valid atau sudah kadaluarsa.',
        ], 400);
    }
}