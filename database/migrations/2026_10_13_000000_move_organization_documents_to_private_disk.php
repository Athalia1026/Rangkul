<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Dokumen pendaftaran organisasi (KTP, SK, foto) dulu disimpan di disk 'public' sehingga bisa dibuka
 * siapa saja lewat /storage/... Pindahkan ke disk privat 'local'; aksesnya kini lewat link bertanda tangan.
 * Path di kolom lokasi_file tetap sama, hanya lokasi fisik filenya yang berpindah.
 */
return new class extends Migration
{
    public function up(): void
    {
        $public = Storage::disk('public');
        $private = Storage::disk('local');

        $this->moveDocuments($public, $private);

        // File lama yang tidak lagi tercatat (mis. sisa pengajuan ulang) juga berisi KTP/SK: ikut dipindahkan.
        foreach ($public->allFiles('documents') as $path) {
            $this->move($public, $private, $path);
        }
    }

    public function down(): void
    {
        $this->moveDocuments(Storage::disk('local'), Storage::disk('public'));
    }

    private function moveDocuments($from, $to): void
    {
        DB::table('organization_documents')->pluck('lokasi_file')
            ->each(fn (string $path) => $this->move($from, $to, $path));
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
