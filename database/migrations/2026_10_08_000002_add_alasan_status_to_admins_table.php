<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Alasan penonaktifan / penghapusan akun staf-manajer dari Super Admin. Dikosongkan lagi saat akun diaktifkan.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->text('alasan_status')->nullable()->after('status_akun');
        });

        // Akun yang sudah berstatus 'dihapus' sebelum kolom ini ada: tandai deleted_at dan
        // ambil alasannya dari activity log terakhir (format "... Alasan: <teks>").
        DB::table('admins')
            ->where('status_akun', 'dihapus')
            ->whereNull('deleted_at')
            ->update(['deleted_at' => DB::raw('updated_at')]);

        DB::table('admins')
            ->whereIn('status_akun', ['nonaktif', 'dihapus'])
            ->pluck('id')
            ->each(function (string $adminId) {
                $description = DB::table('activity_logs')
                    ->where('module', 'admin_account')
                    ->where('subject_id', $adminId)
                    ->latest('created_at')
                    ->value('description');

                if ($description && preg_match('/Alasan: (.*)$/s', $description, $match)) {
                    DB::table('admins')->where('id', $adminId)->update(['alasan_status' => $match[1]]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('alasan_status');
        });
    }
};
