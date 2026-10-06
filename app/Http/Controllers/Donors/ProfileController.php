<?php

namespace App\Http\Controllers\Donors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // 1. Get Profile
    public function show(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data profil berhasil diambil',
            'data' => $request->user()
        ], 200);
    }

    // 2. Update Profile Data (khusus donatur)
    public function update(Request $request)
    {
        $user = $request->user();
        abort_unless($user->account_type === 'donatur', 403);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_telp' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'kota' => 'required|string|max:255',
        ], [
            'required' => 'Kolom ini wajib diisi.',
            'max' => 'Maksimal :max karakter.',
            'no_telp.regex' => 'Nomor telepon hanya boleh berisi angka, spasi, + atau -.',
        ]);

        // Email tidak dapat diubah dari halaman profil.
        $user->update(['nama' => $validated['nama']]);
        $user->donor()->updateOrCreate(
            ['user_id' => $user->id],
            ['no_telp' => $validated['no_telp'], 'kota' => $validated['kota']]
        );

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => $user->fresh('donor'),
        ], 200);
    }

    // 3. Upload / Update Avatar
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,jpg,png|max:2048', // Maks 2MB
        ]);

        $user = $request->user();

        // Hapus avatar lama jika ada
        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Simpan file avatar baru ke storage/app/public/avatars
        $path = $request->file('profile_photo')->store('ProfilePhotos', 'public');

        // Simpan path relatif ke database
        $user->update(['profile_photo' => $path]);

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil diunggah',
            'profile_photo_url' => asset('storage/' . $path)
        ], 200);
    }
}