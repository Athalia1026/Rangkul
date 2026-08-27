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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('id_perusahaan');
            $table->enum('status', ['aktif', 'nonaktif']);
            $table->timestamp('started_at');
            $table->timestamp('expired_at');
            $table->string('transaction_id', 500)->unique();
            $table->timestamp('paid_at');
            $table->timestamps();

            $table->foreign('id_perusahaan')->references('id')->on('companies_premium')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
