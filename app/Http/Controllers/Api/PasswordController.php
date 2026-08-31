<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function update(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.current_password' => 'Password lama yang Anda masukkan tidak sesuai.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = $request->user();

        // 2. Update Password dengan Hash baru
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        // 3. Opsional: Hapus token perangkat lain demi keamanan
        $user->tokens()->where('id', '!=', $user->currentAccessToken()->id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Password berhasil diperbarui.',
        ], 200);
    }
}