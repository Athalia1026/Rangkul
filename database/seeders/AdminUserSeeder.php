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
        $isLocal = app()->environment('local', 'testing');
        $password = $this->resolvePassword($isLocal);

        DB::transaction(function () use ($isLocal, $password) {
            $adminAccounts = [
                [
                    'email' => 'superadmin@rangkul.com',
                    'nama' => 'Super Admin',
                    'tipe' => 'super admin',
                ],
                [
                    'email' => 'manager@rangkul.com',
                    'nama' => 'Manager Rangkul',
                    'tipe' => 'manager',
                ],
                [
                    'email' => 'staff@rangkul.com',
                    'nama' => 'Staff Rangkul',
                    'tipe' => 'staff',
                ],
            ];

            foreach ($adminAccounts as $adminAccount) {
                $user = User::firstOrNew(['email' => $adminAccount['email']]);
                $user->fill([
                    'nama' => $adminAccount['nama'],
                    'account_type' => 'admin',
                    'status' => 'aktif',
                ]);

                // Di luar local/testing, password akun admin yang sudah ada tidak ditimpa saat seeder dijalankan ulang.
                if (!$user->exists || $isLocal) {
                    $user->password = Hash::make($password);
                }

                $user->save();

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

    /**
     * Password awal akun admin diambil dari ADMIN_SEED_PASSWORD. Default 'password123'
     * hanya diizinkan di local/testing agar akun admin tidak pernah dibuat dengan password publik.
     */
    private function resolvePassword(bool $isLocal): string
    {
        $password = (string) env('ADMIN_SEED_PASSWORD', '');

        if ($password !== '') {
            if (!$isLocal && strlen($password) < 12) {
                throw new \RuntimeException('ADMIN_SEED_PASSWORD minimal 12 karakter di luar lingkungan local/testing.');
            }

            return $password;
        }

        if ($isLocal) {
            return 'password123';
        }

        throw new \RuntimeException('Set ADMIN_SEED_PASSWORD di .env sebelum menjalankan AdminUserSeeder di luar lingkungan local/testing.');
    }
}
