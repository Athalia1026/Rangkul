<?php
namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Donor;
use App\Models\Organization;
use App\Http\Requests\RegisterDonorRequest;
use App\Http\Requests\RegisterOrganizationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use App\Models\OrganizationDocument;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Auth\ResubmitRegistrationRequest;
use Illuminate\Support\Facades\Storage;

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
            // Step 1: Simpan Foto Profil User (jika ada)
            $photoPath = $request->hasFile('profile_photo')
                ? $request->file('profile_photo')->store('profiles', 'public')
                : null;

            // Step 2: Buat User
            $user = User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'profile_photo' => $photoPath,
                'account_type' => 'organisasi',
                'status' => 'aktif',
            ]);

            // Step 3: Buat Record Organization
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
            ]);

            // Step 4: Simpan 4 Dokumen ke Tabel OrganizationDocument
            $documentTypes = [
                'sk_operasional' => 'documents/sk',
                'ktp_pj' => 'documents/ktp',
                'foto_bangunan' => 'documents/bangunan',
                'foto_kegiatan' => 'documents/kegiatan',
            ];

            foreach ($documentTypes as $type => $folder) {
                if ($request->hasFile($type)) {
                    $file = $request->file($type);
                    $path = $file->store($folder, 'public');

                    OrganizationDocument::create([
                        'id_organisasi' => $organization->id,
                        'nama_file' => $file->getClientOriginalName(),
                        'lokasi_file' => $path,
                        'status' => 'menunggu',
                        'uploaded_at' => now(),
                    ]);
                }
            }

            // Step 5: Generate Token Sanctum
            $token = $user->createToken('rangkul-org-token')->plainTextToken;

            return [
                'user' => $user->load('organization.documents'),
                'token' => $token,
            ];
        });

        return response()->json([
            'message' => 'Registrasi Organisasi berhasil (Dokumen Menunggu Verifikasi Admin)',
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

        if ($user->organization) {
            $status = $user->organization->verification_status;

            // A. Jika Status DITOLAK
            if ($status === 'ditolak') {
                $resubmitToken = $user->createToken('resubmit_token')->plainTextToken;

                return response()->json([
                    'message' => 'Pendaftaran organisasi Anda ditolak. Silakan ajukan ulang dokumen.',
                    'code' => 'ORGANIZATION_REJECTED',
                ], 403);
            }

            // B. Jika Status MENUNGGU Verifikasi Admin
            if ($status === 'menunggu' || $status === 'pending') {
                return response()->json([
                    'message' => 'Akun berhasil diverifikasi kredensialnya, namun organisasi Anda masih menunggu verifikasi oleh Admin.',
                    'code' => 'ORGANIZATION_UNVERIFIED'
                ], 403);
            }
        }

        // 5. Terbitkan Token jika Status Disetujui / User Biasa
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil.',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 200);


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

    public function resubmit(ResubmitRegistrationRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Kredensial email atau password tidak valid.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $organization = $user->organization;

        if (!$organization) {
            return response()->json([
                'message' => 'Data organisasi tidak ditemukan.'
            ], Response::HTTP_NOT_FOUND);
        }

        // 2. PROTEKSI: Hanya status DITOLAK yang bisa mengakses alur ini
        if ($organization->verification_status !== 'ditolak') {
            return response()->json([
                'message' => 'Akses ditolak. Fitur pengajuan ulang hanya untuk pendaftaran yang ditolak.'
            ], Response::HTTP_FORBIDDEN);
        }

        $organization->nama_lembaga = $request->nama_lembaga;
        $organization->tipe = $request->tipe;
        $organization->no_telp = $request->no_telp;
        $organization->deskripsi = $request->deskripsi;
        $organization->kota = $request->kota;
        $organization->alamat = $request->alamat;
        $organization->link_maps = $request->link_maps;
        $organization->jumlah_anak = $request->jumlah_anak;
        $organization->tahun_berdiri = $request->tahun_berdiri;
        $organization->verification_status = 'menunggu';
        $organization->alasan_penolakan = null;
        $organization->save();

        $existingDocuments = OrganizationDocument::where('id_organisasi', $organization->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $fileFields = [
            'sk_operasional' => 'sk',
            'ktp_pj' => 'ktp',
            'foto_bangunan' => 'bangunan',
            'foto_kegiatan' => 'kegiatan',
        ];

        foreach ($fileFields as $field => $keyword) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);

                // Path folder penyimpanan
                $folder = 'documents/' . $keyword;

                // Simpan file fisik baru ke storage public
                $newPath = $file->store($folder, 'public');

                // Cari dokumen lama berdasarkan kata kunci (keyword) nama folder
                $docToUpdate = null;
                foreach ($existingDocuments as $doc) {
                    if (str_contains(strtolower($doc->lokasi_file), $keyword)) {
                        $docToUpdate = $doc;
                        break;
                    }
                }

                if ($docToUpdate) {
                    // Hapus file fisik lama di storage jika ada
                    if (Storage::disk('public')->exists($docToUpdate->lokasi_file)) {
                        Storage::disk('public')->delete($docToUpdate->lokasi_file);
                    }

                    // UPDATE BARIS DOKUMEN LAMA
                    $docToUpdate->update([
                        'lokasi_file' => $newPath,
                        'nama_file' => $file->getClientOriginalName(),
                        'status' => 'menunggu',
                        'alasan_penolakan' => null,
                        'uploaded_at' => now(),
                    ]);
                } else {
                    // Fallback: Jika tidak cocok kata kunci, update baris dokumen pertama yang statusnya 'ditolak'
                    $rejectedDoc = $existingDocuments->where('status', 'ditolak')->first();

                    if ($rejectedDoc) {
                        $rejectedDoc->update([
                            'lokasi_file' => $newPath,
                            'nama_file' => $file->getClientOriginalName(),
                            'status' => 'menunggu',
                            'alasan_penolakan' => null,
                            'uploaded_at' => now(),
                        ]);
                    }
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Pendaftaran ulang berhasil dikirimkan. Silakan tunggu verifikasi admin.',
            'data' => $organization->fresh()
        ], 200);
    }
}