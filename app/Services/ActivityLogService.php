<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Mencatat aktivitas user ke tabel activity_logs.
 *
 * - Perubahan data dicatat otomatis lewat trait App\Models\Concerns\LogsActivity.
 * - Aktivitas tanpa perubahan data (login, logout) dicatat manual lewat log().
 * Kegagalan mencatat hanya ditulis ke log aplikasi dan tidak menggagalkan proses utama.
 */
class ActivityLogService
{
    /** Kolom yang tidak boleh ikut tersimpan di previous_data / new_data. */
    private const SENSITIVE_KEYS = ['password', 'remember_token', 'token', 'snap_token'];

    private const ACTION_LABELS = [
        'create' => 'Membuat',
        'update' => 'Memperbarui',
        'delete' => 'Menghapus',
    ];

    public function log(
        string $action,
        string $module,
        string $description,
        ?Model $subject = null,
        ?array $previousData = null,
        ?array $newData = null,
        ?User $actor = null
    ): ?ActivityLog {
        $actor ??= Auth::user();

        // user_id wajib diisi: aktivitas sistem tanpa aktor (mis. callback Midtrans) tidak dicatat.
        if (!$actor) {
            return null;
        }

        try {
            $request = request();

            return ActivityLog::create([
                'user_id'       => $actor->getKey(),
                'action'        => $action,
                'module'        => $module,
                'subject_id'    => $subject?->getKey() ?? $actor->getKey(),
                'subject_type'  => $subject ? $subject::class : User::class,
                'description'   => $description,
                'previous_data' => $this->sanitize($previousData),
                'new_data'      => $this->sanitize($newData),
                'ip_address'    => $request?->ip() ?? '127.0.0.1',
                'user_agent'    => Str::limit((string) ($request?->userAgent() ?: (app()->runningInConsole() ? 'console' : 'unknown')), 1000, ''),
            ]);
        } catch (\Throwable $exception) {
            Log::error('Gagal mencatat activity log', [
                'action'  => $action,
                'module'  => $module,
                'message' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    /** Dipanggil trait LogsActivity pada event created / updated / deleted. */
    public function logModelEvent(string $action, Model $model): void
    {
        [$previous, $new] = match ($action) {
            'create' => [null, $model->getAttributes()],
            'update' => $this->changedAttributes($model),
            'delete' => [$model->getAttributes(), null],
        };

        // Update yang hanya menyentuh timestamp / kolom sensitif (mis. remember_token) tidak perlu dicatat.
        if ($action === 'update' && empty($this->sanitize($new))) {
            return;
        }

        $module = method_exists($model, 'activityModule')
            ? $model->activityModule()
            : Str::snake(class_basename($model));

        $label = $this->subjectLabel($model);

        $this->log(
            $action,
            $module,
            self::ACTION_LABELS[$action] . ' ' . str_replace('_', ' ', $module) . ($label ? " \"{$label}\"" : ''),
            $model,
            $previous,
            $new
        );
    }

    private function changedAttributes(Model $model): array
    {
        $changes = collect($model->getChanges())->except(['updated_at', 'created_at']);

        $previous = $changes->keys()
            ->mapWithKeys(fn ($key) => [$key => $model->getOriginal($key)])
            ->all();

        return [$previous, $changes->all()];
    }

    private function subjectLabel(Model $model): ?string
    {
        foreach (['judul', 'nama_lembaga', 'nama', 'alokasi_dana', 'nama_file', 'email'] as $attribute) {
            if (filled($model->getAttribute($attribute))) {
                return Str::limit((string) $model->getAttribute($attribute), 80);
            }
        }

        return null;
    }

    private function sanitize(?array $data): ?array
    {
        if ($data === null) {
            return null;
        }

        return collect($data)
            ->reject(fn ($value, $key) => in_array($key, self::SENSITIVE_KEYS, true))
            ->all();
    }
}
