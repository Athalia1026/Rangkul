<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\BankAccount;

class FundDisbursement extends Model
{
    protected $table = 'fund_disbursements';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id', 'id_campaign', 'id_bank_account', 'alokasi_dana',
        'nominal_diajukan', 'alasan', 'lampiran_pendukung', 'status',
        'alasan_tolak', 'verified_by', 'verified_at', 'nominal_dicairkan',
        'transaction_id', 'transfer_method', 'transfer_status',
        'manual_transfer_proof', 'paid_at', 'transfer_note'
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

    public function campaign() { 
        return $this->belongsTo(Campaign::class, 'id_campaign', 'id'); 
    }
    public function bankAccount() {
        return $this->belongsTo(BankAccount::class, 'id_bank_account', 'id'); 
    }
    public function purchaseProofs() { 
        return $this->hasMany(PurchaseProof::class, 'id_pencairan', 'id'); 
    }
    public function verifier() { 
        return $this->belongsTo(Admin::class, 'verified_by', 'id'); 
    }
}