<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
            'account_type' => 'required|in:admin,donatur,organisasi',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'account_type' => $request->account_type,
            'status' => 'aktif',
        ]);

        // TODO: Anda perlu menambahkan logika untuk membuat record di tabel terkait 
        // (donors, admins, organizations) berdasarkan account_type di sini nantinya.

        $token = $user->createToken('rangkul-auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Kredensial tidak valid.'
            ], 401);
        }

        // Cek apakah akun nonaktif
        if ($user->status === 'nonaktif') {
            return response()->json([
                'message' => 'Akun Anda dinonaktifkan. Hubungi admin.'
            ], 403);
        }

        $token = $user->createToken('rangkul-auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'user' => $user,
            'token' => $token
        ], 200);
    }

    public function logout(Request $request)
    {
        // Menghapus token yang sedang digunakan
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ], 200);
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ], 200);
    }
}