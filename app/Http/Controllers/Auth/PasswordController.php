<?php
namespace App\Http\Controllers\Auth;

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
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password salah. Silahkan coba lagi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password harus terdiri dari minimal 8 karakter, termasuk huruf dan angka.',
            'password.letters' => 'Password harus terdiri dari minimal 8 karakter, termasuk huruf dan angka.',
            'password.numbers' => 'Password harus terdiri dari minimal 8 karakter, termasuk huruf dan angka.',
            'password.confirmed' => 'Password dan konfirmasi password tidak cocok.',
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