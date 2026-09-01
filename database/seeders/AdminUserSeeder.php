<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // 1. Insert/Update Data di Tabel Users
            $user = User::updateOrCreate(
                ['email' => 'admin@rangkul.com'],
                [
                    'nama'         => 'Super Admin',
                    'password'     => Hash::make('admin123'),
                    'account_type' => 'admin',
                    'status'       => 'aktif',
                ]
            );

            // 2. Insert/Update Data di Tabel Admins
            Admin::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'tipe'        => 'super admin',  // Opsi: 'admin', 'manager', 'staff'
                    'status_akun' => 'aktif',  // Opsi: 'aktif', 'nonaktif'
                ]
            );
        });
    }
}
