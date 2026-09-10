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
            $adminAccounts = [
                [
                    'email' => 'superadmin@rangkul.com',
                    'nama' => 'Super Admin',
                    'tipe' => 'super admin',
                    'password' => 'password123',
                ],
                [
                    'email' => 'manager@rangkul.com',
                    'nama' => 'Manager Rangkul',
                    'tipe' => 'manager',
                    'password' => 'password123',
                ],
                [
                    'email' => 'staff@rangkul.com',
                    'nama' => 'Staff Rangkul',
                    'tipe' => 'staff',
                    'password' => 'password123',
                ],
            ];

            foreach ($adminAccounts as $adminAccount) {
                $user = User::updateOrCreate(
                    ['email' => $adminAccount['email']],
                    [
                        'nama' => $adminAccount['nama'],
                        'password' => Hash::make($adminAccount['password']),
                        'account_type' => 'admin',
                        'status' => 'aktif',
                    ]
                );

                Admin::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'tipe' => $adminAccount['tipe'],
                        'status_akun' => 'aktif',
                    ]
                );
            }
        });
    }
}
