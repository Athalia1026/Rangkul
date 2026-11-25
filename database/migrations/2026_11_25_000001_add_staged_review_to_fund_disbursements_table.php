<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fund_disbursements', function (Blueprint $table) {
            $table->string('review_stage')->default('staff')->after('status');
            $table->string('staff_reviewed_by')->nullable()->after('verified_by');
            $table->timestamp('staff_reviewed_at')->nullable()->after('verified_at');
            $table->foreign('staff_reviewed_by')->references('id')->on('admins')->nullOnDelete();
        });

        DB::table('fund_disbursements')
            ->where('status', 'diterima')
            ->update(['review_stage' => 'completed']);

        DB::table('fund_disbursements')
            ->where('status', 'diterima')
            ->where('transfer_status', 'menunggu_transfer_manual')
            ->update(['review_stage' => 'transfer']);

        DB::table('fund_disbursements')
            ->where('status', 'ditolak')
            ->update(['review_stage' => 'rejected']);
    }

    public function down(): void
    {
        Schema::table('fund_disbursements', function (Blueprint $table) {
            $table->dropForeign(['staff_reviewed_by']);
            $table->dropColumn(['review_stage', 'staff_reviewed_by', 'staff_reviewed_at']);
        });
    }
};
