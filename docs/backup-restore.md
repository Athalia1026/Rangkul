# Backup dan Restore Rangkul

## Yang di-backup

`php artisan backup:create` membuat satu arsip `rangkul-backup-YYYY-MM-DD-HHMMSS.tar.gz` berisi:

| Isi | Keterangan |
|---|---|
| `database.sql` | Dump MySQL lengkap (`mysqldump --single-transaction`, termasuk trigger) |
| `storage/...` | Seluruh `storage/app`: dokumen organisasi (privat), bukti penyaluran, bukti transfer, foto |
| `manifest.json` | Waktu backup, nama database, SHA-256 `database.sql`, jumlah file storage |

Arsip disimpan ke setiap disk di `BACKUP_DISKS`:

- `backups`: `storage/backups` di server yang sama.
- `backups_offsite`: bucket S3-compatible (mis. Cloudflare R2), supaya backup selamat walau server rusak.

Backup yang lebih tua dari `BACKUP_KEEP_DAYS` (default 14 hari) dihapus otomatis. Backup terbaru selalu disimpan.

## Jadwal (bootstrap/app.php)

| Waktu | Perintah | Fungsi |
|---|---|---|
| Setiap hari 01:30 | `backup:create` | Membuat backup, ping `HEALTHCHECK_BACKUP_URL` saat sukses atau gagal |
| Setiap Senin 03:00 | `backup:verify` | Membuka backup terbaru, mencocokkan checksum, memastikan tabel inti dan jumlah file lengkap |

Kedua perintah juga bisa dijalankan manual. `backup:verify --disk=backups_offsite` memeriksa salinan di bucket.

## Restore

Contoh untuk server Linux. Ganti nama database, user, dan path sesuai server.

```bash
# 1. Hentikan akses pengguna selama restore
php artisan down

# 2. Ambil arsip: dari storage/backups, atau unduh dari bucket R2 lewat dashboard Cloudflare
mkdir -p /tmp/restore
tar -xzf storage/backups/rangkul-backup-2026-10-21-013000.tar.gz -C /tmp/restore
cat /tmp/restore/manifest.json

# 3. Cocokkan checksum dump dengan manifest
sha256sum /tmp/restore/database.sql

# 4. Pulihkan database (menimpa isi database tujuan)
mysql -u rangkul -p rangkul < /tmp/restore/database.sql

# 5. Pulihkan file unggahan
rsync -a /tmp/restore/storage/ storage/app/

# 6. Hidupkan kembali aplikasi
php artisan storage:link
php artisan config:cache
php artisan up
```

Setelah restore, cek: `/up` membalas 200, login berhasil, dokumen organisasi bisa dibuka dari halaman admin, dan foto kampanye tampil.

Catatan: dump berisi trigger `activity_logs` (log aktivitas append-only). User MySQL yang dipakai untuk restore perlu hak `TRIGGER`.

## Latihan restore (lakukan berkala, mis. tiap bulan)

`backup:verify` memastikan arsip utuh, tetapi restore sungguhan tetap perlu dilatih. Gunakan database terpisah supaya data production tidak tertimpa:

```bash
mysql -u root -p -e "CREATE DATABASE rangkul_restore_test"
mysql -u root -p rangkul_restore_test < /tmp/restore/database.sql
mysql -u root -p -e "SELECT COUNT(*) FROM rangkul_restore_test.users; SELECT COUNT(*) FROM rangkul_restore_test.donations;"
mysql -u root -p -e "DROP DATABASE rangkul_restore_test"
```

Catat tanggal latihan, nama arsip, dan lama prosesnya. Lama proses ini menjadi perkiraan berapa lama layanan mati saat restore sungguhan.
