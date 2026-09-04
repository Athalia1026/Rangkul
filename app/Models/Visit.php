<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Visit extends Model
{
    use HasFactory;

    protected $table = 'visits';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'id_organisasi', 'id_donatur', 'tanggal_kunjungan',
        'waktu_kunjungan', 'pengunjung', 'pesan_donatur',
        'pesan_organisasi', 'status', 'confirmed_at'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn ($model) => $model->id = (string) Str::uuid());
    }

    public function documents()
    {
        return $this->hasMany(VisitDocument::class, 'id_kunjungan');
    }
}