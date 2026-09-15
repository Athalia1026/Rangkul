<?php
namespace App\Http\Controllers\Organizations;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Organization;
use App\Models\OrganizationGallery;
use Illuminate\Http\Request;

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

    // 2. Update Informasi Profil Organisasi
    public function update(Request $request)
    {
        $organization = $request->user()->organization;

        if (!$organization) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data organisasi tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'nama_lembaga' => ['sometimes', 'string', 'max:500'],
            'tipe' => ['sometimes', 'string'],
            'no_telp' => ['sometimes', 'string', 'max:255'],
            'deskripsi' => ['sometimes', 'string'],
            'kota' => ['sometimes', 'string', 'max:255'],
            'alamat' => ['sometimes', 'string'],
            'link_maps' => ['sometimes', 'nullable', 'url', 'max:500'],
            'jumlah_anak' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'tahun_berdiri' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:' . now()->year],
        ]);

        $organization->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Profil organisasi berhasil diperbarui.',
            'data' => $organization->fresh(),
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