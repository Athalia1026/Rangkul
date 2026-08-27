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
        Schema::create('proof_verifications', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('id_bukti')->unique();
            $table->string('staff_id');
            $table->string('manager_id');
            $table->enum('status', ['diterima', 'ditolak', 'menunggu']);
            $table->text('catatan')->nullable();
            $table->timestamp('verified_at')->nullable();

            $table->foreign('id_bukti')->references('id')->on('purchase_proofs')->onDelete('cascade');
            $table->foreign('staff_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('manager_id')->references('id')->on('admins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proof_verifications');
    }
};
