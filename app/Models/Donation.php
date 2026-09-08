<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Donation extends Model
{
    protected $table = 'donations';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'id_campaign', 'id_donatur', 'nominal',
        'note', 'status', 'anonim', 'transaction_id', 'paid_at'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn ($model) => $model->id = (string) Str::uuid());
    }

    public function donor()
    {
        return $this->belongsTo(Donor::class, 'id_donatur');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'id_campaign');
    }
}
