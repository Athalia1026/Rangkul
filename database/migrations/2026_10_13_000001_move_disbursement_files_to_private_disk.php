<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Lampiran pengajuan pencairan & bukti transfer manual dulu disimpan di disk 'public' sehingga bisa dibuka
 * siapa saja lewat /storage/... Pindahkan ke disk privat 'local'; aksesnya kini lewat link bertanda tangan.
 * Path di database tetap sama, hanya lokasi fisik filenya yang berpindah.
 */
return new class extends Migration
{
    private const FOLDERS = ['disbursements/lampiran', 'disbursements/manual-transfer'];

    public function up(): void
    {
        $public = Storage::disk('public');
        $private = Storage::disk('local');

        $this->paths()->each(fn (string $path) => $this->move($public, $private, $path));

        // File lama yang tidak lagi tercatat juga berisi dokumen keuangan: ikut dipindahkan.
        foreach (self::FOLDERS as $folder) {
            foreach ($public->allFiles($folder) as $path) {
                $this->move($public, $private, $path);
            }
        }
    }

    public function down(): void
    {
        $this->paths()->each(fn (string $path) => $this->move(Storage::disk('local'), Storage::disk('public'), $path));
    }

    private function paths()
    {
        return DB::table('fund_disbursements')->pluck('lampiran_pendukung')
            ->merge(DB::table('fund_disbursements')->pluck('manual_transfer_proof'))
            ->filter();
    }

    private function move($from, $to, string $path): void
    {
        if (!$from->exists($path)) {
            return;
        }

        if (!$to->exists($path)) {
            $to->writeStream($path, $from->readStream($path));
        }

        $from->delete($path);
    }
};
