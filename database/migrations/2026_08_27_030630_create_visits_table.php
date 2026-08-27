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
        Schema::create('visits', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('id_organisasi');
            $table->string('id_donatur');
            $table->date('tanggal_kunjungan');
            $table->time('waktu_kunjungan');
            $table->integer('pengunjung');
            $table->string('pesan_donatur')->nullable();
            $table->string('pesan_organisasi')->nullable();
            $table->enum('status', ['terkirim', 'dikonfirmasi', 'ditolak', 'selesai']);
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            $table->foreign('id_organisasi')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('id_donatur')->references('id')->on('donors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
