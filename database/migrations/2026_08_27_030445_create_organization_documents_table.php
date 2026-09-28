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
        if (!Schema::hasTable('organization_documents')) {
            Schema::create('organization_documents', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->string('id_organisasi');
                $table->string('lokasi_file');
                $table->string('nama_file');
                $table->enum('status', ['diterima', 'ditolak', 'menunggu']);
                $table->string('alasan_penolakan')->nullable();
                $table->timestamp('uploaded_at');
                $table->timestamp('verified_at')->nullable();
                $table->string('verified_by')->nullable();
                $table->timestamps();

                $table->foreign('id_organisasi')->references('id')->on('organizations')->onDelete('cascade');
                $table->foreign('verified_by')->references('id')->on('admins')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_documents');
    }
};
