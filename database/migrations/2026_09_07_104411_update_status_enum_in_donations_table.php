<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mengubah kolom status enum dengan menambahkan opsi 'gagal'
        DB::statement("ALTER TABLE donations MODIFY COLUMN status ENUM('belum_bayar', 'sudah_bayar', 'gagal') DEFAULT 'belum_bayar'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback ke enum semula jika diperlukan
        DB::statement("ALTER TABLE donations MODIFY COLUMN status ENUM('belum_bayar', 'sudah_bayar') DEFAULT 'belum_bayar'");
    }
};