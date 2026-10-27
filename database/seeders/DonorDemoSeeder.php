<?php

namespace Database\Seeders;

use App\Models\OrganizationDocument;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DonorDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment('local', 'testing')) {
            throw new \RuntimeException('Data demo hanya untuk lingkungan local/testing.');
        }

        // Use repository images so demo pages do not depend on external image services.
        $images = ['hero/hero_children.jpg', 'hero/students_peace.jpg', 'register.png'];
        foreach ($images as $image) {
            Storage::disk('public')->put('demo/' . basename($image), file_get_contents(public_path('images/' . $image)));
        }

        // Registration documents live on the private disk; folder names match AuthService so admin pages can tell them apart.
        $documents = [
            'sk_operasional' => ['documents/sk', 'register.png'],
            'ktp_pj' => ['documents/ktp', 'logo.png'],
            'foto_bangunan' => ['documents/bangunan', 'hero/hero_children.jpg'],
            'foto_kegiatan' => ['documents/kegiatan', 'hero/students_peace.jpg'],
        ];
        foreach ($documents as $type => [$folder, $image]) {
            Storage::disk(OrganizationDocument::DISK)->put(
                $folder . '/demo-' . $type . '.' . pathinfo($image, PATHINFO_EXTENSION),
                file_get_contents(public_path('images/' . $image))
            );
        }

        // Query builder avoids sending dummy records to external Scout/payment services.
        DB::transaction(function () use ($images, $documents) {
            $this->call(CategorySeeder::class);
            $password = Hash::make('password123');
            $donors = [];
            foreach (['individu', 'komunitas', 'perusahaan'] as $index => $type) {
                $user = $this->record('users', ['email' => ['donatur@rangkul.test', 'komunitas@rangkul.test', 'perusahaan@rangkul.test'][$index]], [
                    'nama' => ['Nadia Donatur Demo', 'Komunitas Peduli Demo', 'Perusahaan Peduli Demo'][$index],
                    'password' => $password, 'account_type' => 'donatur', 'status' => 'aktif',
                ]);
                $donors[] = $this->record('donors', ['user_id' => $user], [
                    'tipe' => $type, 'no_telp' => '08000000000' . $index, 'kota' => 'Surabaya',
                ]);
            }

            $verifier = DB::table('admins')->where('tipe', 'super admin')->value('id');
            $organizations = [];
            foreach (['Panti Harapan Demo', 'Panti Pelita Demo', 'Panti Kasih Demo'] as $index => $name) {
                $user = $this->record('users', ['email' => 'organisasi' . ($index + 1) . '@rangkul.test'], [
                    'nama' => 'Pengurus ' . $name, 'password' => $password,
                    'account_type' => 'organisasi', 'status' => 'aktif',
                ]);
                $organization = $this->record('organizations', ['user_id' => $user], [
                    'tipe' => 'Panti Asuhan', 'nama_lembaga' => $name,
                    'no_telp' => '08000000010' . $index,
                    'deskripsi' => 'Data contoh untuk pengembangan Rangkul. Mendampingi pendidikan dan kebutuhan sehari-hari anak-anak panti.',
                    'kota' => ['Surabaya', 'Bandung', 'Yogyakarta'][$index],
                    'alamat' => 'Jalan Contoh No. ' . ($index + 1), 'jumlah_anak' => 25 + $index * 10,
                    'tahun_berdiri' => 2010 + $index, 'verification_status' => 'disetujui', 'verified_at' => now(),
                ]);
                $organizations[] = $organization;

                // Registration requires a bank account; 'diterima' lets the disbursement flow be tested.
                $this->record('bank_accounts', ['id_organisasi' => $organization], [
                    'bank' => ['BCA', 'BRI', 'Mandiri'][$index],
                    'no_rekening' => '900000000' . ($index + 1),
                    'pemilik_rekening' => 'Yayasan ' . $name,
                    'status_verifikasi' => 'diterima',
                ]);
                foreach ($documents as $type => [$folder, $image]) {
                    $this->record('organization_documents', ['id' => 'demo-document-' . ($index + 1) . '-' . $type], [
                        'id_organisasi' => $organization,
                        'nama_file' => $type . '-demo.' . pathinfo($image, PATHINFO_EXTENSION),
                        'lokasi_file' => $folder . '/demo-' . $type . '.' . pathinfo($image, PATHINFO_EXTENSION),
                        'status' => 'diterima', 'uploaded_at' => now()->subDays(30),
                        'verified_at' => now(), 'verified_by' => $verifier,
                    ]);
                }

                $this->record('organization_galleries', ['organization_id' => $organization, 'display_order' => 0], [
                    'file_path' => 'demo/' . basename($images[$index]),
                ]);
            }

            // Each campaign gets a cover matching its topic (public/images/demo-campaigns, Unsplash License).
            $campaigns = [
                ['Paket Pangan untuk Anak Panti', 'Pangan & Sembako', 7, 20, 'pangan.jpg'],
                ['Pemeriksaan Kesehatan Anak', 'Kesehatan & Medis', 10, 35, 'kesehatan.jpg'],
                ['Beasiswa untuk Masa Depan', 'Beasiswa & Pendidikan', 30, 60, 'beasiswa.jpg'],
                ['Perbaikan Ruang Belajar Panti', 'Sarana & Perbaikan Panti', 5, 15, 'ruang-belajar.jpg'],
                ['Tas dan Buku Sekolah Baru', 'Perlengkapan Sekolah', 12, 40, 'tas-buku.jpg'],
                ['Pelatihan Kreativitas Anak', 'Kegiatan & Pelatihan', 21, 70, 'kreativitas.jpg'],
                ['Susu dan Gizi Seimbang', 'Pangan & Sembako', 3, 10, 'susu.jpg'],
                ['Perlengkapan Kebersihan Panti', 'Kesehatan & Medis', 40, 25, 'kebersihan.jpg'],
                ['Seragam untuk Semangat Sekolah', 'Perlengkapan Sekolah', 9, 30, 'seragam.jpg'],
            ];
            foreach ($campaigns as [, , , , $cover]) {
                Storage::disk('public')->put('demo/campaigns/' . $cover, file_get_contents(public_path('images/demo-campaigns/' . $cover)));
            }
            foreach ($campaigns as $index => [$title, $category, $days, $progress, $cover]) {
                $organization = $organizations[$index % 3];
                $target = 10000000 + $index * 1000000;
                $campaign = $this->record('campaigns', ['id' => 'demo-campaign-' . ($index + 1)], [
                    'id_organisasi' => $organization, 'judul' => $title,
                    'deskripsi' => $title . '. Mari membantu anak-anak panti memperoleh kebutuhan yang layak. Ini adalah kampanye demo untuk pengujian lokal, bukan penggalangan dana sungguhan.',
                    'tanggal_mulai' => now()->subDays(14), 'tanggal_selesai' => now()->addDays($days)->endOfDay(),
                    'target_dana' => $target, 'id_categories' => DB::table('categories')->where('name', $category)->value('id'),
                    'status' => 'aktif', 'foto_cover' => 'demo/campaigns/' . $cover, 'verified_at' => now(),
                ]);
                foreach (['sudah_bayar', 'sudah_bayar', 'belum_bayar', 'gagal'] as $donationIndex => $status) {
                    $this->record('donations', ['id' => 'demo-donation-' . ($index + 1) . '-' . $donationIndex], [
                        'id_campaign' => $campaign, 'id_donatur' => $donors[$donationIndex % 3],
                        'nominal' => $status === 'sudah_bayar' ? $target * $progress / 200 : 100000,
                        'note' => $donationIndex === 0 ? 'Semoga anak-anak selalu sehat dan semangat belajar.' : 'Semoga bantuan ini bermanfaat.',
                        'status' => $status, 'anonim' => $donationIndex === 1,
                        'transaction_id' => 'DEMO-' . ($index + 1) . '-' . $donationIndex,
                        'paid_at' => $status === 'sudah_bayar' ? now()->subDays(2) : null,
                    ]);
                }
            }

            foreach (['terkirim', 'dikonfirmasi', 'ditolak', 'selesai', 'terkirim', 'dikonfirmasi'] as $index => $status) {
                $confirmed = in_array($status, ['dikonfirmasi', 'selesai']);
                $this->record('visits', ['id' => 'demo-visit-' . ($index + 1)], [
                    'id_organisasi' => $organizations[$index % 3], 'id_donatur' => $donors[$index % 3],
                    'tanggal_kunjungan' => $status === 'selesai' ? today()->subDays(3) : today()->addDays(7 + $index),
                    'waktu_kunjungan' => '10:00:00', 'pengunjung' => 5 + $index,
                    'pesan_donatur' => 'Kunjungan demo untuk kegiatan membaca bersama.',
                    'pesan_organisasi' => $confirmed ? 'Jadwal kunjungan telah dikonfirmasi.' : null,
                    'status' => $status, 'confirmed_at' => $confirmed ? now()->subDays(5) : null,
                ]);
            }
        });
    }

    private function record(string $table, array $key, array $values): string
    {
        $existing = DB::table($table)->where($key)->first();
        $id = $existing?->id ?? $key['id'] ?? (string) Str::uuid();
        DB::table($table)->updateOrInsert($key, array_merge($values, [
            'id' => $id, 'created_at' => $existing?->created_at ?? now(), 'updated_at' => now(),
        ]));

        return $id;
    }
}
