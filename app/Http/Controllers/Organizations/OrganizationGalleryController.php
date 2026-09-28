<?php

namespace App\Http\Controllers\Organizations;

use App\Http\Controllers\Controller;
use App\Models\OrganizationGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrganizationGalleryController extends Controller
{
    /**
     * Tambah Foto Baru (Memerlukan Login Organisasi)
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
            'display_order' => 'nullable|integer'
        ]);

        $organization = $request->user()->organization;

        if (!$organization) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akun ini bukan akun organisasi.'
            ], 403);
        }

        if ($organization->verification_status !== 'disetujui') {
            return response()->json([
                'status' => 'error',
                'message' => 'Organisasi Anda belum terverifikasi oleh Admin.'
            ], 403);
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('galleries', 'public');

            $gallery = OrganizationGallery::create([
                'id' => (string) Str::uuid(),
                'organization_id' => $organization->id,
                'file_path' => $path,
                'display_order' => $request->display_order ?? 0,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Foto galeri berhasil diunggah.',
                'data' => [
                    'id' => $gallery->id,
                    'image_url' => $gallery->image_url,
                    'display_order' => $gallery->display_order
                ]
            ], 201);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'File gambar tidak ditemukan.'
        ], 400);
    }

    /**
     * Hapus Foto (Memerlukan Login Organisasi)
     */
    public function destroy(Request $request, $id)
    {
        $organization = $request->user()->organization;
        
        // Pastikan foto yang dihapus milik organisasi yang sedang login
        $gallery = OrganizationGallery::where('id', $id)
            ->where('organization_id', $organization?->id)
            ->first();

        if (!$gallery) {
            return response()->json([
                'status' => 'error',
                'message' => 'Foto tidak ditemukan atau Anda tidak memiliki akses.'
            ], 404);
        }

        // Hapus fisik file dari folder storage
        if (Storage::disk('public')->exists($gallery->file_path)) {
            Storage::disk('public')->delete($gallery->file_path);
        }

        $gallery->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Foto galeri berhasil dihapus.'
        ], 200);
    }
}