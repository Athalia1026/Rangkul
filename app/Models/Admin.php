<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Model
{
    use HasUuids, SoftDeletes;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'tipe',
        'status_akun',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Melacak pencairan dana awal yang diverifikasi admin ini
    public function verifiedDisbursements()
    {
        return $this->hasMany(FundDisbursement::class, 'verified_by', 'id');
    }

    // Melacak verifikasi bukti sebagai Staff
    public function staffProofVerifications()
    {
        return $this->hasMany(ProofVerification::class, 'staff_id', 'id');
    }

    // Melacak verifikasi bukti sebagai Manager
    public function managerProofVerifications()
    {
        return $this->hasMany(ProofVerification::class, 'manager_id', 'id');
    }
}
