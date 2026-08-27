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
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('id_organisasi')->unique();
            $table->string('bank');
            $table->string('no_rekening');
            $table->string('pemilik_rekening');
            $table->enum('status_verifikasi', ['diterima', 'ditolak', 'menunggu']);
            $table->timestamps();

            $table->foreign('id_organisasi')->references('id')->on('organizations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
