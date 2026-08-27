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
        Schema::create('donations', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('id_campaign');
            $table->string('id_donatur');
            $table->decimal('nominal', 15, 2);
            $table->string('note')->nullable();
            $table->enum('status', ['belum_bayar', 'sudah_bayar']);
            $table->boolean('anonim')->default(false);
            $table->string('transaction_id', 500)->nullable()->unique();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('id_campaign')->references('id')->on('campaigns')->onDelete('cascade');
            $table->foreign('id_donatur')->references('id')->on('donors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
