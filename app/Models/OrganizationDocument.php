<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\URL;

class OrganizationDocument extends Model
{
    use LogsActivity;

    protected string $activityModule = 'organization_document';

    use HasFactory, HasUuids;

    // KTP, SK, dan foto pendaftaran bersifat privat: disimpan di luar folder public/storage.
    public const DISK = 'local';

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

    /** Link sementara (30 menit) untuk membuka dokumen privat ini. */
    public function temporaryUrl(): string
    {
        return URL::temporarySignedRoute('organization.document', now()->addMinutes(30), ['document' => $this->id]);
    }
}
