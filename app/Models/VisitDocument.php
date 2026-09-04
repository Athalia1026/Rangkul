<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VisitDocument extends Model
{
    protected $table = 'visit_documents';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'id_kunjungan', 'lokasi_file', 'uploaded_at'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
            $model->uploaded_at = now();
        });
    }
}