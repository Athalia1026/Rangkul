<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Pangan & Sembako',
                'icon' => 'icons/categories/pangan.png',
            ],
            [
                'name' => 'Kesehatan & Medis',
                'icon' => 'icons/categories/kesehatan.png',
            ],
            [
                'name' => 'Beasiswa & Pendidikan',
                'icon' => 'icons/categories/pendidikan.png',
            ],
            [
                'name' => 'Sarana & Perbaikan Panti',
                'icon' => 'icons/categories/sarana.png',
            ],
            [
                'name' => 'Perlengkapan Sekolah',
                'icon' => 'icons/categories/sekolah.png',
            ],
            [
                'name' => 'Kegiatan & Pelatihan',
                'icon' => 'icons/categories/pelatihan.png',
            ],
        ];

        foreach ($categories as $category) {
            $existing = DB::table('categories')->where('name', $category['name'])->first();
            DB::table('categories')->updateOrInsert(
                ['name' => $category['name']],
                [
                    'id'         => $existing?->id ?? (string) Str::uuid(),
                    'icon'       => $category['icon'],
                    'created_at' => $existing?->created_at ?? now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
