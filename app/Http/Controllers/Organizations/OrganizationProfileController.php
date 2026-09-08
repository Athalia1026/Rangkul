<?php
namespace App\Http\Controllers\Organizations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Organization;
use App\Models\Campaign;
use Carbon\Carbon;
use App\Models\OrganizationGallery;

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
            'phone_number' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],

            // Dokumen SK & KTP (PDF, JPG, PNG - Max 2MB)
            'sk_operasional' => [$user->sk_operasional_path ? 'nullable' : 'required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'ktp_pj' => [$user->ktp_pj_path ? 'nullable' : 'required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],

            // Foto Bangunan & Kegiatan (JPG, PNG, WEBP - Max 3MB)
            'foto_bangunan' => [$user->foto_bangunan_path ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'foto_kegiatan' => [$user->foto_kegiatan_path ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        $dataToUpdate = [
            'organization_name' => $request->organization_name,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            // Reset status ke pending jika user melakukan submit/update dokumen
            'verification_status' => 'pending',
            'rejection_reason' => null,
        ];

        // Helper Internal untuk Upload & Hapus File Lama
        $this->uploadDocument($request, 'sk_operasional', 'documents/sk', $user->sk_operasional_path, $dataToUpdate, 'sk_operasional_path');
        $this->uploadDocument($request, 'ktp_pj', 'documents/ktp', $user->ktp_pj_path, $dataToUpdate, 'ktp_pj_path');
        $this->uploadDocument($request, 'foto_bangunan', 'documents/bangunan', $user->foto_bangunan_path, $dataToUpdate, 'foto_bangunan_path');
        $this->uploadDocument($request, 'foto_kegiatan', 'documents/kegiatan', $user->foto_kegiatan_path, $dataToUpdate, 'foto_kegiatan_path');

        $user->update($dataToUpdate);

        return response()->json([
            'status' => 'success',
            'message' => 'Profil dan dokumen organisasi berhasil diperbarui. Menunggu verifikasi admin.',
        ]);
    }

    public function showPublicProfile(string $organizationId)
    {
        $organization = Organization::query()
            ->whereKey($organizationId)
            ->where('verification_status', 'disetujui')
            ->with(['galleries' => function ($query) {
                $query->select('id', 'organization_id', 'file_path', 'display_order');
            }])
            ->first();

        if (!$organization) {
            return response()->json([
                'status' => 'error',
                'message' => 'Organisasi tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $organization->id,
                'nama_lembaga' => $organization->nama_lembaga,
                'tipe' => $organization->tipe,
                'deskripsi' => $organization->deskripsi,
                'kota' => $organization->kota,
                'alamat' => $organization->alamat,
                'no_telp' => $organization->no_telp,
                'link_maps' => $organization->link_maps,
                'jumlah_anak' => $organization->jumlah_anak,
                'tahun_berdiri' => $organization->tahun_berdiri,
                'galleries' => $organization->galleries->map(fn (OrganizationGallery $gallery) => [
                    'id' => $gallery->id,
                    'image_url' => $gallery->image_url,
                    'display_order' => $gallery->display_order,
                ])->values(),
            ],
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

    public function getOrganizationCampaigns($organizationId)
    {

    
        // 1. Cek keberadaan organisasi
        $organization = Organization::find($organizationId);

        if (!$organization) {
            return response()->json([
                'status' => 'error',
                'message' => 'Organisasi tidak ditemukan.'
            ], 404);
        }
        // 2. Ambil campaign milik organisasi & hitung sum nominal dari relasi donations (hanya yang sudah_bayar)
        $campaigns = Campaign::where('id_organisasi', $organizationId)
            ->where('status', 'aktif')
            ->withSum([
                'donations as total_donasi_terkumpul' => function ($query) {
                    $query->where('status', 'sudah_bayar');
                }
            ], 'nominal')
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        // 3. Transformasi data agar persis sesuai UI Card
        $campaigns->getCollection()->transform(function ($campaign) use ($organization) {

            $targetDana = (int) $campaign->target_dana;

            // Ambil total terkumpul dari hasil agregasi query SUM tabel donations
            // Jika belum ada donasi, berikan nilai default 0
            $targetTerkumpul = (int) ($campaign->total_donasi_terkumpul ?? $campaign->target_terkumpul ?? 0);

            // Hitung persentase progress bar (max 100%)
            $persentase = $targetDana > 0
                ? min(100, round(($targetTerkumpul / $targetDana) * 100, 1))
                : 0;

            // Hitung sisa hari dari kolom deadline / tanggal_berakhir
            $sisaHari = $campaign->sisa_hari;

            

            return [
                'id' => $campaign->id,
                'judul' => $campaign->judul,
                'nama_organisasi' => $organization->nama_lembaga ?? $campaign->organization->nama_lembaga,
                'image_url' => $campaign->foto_cover ? asset('storage/' . $campaign->foto_cover) : null,
                'terkumpul' => $targetTerkumpul,
                'target' => $targetDana,
                'persentase' => $persentase,
                'sisa_hari' => $sisaHari,
                'status' => $campaign->status,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $campaigns
        ], 200);
    }
}