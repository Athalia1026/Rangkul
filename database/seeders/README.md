# Data demo lokal Rangkul

Gunakan database MySQL lokal `rangkul` dan `APP_ENV=local` di `.env`.
Sesuaikan `DB_HOST`, `DB_PORT`, `DB_USERNAME`, dan `DB_PASSWORD` dengan MySQL lokal.

```sh
php artisan migrate
php artisan db:seed
php artisan storage:link
```

`DatabaseSeeder` menjalankan kategori dan akun admin yang sudah tersedia, lalu
`DonorDemoSeeder` hanya pada lingkungan `local` atau `testing`.
Seeder demo dapat dijalankan sendiri dengan `php artisan db:seed --class=DonorDemoSeeder`.

Data demo: 6 kategori, 3 donatur, 3 organisasi terverifikasi, 9 kampanye aktif,
36 donasi (lunas, belum dibayar, gagal), 6 kunjungan, dan 3 gambar galeri.
Setiap organisasi juga memiliki rekening bank dan 4 dokumen pendaftaran (SK, KTP, foto bangunan, foto kegiatan)
yang sudah berstatus `diterima`, sehingga alur pencairan dana bisa langsung diuji.
Setiap kampanye memiliki foto cover sesuai temanya dari `public/images/demo-campaigns`
(foto [Unsplash](https://unsplash.com/license), bebas dipakai tanpa atribusi), disalin ke `storage/app/public/demo/campaigns`.
Gambar disalin dari aset repository ke `storage/app/public/demo`; dokumen pendaftaran ke disk privat
`storage/app/documents/{sk,ktp,bangunan,kegiatan}/demo-*`.
Tanggal kampanye dihitung relatif terhadap tanggal seeding agar beranda selalu berisi data aktif.
Tidak ada transaksi pembayaran atau pengiriman email sungguhan.

| Peran | Email | Password lokal |
| --- | --- | --- |
| Donatur | donatur@rangkul.test | password123 |
| Komunitas | komunitas@rangkul.test | password123 |
| Perusahaan | perusahaan@rangkul.test | password123 |
| Organisasi | organisasi1@rangkul.test | password123 |
| Organisasi | organisasi2@rangkul.test | password123 |
| Organisasi | organisasi3@rangkul.test | password123 |
| Super Admin / Manager / Staff (`AdminUserSeeder`) | superadmin@ / manager@ / staff@rangkul.com | `ADMIN_SEED_PASSWORD`, atau password123 jika kosong |

`AdminUserSeeder` memakai `ADMIN_SEED_PASSWORD` dari `.env`. Default `password123` hanya berlaku di
`local`/`testing`; di lingkungan lain seeder berhenti jika `ADMIN_SEED_PASSWORD` kosong atau kurang dari
12 karakter, dan tidak menimpa password akun admin yang sudah ada.

Menjalankan ulang seeder memperbarui data demo dan password akun contoh tanpa
menggandakan baris atau mengganti ID kategori yang telah direferensikan kampanye.
Seeder tidak menghapus data lain. Hindari `migrate:fresh` jika ingin mempertahankan data lokal.

Halaman pemeriksaan: `/beranda`, `/search`, `/search-results?q=pangan`,
`/campaign/demo-campaign-1`, dan `/campaign/demo-campaign-1/prayers`.
Login mengarahkan donatur ke `/donatur/beranda`, organisasi ke `/organisasi/dashboard`,
dan admin ke `/manager/home`.
