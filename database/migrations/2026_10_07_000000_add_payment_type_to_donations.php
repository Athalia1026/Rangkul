<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Metode pembayaran donasi dari Midtrans (payment_type), mis. qris, gopay, bank_transfer.
     */
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->string('payment_type', 50)->nullable()->after('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn('payment_type');
        });
    }
};
