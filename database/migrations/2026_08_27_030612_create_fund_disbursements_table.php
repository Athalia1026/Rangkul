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
        Schema::create('fund_disbursements', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('id_campaign');
            $table->string('id_bank_account');
            $table->string('alokasi_dana');
            $table->decimal('nominal_diajukan', 15, 2);
            $table->text('alasan');
            $table->enum('status', ['diterima', 'ditolak', 'menunggu']);
            $table->text('alasan_tolak')->nullable();
            $table->string('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->decimal('nominal_dicairkan', 15, 2)->nullable();
            $table->string('transaction_id', 500)->nullable()->unique();
            $table->timestamps();

            $table->foreign('id_campaign')->references('id')->on('campaigns')->onDelete('cascade');
            $table->foreign('id_bank_account')->references('id')->on('bank_accounts')->onDelete('restrict');
            $table->foreign('verified_by')->references('id')->on('admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fund_disbursements');
    }
};
