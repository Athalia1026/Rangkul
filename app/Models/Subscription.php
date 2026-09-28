<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasUuids;

    protected $table = 'subscriptions';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_perusahaan', 'status', 'started_at', 'expired_at', 'transaction_id', 'paid_at',
        'reminder_sent_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'expired_at' => 'datetime',
        'paid_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyPremium::class, 'id_perusahaan');
    }

    public function isActive(): bool
    {
        return $this->status === 'aktif' && $this->expired_at?->isFuture() === true;
    }
}