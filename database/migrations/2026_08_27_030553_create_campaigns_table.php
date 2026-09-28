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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('id_organisasi');
            $table->string('judul');
            $table->text('deskripsi');
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->decimal('target_dana', 15, 2);
            $table->string('id_categories', 500);
            $table->enum('status', ['menunggu', 'ditolak', 'aktif', 'disalurkan', 'selesai']);
            $table->string('foto_cover', 500);
            $table->text('alasan_tolak')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->string('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();

            $table->foreign('verified_by')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('id_organisasi')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('id_categories')->references('id')->on('categories')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
