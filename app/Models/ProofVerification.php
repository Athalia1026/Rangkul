<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProofVerification extends Model
{
    use LogsActivity;

    protected string $activityModule = 'proof_verification';

    protected $table = 'proof_verifications';
    protected $keyType = 'string';
    public $incrementing = false;
    
    // Matikan timestamps otomatis karena tabel ini (merujuk pada desain) 
    // hanya memiliki verified_at, tanpa created_at & updated_at standar
    public $timestamps = false; 

    protected $fillable = [
        'id', 'id_bukti', 'staff_id', 'manager_id', 
        'status', 'catatan', 'verified_at'
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

    public function purchaseProof() { 
        return $this->belongsTo(PurchaseProof::class, 'id_bukti', 'id'); 
    }
    public function staff() { 
        return $this->belongsTo(Admin::class, 'staff_id', 'id')->withTrashed(); 
    }
    public function manager() { 
        return $this->belongsTo(Admin::class, 'manager_id', 'id')->withTrashed(); 
    }
}