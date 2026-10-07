<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\BankAccount;

class FundDisbursement extends Model
{
    use LogsActivity;

    protected string $activityModule = 'fund_disbursement';

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

    /**
     * Pencairan milik organisasi yang menghalangi pengajuan pencairan baru:
     * - pengajuannya masih menunggu verifikasi admin, atau
     * - sudah disetujui tetapi bukti penyalurannya belum diverifikasi admin, yaitu belum ada bukti
     *   yang diterima atau masih ada bukti yang menunggu verifikasi. Bukti yang ditolak harus diganti
     *   dengan unggahan baru yang kemudian diterima admin.
     */
    public static function blockingNewRequestFor(string $organizationId): ?self
    {
        return static::with(['campaign', 'purchaseProofs'])
            ->whereHas('campaign', fn ($query) => $query->where('id_organisasi', $organizationId))
            ->where(function ($query) {
                $query->where('status', 'menunggu')
                    ->orWhere(fn ($accepted) => $accepted
                        ->where('status', 'diterima')
                        ->where(fn ($proofs) => $proofs
                            ->whereDoesntHave('purchaseProofs', fn ($proof) => $proof->where('status', 'diterima'))
                            ->orWhereHas('purchaseProofs', fn ($proof) => $proof->where('status', 'menunggu'))));
            })
            ->oldest()
            ->first();
    }

    /**
     * Alasan pencairan ini menghalangi pengajuan baru:
     * pengajuan_menunggu, bukti_menunggu_verifikasi, atau belum_upload_bukti.
     */
    public function newRequestBlockReason(): string
    {
        if ($this->status === 'menunggu') {
            return 'pengajuan_menunggu';
        }

        return $this->purchaseProofs->contains('status', 'menunggu')
            ? 'bukti_menunggu_verifikasi'
            : 'belum_upload_bukti';
    }

    /** Pesan untuk organisasi mengapa pengajuan pencairan baru belum bisa dibuat. */
    public function newRequestBlockMessage(): string
    {
        $campaign = $this->campaign?->judul ? ' pada kampanye "' . $this->campaign->judul . '"' : '';

        return match ($this->newRequestBlockReason()) {
            'pengajuan_menunggu' => 'Anda masih memiliki pengajuan pencairan dana' . $campaign . ' yang menunggu verifikasi admin. Tunggu hingga pengajuan tersebut diproses sebelum mengajukan pencairan baru.',
            'bukti_menunggu_verifikasi' => 'Bukti penyaluran untuk pencairan dana' . $campaign . ' masih menunggu verifikasi admin. Pencairan baru dapat diajukan setelah bukti tersebut diverifikasi.',
            default => 'Anda belum mengunggah bukti penyaluran yang disetujui admin untuk pencairan dana' . $campaign . '. Unggah bukti penyaluran dan tunggu verifikasi admin sebelum mengajukan pencairan dana baru.',
        };
    }
}