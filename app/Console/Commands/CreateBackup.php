<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Phar;
use PharData;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;

/**
 * Backup database + file unggahan (storage/app) ke satu arsip .tar.gz.
 *
 * Isi arsip: database.sql (mysqldump), storage/... (dokumen, bukti, foto),
 * manifest.json (waktu, jumlah file, SHA-256 dump) untuk dicek backup:verify.
 * Arsip dikirim ke setiap disk di config('backup.disks'), lalu backup lama dibersihkan.
 */
class CreateBackup extends Command
{
    protected $signature = 'backup:create';

    protected $description = 'Backup database dan file unggahan ke disk backup';

    public function handle(): int
    {
        $disks = config('backup.disks');
        if (empty($disks)) {
            $this->error('BACKUP_DISKS kosong: tidak ada tujuan backup.');

            return self::FAILURE;
        }

        $stamp = now()->format('Y-m-d-His');
        $workDir = storage_path('framework/backup-tmp-' . $stamp);
        File::ensureDirectoryExists($workDir);

        try {
            $sqlPath = $workDir . DIRECTORY_SEPARATOR . 'database.sql';
            $this->dumpDatabase($sqlPath, $workDir);
            $archivePath = $this->buildArchive($workDir, $sqlPath, $stamp);
            $name = basename($archivePath);
            $size = filesize($archivePath);

            $failed = [];
            foreach ($disks as $disk) {
                try {
                    $stream = fopen($archivePath, 'r');
                    Storage::disk($disk)->writeStream($name, $stream);
                    $this->pruneOldBackups($disk);
                    $this->info("Backup tersimpan di disk [{$disk}]: {$name}");
                } catch (\Throwable $exception) {
                    $failed[] = $disk;
                    Log::error('Backup gagal dikirim ke disk', ['disk' => $disk, 'message' => $exception->getMessage()]);
                    $this->error("Gagal menyimpan ke disk [{$disk}]: {$exception->getMessage()}");
                } finally {
                    if (isset($stream) && is_resource($stream)) {
                        fclose($stream);
                    }
                }
            }

            if ($failed) {
                return self::FAILURE;
            }

            Log::info('Backup selesai', ['file' => $name, 'bytes' => $size, 'disks' => $disks]);
            $this->info(sprintf('Selesai (%s MB).', number_format($size / 1048576, 2)));

            return self::SUCCESS;
        } catch (\Throwable $exception) {
            Log::error('Backup gagal', ['message' => $exception->getMessage()]);
            $this->error('Backup gagal: ' . $exception->getMessage());

            return self::FAILURE;
        } finally {
            File::deleteDirectory($workDir);
        }
    }

    private function dumpDatabase(string $sqlPath, string $workDir): void
    {
        $connection = config('database.connections.' . config('database.default'));
        if (($connection['driver'] ?? null) !== 'mysql') {
            throw new RuntimeException('Backup database hanya mendukung MySQL.');
        }

        // Password lewat file opsi sementara, bukan argumen, agar tidak terlihat di daftar proses.
        $optionFile = $workDir . DIRECTORY_SEPARATOR . 'client.cnf';
        File::put($optionFile, "[client]\npassword=\"" . addcslashes((string) $connection['password'], "\\\"") . "\"\n");

        $binaryDir = rtrim((string) config('backup.dump_binary_path'), '/\\');
        $command = [
            $binaryDir !== '' ? $binaryDir . DIRECTORY_SEPARATOR . 'mysqldump' : 'mysqldump',
            '--defaults-extra-file=' . $optionFile,
            '--host=' . $connection['host'],
            '--port=' . $connection['port'],
            '--user=' . $connection['username'],
            '--single-transaction',
            '--quick',
            '--routines',
            '--triggers',
            '--no-tablespaces',
            '--default-character-set=utf8mb4',
            '--result-file=' . $sqlPath,
        ];
        if (!empty($connection['unix_socket'])) {
            $command[] = '--socket=' . $connection['unix_socket'];
        }
        $command[] = $connection['database'];

        $result = Process::timeout(900)->run($command);
        File::delete($optionFile);

        if ($result->failed()) {
            throw new RuntimeException('mysqldump gagal: ' . trim($result->errorOutput()));
        }

        // mysqldump menulis baris "-- Dump completed" di akhir; tanpa baris itu dump terpotong.
        $tail = $this->tail($sqlPath, 200);
        if (!str_contains($tail, '-- Dump completed')) {
            throw new RuntimeException('Dump database tidak lengkap.');
        }
    }

    private function buildArchive(string $workDir, string $sqlPath, string $stamp): string
    {
        $tarPath = $workDir . DIRECTORY_SEPARATOR . config('backup.filename_prefix') . $stamp . '.tar';
        $archive = new PharData($tarPath);
        $archive->addFile($sqlPath, 'database.sql');

        $root = rtrim(config('backup.storage_root'), '/\\');
        $fileCount = 0;
        if (is_dir($root)) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, RecursiveDirectoryIterator::SKIP_DOTS));
            foreach ($files as $file) {
                if (!$file->isFile()) {
                    continue;
                }
                $relative = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));
                $archive->addFile($file->getPathname(), 'storage/' . $relative);
                $fileCount++;
            }
        }

        $archive->addFromString('manifest.json', json_encode([
            'created_at' => now()->toIso8601String(),
            'app_url' => config('app.url'),
            'database' => config('database.connections.' . config('database.default') . '.database'),
            'database_sha256' => hash_file('sha256', $sqlPath),
            'storage_files' => $fileCount,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $archive->compress(Phar::GZ);
        // Lepaskan handle sebelum menghapus .tar (Windows mengunci file yang masih terbuka).
        unset($archive);
        Phar::unlinkArchive($tarPath);

        return $tarPath . '.gz';
    }

    private function pruneOldBackups(string $disk): void
    {
        $prefix = config('backup.filename_prefix');
        $cutoff = now()->subDays(config('backup.keep_days'));

        $backups = collect(Storage::disk($disk)->files())
            ->filter(fn (string $path) => str_starts_with(basename($path), $prefix))
            ->sortDesc()
            ->values();

        // Backup terbaru selalu disimpan, berapa pun umurnya.
        $backups->slice(1)->each(function (string $path) use ($disk, $prefix, $cutoff) {
            $stamp = substr(basename($path), strlen($prefix), 17);
            $createdAt = rescue(fn () => Carbon::createFromFormat('Y-m-d-His', $stamp), null, false);
            if ($createdAt && $createdAt->lt($cutoff)) {
                Storage::disk($disk)->delete($path);
                $this->line("Backup lama dihapus dari [{$disk}]: " . basename($path));
            }
        });
    }

    private function tail(string $path, int $bytes): string
    {
        $handle = fopen($path, 'r');
        fseek($handle, -min($bytes, filesize($path)), SEEK_END);
        $content = stream_get_contents($handle);
        fclose($handle);

        return (string) $content;
    }
}
