<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Nomor telepon akun staf/manajer, diisi Super Admin lewat form Ubah Data Akun.
     */
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('no_telp', 20)->nullable()->after('tipe');
        });
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('no_telp');
        });
    }
};
