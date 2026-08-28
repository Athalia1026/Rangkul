<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Donor;
use App\Models\Organization;
use App\Http\Requests\RegisterDonorRequest;
use App\Http\Requests\RegisterOrganizationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function registerDonor(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            // Step A: Buat User
            $user = User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'account_type' => 'donatur',
                'status' => 'aktif',
            ]);

            // Step B: Buat Record Donor
            $donor = Donor::create([
                'user_id' => $user->id,
                'tipe' => $request->tipe,
                'no_telp' => $request->no_telp,
                'kota' => $request->kota,
            ]);

            // Step C: Generate Token Sanctum
            $token = $user->createToken('rangkul-donor-token')->plainTextToken;

            return [
                'user' => $user->load('donor'),
                'token' => $token,
            ];
        });

        return response()->json([
            'message' => 'Registrasi Donatur berhasil',
            'data' => $result['user'],
            'token' => $result['token'],
        ], 201);
    }

    // 2. REGISTRASI ORGANISASI
    public function registerOrganization(RegisterOrganizationRequest $request)
    {
        $result = DB::transaction(function () use ($request) {
            // Step A: Buat User
            $user = User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'account_type' => 'organisasi',
                'status' => 'aktif',
            ]);

            // Step B: Buat Record Organization
            $organization = Organization::create([
                'user_id' => $user->id,
                'nama_lembaga' => $request->nama_lembaga,
                'tipe' => $request->tipe,
                'no_telp' => $request->no_telp,
                'deskripsi' => $request->deskripsi,
                'kota' => $request->kota,
                'alamat' => $request->alamat,
                'link_maps' => $request->link_maps,
                'jumlah_anak' => $request->jumlah_anak,
                'tahun_berdiri' => $request->tahun_berdiri,
                'verification_status' => 'menunggu', // Status awal verifikasi
            ]);

            // Step C: Generate Token Sanctum
            $token = $user->createToken('rangkul-org-token')->plainTextToken;

            return [
                'user' => $user->load('organization'),
                'token' => $token,
            ];
        });

        return response()->json([
            'message' => 'Registrasi Organisasi berhasil (Menunggu Verifikasi Admin)',
            'data' => $result['user'],
            'token' => $result['token'],
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
                'message' => 'Email atau password salah.'
            ], 401);
        }

        // Cek apakah akun nonaktif
        if ($user->status === 'nonaktif') {
            return response()->json([
                'message' => 'Akun Anda dinonaktifkan. Hubungi admin.'
            ], 403);
        }

        if ($user->account_type === 'donatur') {
            $user->load('donor');
        } elseif ($user->account_type === 'organisasi') {
            $user->load('organization');
        } elseif ($user->account_type === 'admin') {
            $user->load('admin');
        }

        $token = $user->createToken('rangkul-login-token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'user' => $user,
            'token' => $token
        ], 200);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        // Load data relasi pendukung
        if ($user->account_type === 'donatur') {
            $user->load('donor');
        } elseif ($user->account_type === 'organisasi') {
            $user->load('organization');
        } elseif ($user->account_type === 'admin') {
            $user->load('admin');
        }

        return response()->json([
            'user' => $user
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
}