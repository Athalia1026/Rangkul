<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminInvitation;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

/**
 * Halaman pendaftaran staf / manajer dari link undangan Super Admin.
 *
 * Tanda tangan link diperiksa di sini (bukan lewat middleware 'signed') agar link yang salah,
 * sudah dipakai, atau kedaluwarsa menampilkan alasannya di halaman 403, bukan "Invalid signature".
 */
class AdminRegistrationController extends Controller
{
    public function show(Request $request, string $invitation)
    {
        [$invitationModel, $error] = $this->resolveInvitation($request, $invitation);

        abort_if($error !== null, 403, (string) $error);

        return view('auth.register-admin', ['invitation' => $invitationModel]);
    }

    public function store(Request $request, string $invitation)
    {
        [$invitationModel, $error] = $this->resolveInvitation($request, $invitation);

        abort_if($error !== null, 403, (string) $error);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc,filter|max:255|unique:users,email',
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]{8,20}$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[A-Za-z])(?=.*[0-9]).+$/'],
            'agreement' => 'accepted',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'phone.regex' => 'Nomor telepon terdiri dari 8–20 digit angka.',
            'password.min' => 'Password harus terdiri dari minimal 8 karakter, termasuk huruf dan angka.',
            'password.regex' => 'Password harus terdiri dari minimal 8 karakter, termasuk huruf dan angka.',
            'password.confirmed' => 'Password dan konfirmasi password tidak cocok.',
            'agreement.accepted' => 'Anda harus menyetujui pakta integritas untuk melanjutkan.',
        ]);

        // Undangan yang dikirim lewat email hanya bisa dipakai oleh email tersebut.
        if ($invitationModel->email && strcasecmp($invitationModel->email, $validated['email']) !== 0) {
            throw ValidationException::withMessages([
                'email' => 'Gunakan email yang menerima undangan: ' . $invitationModel->email . '.',
            ]);
        }

        // Baris undangan dikunci agar satu link tidak bisa dipakai dua kali secara bersamaan.
        $user = DB::transaction(function () use ($invitationModel, $validated) {
            $locked = AdminInvitation::lockForUpdate()->find($invitationModel->id);

            if (!$locked || !$locked->isUsable()) {
                return null;
            }

            $user = User::create([
                'nama' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'account_type' => 'admin',
                'status' => 'aktif',
            ]);

            // Role diambil dari undangan, bukan dari isian formulir.
            Admin::create([
                'user_id' => $user->id,
                'tipe' => $locked->role,
                'status_akun' => 'aktif',
                'no_telp' => $validated['phone'],
            ]);

            $locked->update(['used_at' => now(), 'used_by' => $user->id]);

            return $user;
        });

        abort_if(!$user, 403, 'Link undangan ini sudah dipakai untuk membuat akun. Silakan masuk, atau minta link baru ke Super Admin.');

        app(ActivityLogService::class)->log(
            'register',
            'auth',
            'Mendaftar sebagai ' . $invitationModel->roleLabel() . ' melalui link undangan',
            $user,
            null,
            null,
            $user
        );

        return redirect()->route('login');
    }

    /**
     * @return array{0: ?AdminInvitation, 1: ?string} undangan yang masih berlaku, atau pesan galat
     */
    private function resolveInvitation(Request $request, string $id): array
    {
        $invalid = 'Link undangan tidak valid. Pastikan Anda membuka link lengkap dari email atau dari Super Admin.';

        if (!URL::hasCorrectSignature($request)) {
            return [null, $invalid];
        }

        $invitation = AdminInvitation::find($id);

        if (!$invitation) {
            return [null, $invalid];
        }

        if ($invitation->isUsed()) {
            return [null, 'Link undangan ini sudah dipakai untuk membuat akun. Silakan masuk, atau minta link baru ke Super Admin.'];
        }

        if ($invitation->isExpired() || !URL::signatureHasNotExpired($request)) {
            return [null, 'Link undangan sudah kedaluwarsa. Minta link baru ke Super Admin.'];
        }

        return [$invitation, null];
    }
}
