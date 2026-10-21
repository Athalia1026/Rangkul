<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Audit trail append-only di level database:
 * - trigger menolak UPDATE dan DELETE pada activity_logs (juga dari query mentah di luar Eloquent);
 * - FK user_id diubah dari CASCADE ke RESTRICT, karena penghapusan cascade tidak memicu trigger
 *   sehingga log bisa ikut terhapus bersama user. User tidak pernah dihapus permanen (cukup status 'dihapus').
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
        });

        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::unprepared('DROP TRIGGER IF EXISTS activity_logs_block_update');
        DB::unprepared('DROP TRIGGER IF EXISTS activity_logs_block_delete');
        DB::unprepared("CREATE TRIGGER activity_logs_block_update BEFORE UPDATE ON activity_logs FOR EACH ROW
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'activity_logs bersifat append-only: log tidak boleh diubah'");
        DB::unprepared("CREATE TRIGGER activity_logs_block_delete BEFORE DELETE ON activity_logs FOR EACH ROW
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'activity_logs bersifat append-only: log tidak boleh dihapus'");
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::unprepared('DROP TRIGGER IF EXISTS activity_logs_block_update');
            DB::unprepared('DROP TRIGGER IF EXISTS activity_logs_block_delete');
        }

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
