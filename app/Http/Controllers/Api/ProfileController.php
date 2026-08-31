<?php

namespace App\Http\Controllers\Api;

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

    // 2. Update Profile Data
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
        ]);

        $validatedDonor = $request->validate([
            'phone_number' => 'sometimes|string|max:20',
            'city' => 'sometimes|string|max:255',
        ]);

        if (!empty($validatedUser)) {
            $user->update($validatedUser);
        }

        if (!empty($validatedDonor)) {
        $user->donor()->updateOrCreate(
            ['user_id' => $user->id], // Kunci pencarian relasi
            $validatedDonor
        );
    }
        $user->load('donor');
        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => $user
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