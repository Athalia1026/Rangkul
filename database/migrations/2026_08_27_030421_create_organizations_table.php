<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id')->unique();
            $table->text('tipe');
            $table->string('nama_lembaga', 500);
            $table->string('no_telp');
            $table->text('deskripsi');
            $table->string('kota');
            $table->text('alamat');
            $table->string('link_maps', 500)->nullable();
            $table->integer('jumlah_anak')->nullable();
            $table->integer('tahun_berdiri')->nullable();
            $table->text('verification_status');
            $table->timestamp('verified_at')->nullable();
            $table->string('alasan_penolakan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
