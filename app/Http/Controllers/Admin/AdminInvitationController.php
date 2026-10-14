<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminInvitationMail;
use App\Models\Admin;
use App\Models\AdminInvitation;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

/**
 * Super Admin menambah staf / manajer lewat link undangan: link dibuat otomatis per role, lalu
 * bisa disalin atau dikirim ke email calon admin. Akun baru dibuat sendiri oleh calon admin
 * di halaman pendaftaran (lihat Auth\AdminRegistrationController).
 */
class AdminInvitationController extends Controller
{
    private const ROLES = [Admin::TIPE_STAFF, Admin::TIPE_MANAGER];

    public function store(Request $request)
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(self::ROLES)],
        ], [
            'role.in' => 'Role harus Staff atau Manager.',
        ]);

        $invitation = AdminInvitation::create([
            'role' => $validated['role'],
            'created_by' => $request->user()->adminProfile->id,
            'expires_at' => now()->addHours(AdminInvitation::VALID_HOURS),
        ]);

        app(ActivityLogService::class)->log(
            'create',
            'admin_invitation',
            'Membuat link undangan pendaftaran ' . $invitation->roleLabel(),
            $invitation
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Link undangan ' . $invitation->roleLabel() . ' berhasil dibuat.',
            'data' => $this->present($invitation),
        ], 201);
    }

    public function send(Request $request, string $id)
    {
        $invitation = AdminInvitation::findOrFail($id);

        $validated = $request->validate([
            'email' => 'required|email:rfc,filter|max:255|unique:users,email',
        ], [
            'email.required' => 'Email tujuan wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar sebagai pengguna Rangkul.',
        ]);

        if (!$invitation->isUsable()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Link undangan ini sudah dipakai atau kedaluwarsa. Buat link baru terlebih dahulu.',
            ], 409);
        }

        // Link diikat ke email tujuan, sehingga pendaftaran hanya bisa memakai email tersebut.
        $invitation->email = $validated['email'];

        try {
            Mail::to($invitation->email)->send(new AdminInvitationMail($invitation));
        } catch (\Throwable $e) {
            Log::error('SMTP Admin Invitation Failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Email undangan gagal dikirim. Salin link dan kirimkan secara manual.',
            ], 502);
        }

        $invitation->save();

        app(ActivityLogService::class)->log(
            'update',
            'admin_invitation',
            'Mengirim link undangan ' . $invitation->roleLabel() . ' ke ' . $invitation->email,
            $invitation
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Undangan telah dikirim ke ' . $invitation->email . '.',
            'data' => $this->present($invitation),
        ]);
    }

    private function present(AdminInvitation $invitation): array
    {
        return [
            'id' => $invitation->id,
            'role' => $invitation->role,
            'role_label' => $invitation->roleLabel(),
            'email' => $invitation->email,
            'url' => $invitation->registrationUrl(),
            'expires_at' => $invitation->expires_at->toIso8601String(),
            'expires_label' => $invitation->expires_at->timezone(config('app.timezone'))->translatedFormat('d F Y H:i'),
        ];
    }
}
