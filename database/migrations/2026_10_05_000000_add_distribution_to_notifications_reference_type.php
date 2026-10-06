<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// Notifikasi "donasi sudah disalurkan" untuk donatur memakai reference_type 'distribution'
// dengan reference_id = id donasi.
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE notifications MODIFY reference_type ENUM('donation', 'campaign', 'visit', 'fund_disbursement', 'purchase_proof', 'organization', 'subscription', 'report', 'distribution') NOT NULL");
    }

    public function down(): void
    {
        DB::table('notifications')->where('reference_type', 'distribution')->delete();
        DB::statement("ALTER TABLE notifications MODIFY reference_type ENUM('donation', 'campaign', 'visit', 'fund_disbursement', 'purchase_proof', 'organization', 'subscription', 'report') NOT NULL");
    }
};
