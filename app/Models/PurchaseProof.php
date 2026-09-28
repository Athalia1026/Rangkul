<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PurchaseProof extends Model
{
    protected $table = 'purchase_proofs';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id', 'id_pencairan', 'lokasi_file', 'nominal', 
        'deskripsi', 'status', 'alasan_tolak', 'uploaded_at'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function fundDisbursement() { 
        return $this->belongsTo(FundDisbursement::class, 'id_pencairan', 'id'); 
    }
    public function proofVerification() { 
        return $this->hasOne(ProofVerification::class, 'id_bukti', 'id'); 
    }
}