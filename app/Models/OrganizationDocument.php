<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationDocument extends Model
{
    protected $fillable = [
        'id_organisasi',
        'lokasi_file',
        'nama_file',
        'status',
        'alasan_penolakan',
        'verified_at',
        'verified_by',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
