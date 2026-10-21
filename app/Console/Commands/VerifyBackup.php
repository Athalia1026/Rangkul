<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PharData;

/**
 * Memeriksa backup terbaru bisa dipakai untuk restore: arsip bisa dibuka,
 * dump database utuh (SHA-256 cocok dengan manifest, berisi tabel inti),
 * dan jumlah file unggahan sesuai manifest. Tidak mengubah database.
 */
class VerifyBackup extends Command
{
    protected $signature = 'backup:verify {--disk= : Disk yang diperiksa (default: disk backup pertama)}';

    protected $description = 'Periksa backup terbaru dapat dibuka dan isinya utuh';

    private const REQUIRED_TABLES = ['users', 'organizations', 'campaigns', 'donations', 'fund_disbursements', 'activity_logs'];

    public function handle(): int
    {
        $disk = $this->option('disk') ?: (config('backup.disks')[0] ?? null);
        if (!$disk) {
            $this->error('Tidak ada disk backup.');

            return self::FAILURE;
        }

        $prefix = config('backup.filename_prefix');
        $latest = collect(Storage::disk($disk)->files())
            ->filter(fn (string $path) => str_starts_with(basename($path), $prefix))
            ->sortDesc()
            ->first();

        if (!$latest) {
            return $this->fail_("Belum ada backup di disk [{$disk}].");
        }

        $workDir = storage_path('framework/backup-verify-' . now()->format('YmdHis'));
        File::ensureDirectoryExists($workDir);

        try {
            $localPath = $workDir . DIRECTORY_SEPARATOR . basename($latest);
            $source = Storage::disk($disk)->readStream($latest);
            $target = fopen($localPath, 'w');
            stream_copy_to_stream($source, $target);
            fclose($target);
            fclose($source);

            $archive = new PharData($localPath);
            if (!isset($archive['manifest.json'], $archive['database.sql'])) {
                return $this->fail_('Arsip tidak berisi manifest.json atau database.sql.');
            }

            $manifest = json_decode($archive['manifest.json']->getContent(), true);
            $archive->extractTo($workDir, 'database.sql');
            $sqlPath = $workDir . DIRECTORY_SEPARATOR . 'database.sql';

            if (!hash_equals((string) ($manifest['database_sha256'] ?? ''), hash_file('sha256', $sqlPath))) {
                return $this->fail_('Checksum database.sql tidak cocok dengan manifest (arsip rusak).');
            }

            $sql = File::get($sqlPath);
            $missing = array_filter(self::REQUIRED_TABLES, fn (string $table) => !str_contains($sql, "CREATE TABLE `{$table}`"));
            if ($missing) {
                return $this->fail_('Tabel tidak ditemukan di dump: ' . implode(', ', $missing));
            }
            if (!str_contains($sql, '-- Dump completed')) {
                return $this->fail_('Dump database terpotong.');
            }

            // Path entri berbentuk phar://<lokasi arsip>/<path di arsip>; yang dihitung hanya bagian setelah arsip.
            $archiveRoot = 'phar://' . str_replace('\\', '/', $localPath) . '/';
            $storageFiles = 0;
            foreach (new \RecursiveIteratorIterator($archive) as $file) {
                $inside = substr(str_replace('\\', '/', $file->getPathname()), strlen($archiveRoot));
                if (str_starts_with($inside, 'storage/')) {
                    $storageFiles++;
                }
            }
            if ($storageFiles !== (int) ($manifest['storage_files'] ?? -1)) {
                return $this->fail_("Jumlah file unggahan {$storageFiles}, manifest mencatat {$manifest['storage_files']}.");
            }

            unset($archive);
            $this->info("Backup valid: {$latest}");
            $this->line("  Dibuat      : {$manifest['created_at']}");
            $this->line("  Database    : {$manifest['database']} (" . number_format(filesize($sqlPath) / 1048576, 2) . ' MB dump)');
            $this->line("  File storage: {$storageFiles}");

            return self::SUCCESS;
        } catch (\Throwable $exception) {
            return $this->fail_('Arsip tidak bisa dibuka: ' . $exception->getMessage());
        } finally {
            File::deleteDirectory($workDir);
        }
    }

    private function fail_(string $message): int
    {
        Log::error('Verifikasi backup gagal', ['message' => $message]);
        $this->error($message);

        return self::FAILURE;
    }
}
