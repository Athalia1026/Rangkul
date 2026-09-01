<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasUuids, SoftDeletes;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'tipe',
        'nama_lembaga',
        'no_telp',
        'deskripsi',
        'kota',
        'alamat',
        'link_maps',
        'jumlah_anak',
        'tahun_berdiri',
        'verification_status',
        'verified_at',
        'alasan_penolakan',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function documents()
    {
        // Parameter ke-2 adalah Foreign Key ('id_organisasi') di tabel organization_documents
        return $this->hasMany(OrganizationDocument::class, 'id_organisasi', 'id');
    }
}
