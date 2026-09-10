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
        Schema::table('fund_disbursements', function (Blueprint $table) {
            $table->string('transfer_method')->nullable()->after('transaction_id');
            $table->enum('transfer_status', ['menunggu_transfer_manual', 'selesai', 'gagal'])->nullable()->after('transfer_method');
            $table->string('manual_transfer_proof')->nullable()->after('transfer_status');
            $table->timestamp('paid_at')->nullable()->after('manual_transfer_proof');
            $table->text('transfer_note')->nullable()->after('paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fund_disbursements', function (Blueprint $table) {
            $table->dropColumn([
                'transfer_method',
                'transfer_status',
                'manual_transfer_proof',
                'paid_at',
                'transfer_note',
            ]);
        });
    }
};
