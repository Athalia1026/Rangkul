<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // Wajib untuk UUID

class User extends Authenticatable
{
    use LogsActivity;

    protected string $activityModule = 'user';

    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    // Pastikan ID tidak auto-increment dan bertipe string
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'account_type',
        'status',
        'profile_photo'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    /** URL foto profil (path storage atau URL penuh), null jika belum ada foto. */
    public function profilePhotoUrl(): ?string
    {
        if (!$this->profile_photo) {
            return null;
        }

        return filter_var($this->profile_photo, FILTER_VALIDATE_URL)
            ? $this->profile_photo
            : asset('storage/' . ltrim($this->profile_photo, '/'));
    }

    // withTrashed: profil admin yang dihapus tetap terbaca agar status 'dihapus' ikut dicek CheckIsAdmin.
    public function adminProfile()
    {
        return $this->hasOne(Admin::class, 'user_id')->withTrashed();
    }

    public function donor(): HasOne
    {
        return $this->hasOne(Donor::class, 'user_id', 'id');
    }

    public function organization(): HasOne
    {
        return $this->hasOne(Organization::class, 'user_id', 'id');
    }

    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class, 'user_id', 'id')->withTrashed();
    }

    public function companyPremium(): HasOneThrough
    {
        return $this->hasOneThrough(
            CompanyPremium::class,
            Donor::class,
            'user_id',
            'id_donatur',
            'id',
            'id'
        );
    }
}
