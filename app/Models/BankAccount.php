<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BankAccount extends Model
{
    // Asumsi nama tabel di database Anda adalah bank_accounts
    protected $table = 'bank_accounts'; 
    
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id', 
        'id_organisasi', 
        'bank', 
        'no_rekening', 
        'pemilik_rekening', 
        'status_verifikasi'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
            // Set default status ke menunggu saat registrasi
            if (empty($model->status_verifikasi)) {
                $model->status_verifikasi = 'menunggu';
            }
        });
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'id_organisasi', 'user_id');
    }

    public function fundDisbursements()
    {
        return $this->hasMany(FundDisbursement::class, 'id_bank_account', 'id');
    }
}