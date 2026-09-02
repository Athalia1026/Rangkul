<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'campaigns';

    protected $fillable = [
        'id_organisasi', 'judul', 'deskripsi', 
        'tanggal_mulai', 'tanggal_selesai', 'target_dana', 
        'id_categories', 'status', 'foto_cover', 
        'alasan_tolak', 'verified_by', 'verified_at'
    ];
    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'id_organisasi', 'id');
    }

    public function verifier()
    {
        return $this->belongsTo(Admin::class, 'verified_by', 'id');
    }
}
