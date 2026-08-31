<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrganizationProfileController extends Controller
{
    // 1. Ambil Profil & Status Verifikasi Saat Ini
    public function show(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'organization_name' => $user->organization_name,
                'phone_number' => $user->phone_number,
                'address' => $user->address,
                'verification_status' => $user->verification_status,
                'rejection_reason' => $user->rejection_reason,
                'documents' => [
                    'sk_operasional' => $user->sk_operasional_path ? asset('storage/' . $user->sk_operasional_path) : null,
                    'ktp_pj' => $user->ktp_pj_path ? asset('storage/' . $user->ktp_pj_path) : null,
                    'foto_bangunan' => $user->foto_bangunan_path ? asset('storage/' . $user->foto_bangunan_path) : null,
                    'foto_kegiatan' => $user->foto_kegiatan_path ? asset('storage/' . $user->foto_kegiatan_path) : null,
                ]
            ]
        ]);
    }

    // 2. Update Informasi Profil & Unggah Dokumen
    public function update(Request $request)
    {
        $user = $request->user();

        // Validasi Data Teks dan Berkas
        $request->validate([
            'organization_name' => ['required', 'string', 'max:255'],
            'phone_number'      => ['required', 'string', 'max:20'],
            'address'           => ['required', 'string'],
            
            // Dokumen SK & KTP (PDF, JPG, PNG - Max 2MB)
            'sk_operasional'    => [$user->sk_operasional_path ? 'nullable' : 'required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'ktp_pj'            => [$user->ktp_pj_path ? 'nullable' : 'required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            
            // Foto Bangunan & Kegiatan (JPG, PNG, WEBP - Max 3MB)
            'foto_bangunan'     => [$user->foto_bangunan_path ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'foto_kegiatan'     => [$user->foto_kegiatan_path ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        $dataToUpdate = [
            'organization_name' => $request->organization_name,
            'phone_number'      => $request->phone_number,
            'address'           => $request->address,
            // Reset status ke pending jika user melakukan submit/update dokumen
            'verification_status' => 'pending',
            'rejection_reason'   => null,
        ];

        // Helper Internal untuk Upload & Hapus File Lama
        $this->uploadDocument($request, 'sk_operasional', 'documents/sk', $user->sk_operasional_path, $dataToUpdate, 'sk_operasional_path');
        $this->uploadDocument($request, 'ktp_pj', 'documents/ktp', $user->ktp_pj_path, $dataToUpdate, 'ktp_pj_path');
        $this->uploadDocument($request, 'foto_bangunan', 'documents/bangunan', $user->foto_bangunan_path, $dataToUpdate, 'foto_bangunan_path');
        $this->uploadDocument($request, 'foto_kegiatan', 'documents/kegiatan', $user->foto_kegiatan_path, $dataToUpdate, 'foto_kegiatan_path');

        $user->update($dataToUpdate);

        return response()->json([
            'status'  => 'success',
            'message' => 'Profil dan dokumen organisasi berhasil diperbarui. Menunggu verifikasi admin.',
        ]);
    }

    private function uploadDocument(Request $request, string $inputKey, string $folder, ?string $oldPath, array &$dataToUpdate, string $columnName): void
    {
        if ($request->hasFile($inputKey)) {
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
            $dataToUpdate[$columnName] = $request->file($inputKey)->store($folder, 'public');
        }
    }
}