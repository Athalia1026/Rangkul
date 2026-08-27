<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // Wajib untuk UUID

class User extends Authenticatable
{
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
    ];

    protected $hidden = [
        'password',
    ];
}
