<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Undangan pendaftaran akun staf / manajer yang dibuat Super Admin.
     * Satu baris = satu link undangan (sekali pakai, ada masa berlaku).
     */
    public function up(): void
    {
        Schema::create('admin_invitations', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->enum('role', ['manager', 'staff']);
            // Diisi saat link dikirim lewat email; pendaftaran lalu wajib memakai email ini.
            $table->string('email')->nullable();
            $table->string('created_by');
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->string('used_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('admins')->cascadeOnDelete();
            $table->foreign('used_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_invitations');
    }
};
