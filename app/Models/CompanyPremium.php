<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyPremium extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'companies_premium';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_donatur', 'nama_pic', 'jabatan', 'nomor_pic', 'email_korporat', 'NPWP',
    ];

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class, 'id_donatur');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'id_perusahaan');
    }

    public function activeSubscription(): HasMany
    {
        return $this->subscriptions()
            ->where('status', 'aktif')
            ->where('expired_at', '>', now())
            ->latest('expired_at');
    }
}