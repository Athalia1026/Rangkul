<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use LogsActivity;

    protected string $activityModule = 'campaign';

    use HasFactory, HasUuids, SoftDeletes, Concerns\SafeSearchable;

    protected $table = 'campaigns';

    protected $fillable = [
        'id_organisasi',
        'judul',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'target_dana',
        'id_categories',
        'status',
        'foto_cover',
        'alasan_tolak',
        'verified_by',
        'verified_at'
    ];
    protected $casts = [
        'verified_at' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'id_organisasi', 'id');
    }

    public function donations(): HasMany
    {
        // Replace 'campaign_id' or 'id_campaign' with your actual foreign key in the donations table
        return $this->hasMany(Donation::class, 'id_campaign');
    }

    protected $appends = ['sisa_hari'];
    public function sisaHari(): Attribute
    {
        return Attribute::make(
            get: function () {
                // 1. Cek jika kolom tanggal_selesai kosong / null
                if (!$this->tanggal_selesai) {
                    return 0;
                }

                // 2. Set acuan waktu ke awal hari (00:00:00) agar hitungan presisi
                $now = Carbon::now()->startOfDay();
                $endDate = Carbon::parse($this->tanggal_selesai)->startOfDay();

                // 3. Jika tanggal selesai sudah lewat atau hari ini, kembalikan 0
                if ($now->greaterThanOrEqualTo($endDate)) {
                    return 0;
                }

                // 4. Hitung selisih hari
                return (int) $now->diffInDays($endDate);
            }
        );
    }
    public function verifier()
    {
        return $this->belongsTo(Admin::class, 'verified_by', 'id');
    }
public function fundDisbursements()
    {
        return $this->hasMany(FundDisbursement::class, 'id_campaign', 'id');
    }

    /**
     * Tambahkan kolom total_terkumpul (donasi sudah_bayar) dan total_dicairkan
     * (pencairan yang disetujui admin) pada query campaign.
     */
    public function scopeWithFinancialSummary($query)
    {
        return $query
            ->withSum(['donations as total_terkumpul' => fn ($q) => $q->where('status', 'sudah_bayar')], 'nominal')
            ->withSum(['fundDisbursements as total_dicairkan' => fn ($q) => $q->where('status', 'diterima')], 'nominal_dicairkan');
    }

    /** Persentase dana terkumpul terhadap target (0-100). Butuh scope withFinancialSummary. */
    public function progressPercent(): float
    {
        $target = (float) $this->target_dana;

        return $target > 0 ? min(100, round(((float) $this->total_terkumpul / $target) * 100, 1)) : 0;
    }

    /** Saldo campaign yang belum dicairkan. Butuh scope withFinancialSummary. */
    public function saldoTersisa(): float
    {
        return max(0, (float) $this->total_terkumpul - (float) $this->total_dicairkan);
    }

    public function toSearchableArray(): array
{
    return [
        'id' => $this->id,
        'judul' => $this->judul,
        'deskripsi' => $this->deskripsi,
        'status' => $this->status,
        'nama_organisasi' => $this->organization?->nama_lembaga,
    ];
}

public function shouldBeSearchable(): bool
{
    return $this->status === 'aktif';
}
}
