<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class OrganizationDocument extends Model
{
    use LogsActivity;

    protected string $activityModule = 'organization_document';

    use HasFactory, HasUuids;
    protected $fillable = [
        'id_organisasi',
        'nama_file',
        'lokasi_file',
        'status',
        'alasan_penolakan',
        'verified_at',
        'verified_by',
        'uploaded_at',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
