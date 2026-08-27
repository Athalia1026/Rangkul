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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id');
            $table->string('action');
            $table->string('module');
            $table->string('subject_id');
            $table->string('subject_type');
            $table->text('description');
            $table->json('previous_data')->nullable();
            $table->json('new_data')->nullable();
            $table->string('ip_address', 45);
            $table->text('user_agent');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Index sesuai SQL
            $table->index('created_at', 'idx_activity_logs_created_at');
            $table->index('module', 'idx_activity_logs_module');
            $table->index(['subject_type', 'subject_id'], 'idx_activity_logs_subject');
            $table->index('user_id', 'idx_activity_logs_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
