<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Organization;

class OrganizationGallery extends Model
{
    use HasUuids; // Menggunakan UUID otomatis untuk primary key string 'id'

    protected $table = 'organization_galleries';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'organization_id',
        'file_path',
        'display_order',
    ];

    /**
     * Accessor untuk mendapatkan URL lengkap gambar
     */
    protected $appends = ['image_url'];

    public function getImageUrlAttribute(): ?string
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }
}
