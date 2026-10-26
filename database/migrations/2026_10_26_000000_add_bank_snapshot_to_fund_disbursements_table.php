<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Salinan rekening tujuan saat pencairan disetujui. Baris bank_accounts bisa berubah lagi
 * (mis. lewat pengajuan ulang pendaftaran), sedangkan transfer harus ke rekening yang disetujui.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fund_disbursements', function (Blueprint $table) {
            $table->string('rekening_bank')->nullable()->after('id_bank_account');
            $table->string('rekening_nomor')->nullable()->after('rekening_bank');
            $table->string('rekening_pemilik')->nullable()->after('rekening_nomor');
        });

        // Pencairan yang sudah disetujui sebelum kolom ini ada: salin rekening yang tercatat saat ini.
        DB::table('fund_disbursements')
            ->join('bank_accounts', 'bank_accounts.id', '=', 'fund_disbursements.id_bank_account')
            ->where('fund_disbursements.status', 'diterima')
            ->whereNull('fund_disbursements.rekening_nomor')
            ->update([
                'fund_disbursements.rekening_bank' => DB::raw('bank_accounts.bank'),
                'fund_disbursements.rekening_nomor' => DB::raw('bank_accounts.no_rekening'),
                'fund_disbursements.rekening_pemilik' => DB::raw('bank_accounts.pemilik_rekening'),
            ]);
    }

    public function down(): void
    {
        Schema::table('fund_disbursements', function (Blueprint $table) {
            $table->dropColumn(['rekening_bank', 'rekening_nomor', 'rekening_pemilik']);
        });
    }
};
