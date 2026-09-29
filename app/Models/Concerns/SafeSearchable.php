<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Log;
use Laravel\Scout\Searchable;

/**
 * Pengganti Laravel\Scout\Searchable yang tidak menggagalkan penyimpanan data
 * ketika mesin pencari (Meilisearch) sedang tidak bisa dihubungi.
 *
 * Data tetap tersimpan di database; error sinkronisasi hanya dicatat ke log.
 * Setelah Meilisearch kembali berjalan, sinkronkan ulang dengan:
 *   php artisan scout:import "App\Models\Campaign"
 *   php artisan scout:import "App\Models\Organization"
 */
trait SafeSearchable
{
    use Searchable {
        syncMakeSearchable as protected scoutSyncMakeSearchable;
        syncRemoveFromSearch as protected scoutSyncRemoveFromSearch;
    }

    public function syncMakeSearchable($models)
    {
        try {
            return $this->scoutSyncMakeSearchable($models);
        } catch (\Throwable $exception) {
            $this->reportSearchSyncFailure('index', $exception);
        }
    }

    public function syncRemoveFromSearch($models)
    {
        try {
            return $this->scoutSyncRemoveFromSearch($models);
        } catch (\Throwable $exception) {
            $this->reportSearchSyncFailure('hapus', $exception);
        }
    }

    private function reportSearchSyncFailure(string $operation, \Throwable $exception): void
    {
        Log::warning('Sinkronisasi pencarian (Scout) gagal, data tetap tersimpan di database.', [
            'model'     => static::class,
            'operation' => $operation,
            'message'   => $exception->getMessage(),
        ]);
    }
}
