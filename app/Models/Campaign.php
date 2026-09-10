<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use App\Models\Organization;

class Campaign extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

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
}
