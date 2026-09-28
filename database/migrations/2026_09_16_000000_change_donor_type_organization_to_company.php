<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        DB::table('donors')
            ->where('tipe', 'organisasi')
            ->update(['tipe' => 'perusahaan']);

        Schema::table('donors', function (Blueprint $table) {
            $table->enum('tipe', ['individu', 'komunitas', 'perusahaan'])->change();
        });
    }

    public function down(): void
    {
        DB::table('donors')
            ->where('tipe', 'perusahaan')
            ->update(['tipe' => 'organisasi']);

        Schema::table('donors', function (Blueprint $table) {
            $table->enum('tipe', ['individu', 'komunitas', 'organisasi'])->change();
        });
    }
};