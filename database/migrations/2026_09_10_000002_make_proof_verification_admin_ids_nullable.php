<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('proof_verifications', function (Blueprint $table) {
            $table->string('staff_id')->nullable()->change();
            $table->string('manager_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proof_verifications', function (Blueprint $table) {
            $table->string('staff_id')->nullable(false)->change();
            $table->string('manager_id')->nullable(false)->change();
        });
    }
};
