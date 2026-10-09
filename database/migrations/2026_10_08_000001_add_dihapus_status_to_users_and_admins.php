<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// Status 'dihapus': akun staf/manajer yang dihapus permanen oleh Super Admin. Barisnya tetap
// disimpan agar riwayat verifikasi (verified_by, staff_id, manager_id) tidak ikut hilang.
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY status ENUM('aktif', 'nonaktif', 'dihapus') NOT NULL");
        DB::statement("ALTER TABLE admins MODIFY status_akun ENUM('aktif', 'nonaktif', 'dihapus') NOT NULL DEFAULT 'aktif'");
    }

    public function down(): void
    {
        DB::table('users')->where('status', 'dihapus')->update(['status' => 'nonaktif']);
        DB::table('admins')->where('status_akun', 'dihapus')->update(['status_akun' => 'nonaktif']);
        DB::statement("ALTER TABLE users MODIFY status ENUM('aktif', 'nonaktif') NOT NULL");
        DB::statement("ALTER TABLE admins MODIFY status_akun ENUM('aktif', 'nonaktif') NOT NULL DEFAULT 'aktif'");
    }
};
