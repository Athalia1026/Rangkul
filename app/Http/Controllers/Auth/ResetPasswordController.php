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
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email ini tidak terdaftar di sistem kami.'
        ]);

        // Meminta Laravel Password Broker mengirimkan email token reset
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json([
                'success' => true,
                'message' => 'Link reset password telah dikirimkan ke email Anda.'
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
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
    }

    private function resetUserPasswordCallback(): callable
    {
        return function ($user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->setRememberToken(Str::random(60));

            $user->save();

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