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
        Schema::create('companies_premium', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('id_donatur')->unique();
            $table->string('nama_pic');
            $table->string('jabatan');
            $table->string('nomor_pic');
            $table->string('email_korporat');
            $table->string('NPWP');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_donatur')->references('id')->on('donors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies_premium');
    }
};
