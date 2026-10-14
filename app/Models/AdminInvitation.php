<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\URL;

/**
 * Link undangan pendaftaran staf / manajer. Role ditentukan Super Admin saat membuat link dan
 * disimpan di sini, sehingga tidak bisa diubah lewat URL maupun isian formulir pendaftaran.
 */
class AdminInvitation extends Model
{
    use HasUuids;

    // Link undangan berlaku 48 jam dan hanya bisa dipakai sekali.
    public const VALID_HOURS = 48;

    public const ROLE_LABELS = [
        Admin::TIPE_STAFF => 'Staff',
        Admin::TIPE_MANAGER => 'Manager',
    ];

    protected $fillable = [
        'role',
        'email',
        'created_by',
        'expires_at',
        'used_at',
        'used_by',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isUsable(): bool
    {
        return !$this->isUsed() && !$this->isExpired();
    }

    public function roleLabel(): string
    {
        return self::ROLE_LABELS[$this->role] ?? ucfirst($this->role);
    }

    /**
     * Link pendaftaran bertanda tangan. Masa berlakunya sama dengan undangan, sehingga link yang
     * sama persis bisa dibuat ulang (misalnya saat dikirim ke email) tanpa menyimpan token rahasia.
     */
    public function registrationUrl(): string
    {
        return URL::temporarySignedRoute('register.admin.form', $this->expires_at, ['invitation' => $this->id]);
    }
}
