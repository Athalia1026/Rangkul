<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->enum('status', ['menunggu_bayar', 'aktif', 'nonaktif'])
                ->default('menunggu_bayar')
                ->change();
            $table->timestamp('started_at')->nullable()->change();
            $table->timestamp('expired_at')->nullable()->change();
            $table->string('transaction_id', 500)->nullable()->change();
            $table->timestamp('paid_at')->nullable()->change();
            $table->timestamp('reminder_sent_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->enum('status', ['aktif', 'nonaktif'])->default('nonaktif')->change();
            $table->timestamp('started_at')->nullable(false)->change();
            $table->timestamp('expired_at')->nullable(false)->change();
            $table->string('transaction_id', 500)->nullable(false)->change();
            $table->timestamp('paid_at')->nullable(false)->change();
            $table->dropColumn('reminder_sent_at');
        });
    }
};