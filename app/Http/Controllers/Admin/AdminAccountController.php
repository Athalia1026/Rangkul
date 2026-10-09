<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminPasswordChangedMail;
use App\Models\Admin;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Pengelolaan akun staf & manajer oleh Super Admin (halaman Beranda Super Admin).
 * Akun super admin lain tidak bisa dilihat atau diubah lewat endpoint ini.
 */
class AdminAccountController extends Controller
{
    private const MANAGED_ROLES = [Admin::TIPE_STAFF, Admin::TIPE_MANAGER];

    // Status akun yang dihapus permanen (users.status & admins.status_akun).
    private const STATUS_DIHAPUS = 'dihapus';

    private const ROLE_LABELS = [
        Admin::TIPE_STAFF => 'Staff',
        Admin::TIPE_MANAGER => 'Manager',
    ];

    public function index()
    {
        $accounts = Admin::with('user')
            ->whereIn('tipe', self::MANAGED_ROLES)
            ->whereHas('user')
            ->oldest()
            ->get();

        $active = $accounts->where('status_akun', 'aktif');

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_staff' => $active->where('tipe', Admin::TIPE_STAFF)->count(),
                'total_manager' => $active->where('tipe', Admin::TIPE_MANAGER)->count(),
                'accounts' => $accounts->map(fn (Admin $admin) => $this->present($admin))->values(),
            ],
        ]);
    }

    // Akun baru dibuat dengan password acak; pemilik akun mengatur passwordnya sendiri lewat link reset.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'role' => ['required', Rule::in(self::MANAGED_ROLES)],
        ], [
            'email.unique' => 'Email ini sudah terdaftar.',
            'role.in' => 'Role harus Staff atau Manager.',
        ]);

        $admin = DB::transaction(function () use ($validated) {
            $user = User::create([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make(Str::random(40)),
                'account_type' => 'admin',
                'status' => 'aktif',
            ]);

            return Admin::create([
                'user_id' => $user->id,
                'tipe' => $validated['role'],
                'status_akun' => 'aktif',
            ]);
        });

        $linkSent = false;

        try {
            $linkSent = Password::sendResetLink(['email' => $validated['email']]) === Password::RESET_LINK_SENT;
        } catch (\Exception $e) {
            Log::error('SMTP Admin Account Reset Link Failed: ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => $linkSent
                ? 'Akun berhasil dibuat. Link untuk membuat password telah dikirim ke email akun.'
                : 'Akun berhasil dibuat, tetapi email link password gagal dikirim. Gunakan tombol Reset untuk mengatur password akun.',
            'data' => $this->present($admin->load('user')),
        ], 201);
    }

    public function update(Request $request, string $id)
    {
        $admin = $this->findManagedAdmin($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($admin->user_id)],
            'no_telp' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]*$/'],
        ], [
            'email.unique' => 'Email ini sudah terdaftar.',
            'no_telp.regex' => 'Nomor telepon hanya boleh berisi angka.',
        ]);

        DB::transaction(function () use ($admin, $validated) {
            $admin->user->update([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
            ]);

            $admin->update(['no_telp' => $validated['no_telp'] ?? null]);
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Data akun berhasil diperbarui.',
            'data' => $this->present($admin->fresh('user')),
        ]);
    }

    public function resetPassword(Request $request, string $id)
    {
        $admin = $this->findManagedAdmin($id);

        // Aturan password disamakan dengan registrasi: minimal 8 karakter, berisi huruf dan angka.
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[A-Za-z])(?=.*[0-9]).+$/'],
        ], [
            'password.min' => 'Password harus terdiri dari minimal 8 karakter, termasuk huruf dan angka.',
            'password.regex' => 'Password harus terdiri dari minimal 8 karakter, termasuk huruf dan angka.',
            'password.confirmed' => 'Password baru dan konfirmasi password tidak sesuai.',
        ]);

        $user = $admin->user;
        $user->update(['password' => Hash::make($validated['password'])]);

        // Sesi lama di semua perangkat diakhiri agar password baru langsung berlaku.
        $user->tokens()->delete();

        try {
            Mail::to($user->email)->send(new AdminPasswordChangedMail($user));
        } catch (\Exception $e) {
            Log::error('SMTP Admin Password Changed Failed: ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Password berhasil diubah.',
        ]);
    }

    public function deactivate(Request $request, string $id)
    {
        $admin = $this->findManagedAdmin($id);
        $validated = $request->validate([
            'alasan' => 'required|string|max:1000',
        ], [
            'alasan.required' => 'Alasan penonaktifan wajib diisi.',
        ]);

        DB::transaction(function () use ($admin, $validated) {
            $admin->update(['status_akun' => 'nonaktif', 'alasan_status' => $validated['alasan']]);
            $admin->user->update(['status' => 'nonaktif']);
            $admin->user->tokens()->delete();
        });

        app(ActivityLogService::class)->log(
            'deactivate',
            'admin_account',
            'Menonaktifkan akun ' . $admin->user->email . '. Alasan: ' . $validated['alasan'],
            $admin
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Akun berhasil dinonaktifkan.',
            'data' => $this->present($admin->fresh('user')),
        ]);
    }

    public function activate(string $id)
    {
        $admin = $this->findManagedAdmin($id);

        DB::transaction(function () use ($admin) {
            $admin->update(['status_akun' => 'aktif', 'alasan_status' => null]);
            $admin->user->update(['status' => 'aktif']);
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Akun berhasil diaktifkan kembali.',
            'data' => $this->present($admin->fresh('user')),
        ]);
    }

    // Hapus permanen tidak menghapus baris dari database: status diganti 'dihapus' dan deleted_at diisi
    // (soft delete) agar riwayat verifikasi tetap utuh, lalu akun tidak tampil dan tidak bisa login lagi.
    public function destroy(Request $request, string $id)
    {
        $admin = $this->findManagedAdmin($id);
        $validated = $request->validate([
            'alasan' => 'required|string|max:1000',
        ], [
            'alasan.required' => 'Alasan penghapusan wajib diisi.',
        ]);

        if ($admin->status_akun !== 'nonaktif') {
            return response()->json([
                'status' => 'error',
                'message' => 'Nonaktifkan akun terlebih dahulu sebelum menghapusnya.',
            ], 409);
        }

        DB::transaction(function () use ($admin, $validated) {
            $admin->update(['status_akun' => self::STATUS_DIHAPUS, 'alasan_status' => $validated['alasan']]);
            $admin->delete();
            $admin->user->update(['status' => self::STATUS_DIHAPUS]);
            $admin->user->tokens()->delete();
        });

        app(ActivityLogService::class)->log(
            'delete',
            'admin_account',
            'Menghapus permanen akun ' . $admin->user->email . '. Alasan: ' . $validated['alasan'],
            $admin
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Akun berhasil dihapus permanen.',
        ]);
    }

    private function findManagedAdmin(string $id): Admin
    {
        // Akun yang sudah dihapus (deleted_at terisi) otomatis tidak ditemukan karena SoftDeletes.
        return Admin::with('user')
            ->whereIn('tipe', self::MANAGED_ROLES)
            ->whereHas('user')
            ->findOrFail($id);
    }

    private function present(Admin $admin): array
    {
        return [
            'id' => $admin->id,
            'name' => $admin->user?->nama ?? '-',
            'email' => $admin->user?->email ?? '-',
            'phone' => $admin->no_telp,
            'role' => $admin->tipe,
            'role_label' => self::ROLE_LABELS[$admin->tipe] ?? ucfirst($admin->tipe),
            'status' => $admin->status_akun,
            'status_label' => $admin->status_akun === 'aktif' ? 'Aktif' : 'Nonaktif',
        ];
    }
}
