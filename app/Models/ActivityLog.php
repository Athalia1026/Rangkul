<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasUuids;

    protected $table = 'activity_logs';
    public $incrementing = false;
    protected $keyType = 'string';

    // Tabel hanya memiliki created_at (tanpa updated_at)
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'action',
        'module',
        'subject_id',
        'subject_type',
        'description',
        'previous_data',
        'new_data',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'previous_data' => 'array',
        'new_data'      => 'array',
    ];

    /**
     * Audit trail bersifat append-only: log yang sudah tercatat tidak boleh diubah atau dihapus,
     * agar jejak tindakan tidak bisa dihilangkan. Database juga menolaknya lewat trigger
     * (migration 2026_10_21_000001_make_activity_logs_append_only).
     */
    protected static function booted(): void
    {
        static::updating(fn () => throw new \LogicException('Log aktivitas tidak boleh diubah.'));
        static::deleting(fn () => throw new \LogicException('Log aktivitas tidak boleh dihapus.'));
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
