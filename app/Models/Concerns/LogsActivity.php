<?php

namespace App\Models\Concerns;

use App\Services\ActivityLogService;

/**
 * Catat otomatis create / update / delete model ini ke activity_logs.
 * Nama modul default diambil dari nama tabel; override dengan properti $activityModule.
 */
trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(fn ($model) => app(ActivityLogService::class)->logModelEvent('create', $model));
        static::updated(fn ($model) => app(ActivityLogService::class)->logModelEvent('update', $model));
        static::deleted(fn ($model) => app(ActivityLogService::class)->logModelEvent('delete', $model));
    }

    public function activityModule(): string
    {
        return property_exists($this, 'activityModule') ? $this->activityModule : $this->getTable();
    }
}
