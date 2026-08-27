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
        Schema::create('visit_documents', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('id_kunjungan');
            $table->string('lokasi_file');
            $table->timestamp('uploaded_at');

            $table->foreign('id_kunjungan')->references('id')->on('visits')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_documents');
    }
};
