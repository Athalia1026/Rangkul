<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('donations', function (Blueprint $table) {
            $table->text('snap_token')->nullable();
            $table->text('payment_url')->nullable();
            $table->unsignedInteger('payment_fee')->nullable();
        });
    }
    public function down(): void {
        Schema::table('donations', fn (Blueprint $table) => $table->dropColumn(['snap_token', 'payment_url', 'payment_fee']));
    }
};
