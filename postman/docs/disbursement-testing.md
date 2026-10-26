# Panduan Test Fitur Pencairan Dana di Postman

Berikut cara testing flow pencairan dana dan verifikasi admin secara lengkap di Postman.

## 1) Jalankan server Laravel

Pastikan server berjalan di local:

```bash
php artisan serve
```

Base URL yang dipakai:

```text
http://127.0.0.1:8000/api
```

---

## 2) Login untuk mendapat token

### Endpoint login

```http
POST http://127.0.0.1:8000/api/login
```

Body (JSON):

```json
{
  "email": "organisasi@email.com",
  "password": "password123"
}
```

Jika user adalah admin, gunakan akun admin yang punya role `staff`/`manager`.

Setelah login, ambil token dari response dan masukkan ke Postman:

```text
Authorization: Bearer <token>
```

Catatan penguncian login:
- Setelah 5 kali password salah untuk email yang sama, email itu dikunci 15 menit dari IP mana pun (respons `429`). Atur lewat `LOGIN_MAX_ATTEMPTS` dan `LOGIN_LOCKOUT_SECONDS`.
- Hitungan gagal ini dipakai bersama dengan endpoint pengajuan ulang pendaftaran (`POST /api/registration/resubmit`). Jika akun uji terkunci saat testing, tunggu 15 menit atau jalankan `php artisan cache:clear` di lokal.

---

## 3) Organisasi ajukan pencairan dana

### Endpoint

```http
POST http://127.0.0.1:8000/api/organizations/disbursements
```

Header:

```text
Authorization: Bearer <token-organisasi>
```

Body (form-data):

```text
id_campaign: <id_campaign>
alokasi_dana: "Dana operasional"
nominal_diajukan: 2500000
alasan: "Dana untuk kebutuhan program bulanan"
lampiran: file pdf/jpg/png (opsional)
```

Contoh response sukses:

```json
{
  "status": "success",
  "data": {
    "id": "...",
    "status": "menunggu"
  }
}
```

Syarat pengajuan (dicek di server):

| Syarat | Respons bila tidak terpenuhi |
|---|---|
| Organisasi sudah disetujui admin (`verification_status = disetujui`) | `403` "Organisasi Anda belum terverifikasi oleh Admin." |
| Tidak ada pencairan lain yang masih menunggu verifikasi, atau yang sudah disetujui tetapi bukti penyalurannya belum diterima | `403` dengan pesan alasan pengajuan tertahan |
| Rekening bank lembaga sudah diverifikasi (`status_verifikasi = diterima`) | `400` "Rekening bank tidak valid atau belum diverifikasi." |
| `nominal_diajukan` tidak melebihi saldo kampanye (donasi lunas dikurangi pencairan yang sudah disetujui) | `422` "Nominal pencairan melebihi saldo kampanye yang tersedia (Rp ...)." |

Pengajuan diproses bergantian per organisasi, sehingga dua pengajuan yang dikirim bersamaan tidak bisa sama-sama lolos.

---

## 4) Admin melihat daftar pencairan yang menunggu

### Endpoint

```http
GET http://127.0.0.1:8000/api/admin/disbursements/pending
```

Header:

```text
Authorization: Bearer <token-admin>
```

Detail satu pengajuan (dipakai halaman detail pengajuan manajer):

```http
GET http://127.0.0.1:8000/api/admin/disbursements/{id}
```

Selain data pengajuan, respons memuat saldo kampanye dan rekening tujuan agar manajer bisa membandingkan sebelum memutuskan:

```json
{
  "status": "success",
  "data": {
    "amount": 2500000,
    "campaign_balance": 4000000,
    "destination_account": {
      "bank": "BCA",
      "no_rekening": "1234567890",
      "pemilik_rekening": "Yayasan Contoh",
      "status": "diterima"
    }
  }
}
```

---

## 5) Manajer menyetujui / menolak pencairan dana

Hanya akun admin dengan role `manager` yang bisa memutuskan pencairan dana (`staff` mendapat `403`).

### Endpoint

```http
PUT http://127.0.0.1:8000/api/admin/disbursements/{id}/verify
```

Header:

```text
Authorization: Bearer <token-manager>
```

Body (JSON):

```json
{
  "status": "diterima",
  "nominal_dicairkan": 2500000
}
```

Untuk menolak:

```json
{
  "status": "ditolak",
  "alasan_tolak": "Dokumen pendukung belum lengkap"
}
```

Aturan persetujuan:
- `nominal_dicairkan` opsional (default = `nominal_diajukan`), minimal 1, dan tidak boleh melebihi nominal yang diajukan maupun saldo kampanye. Jika melebihi, respons `422`.
- Rekening tujuan harus masih berstatus `diterima`. Rekening yang diganti lewat pengajuan ulang pendaftaran harus diverifikasi lagi; sebelum itu persetujuan ditolak dengan `409`. Menolak pengajuan tetap bisa dilakukan.
- Pengajuan yang sudah diputuskan tidak bisa diputuskan ulang (`409`).
- Saat disetujui, data rekening tujuan (bank, nomor, nama pemilik) disalin ke pencairan (kolom `rekening_bank`, `rekening_nomor`, `rekening_pemilik`). Transfer selalu mengikuti salinan ini, walaupun rekening lembaga diubah setelahnya.

---

## 5b) Manajer mencatat transfer manual

Setelah dana ditransfer ke rekening tujuan, manajer mencatat transfernya.

### Endpoint

```http
POST http://127.0.0.1:8000/api/admin/disbursements/{id}/manual-transfer
```

Header:

```text
Authorization: Bearer <token-manager>
```

Body (form-data, semuanya opsional):

```text
bukti_transfer: file pdf/jpg/png (maks. 2 MB)
referensi_transfer: TRF-20261026-001
catatan: Transfer via BCA
```

Catatan:
- Hanya untuk pencairan berstatus `diterima`.
- Transfer hanya bisa dicatat sekali. Pemanggilan berikutnya mendapat `409` dan data transfer (tanggal bayar, referensi, bukti) tidak berubah.

---

## 6) Organisasi upload bukti pengeluaran setelah pencairan disetujui

### Endpoint

```http
POST http://127.0.0.1:8000/api/organizations/disbursements/{disbursementId}/proofs
```

Header:

```text
Authorization: Bearer <token-organisasi>
```

Body (form-data):

```text
bukti_file: file pdf/jpg/png
nominal: 2500000
deskripsi: Pembelian alat kebutuhan program
```

`nominal` minimal 1; nilai nol atau negatif ditolak dengan `422`.

---

## 7) Admin staff dan manager verifikasi bukti pengeluaran

### Endpoint

```http
PUT http://127.0.0.1:8000/api/admin/proof-verifications/{proofId}/verify
```

Header:

```text
Authorization: Bearer <token-admin>
```

Body (JSON):

```json
{
  "status": "diterima",
  "catatan": "Bukti pengeluaran sesuai dengan kebutuhan program"
}
```

Untuk tolak:

```json
{
  "status": "ditolak",
  "catatan": "Nominal bukti tidak sesuai ajukan"
}
```

Catatan penting:
- `staff` bisa memproses dulu, lalu `manager` baru final.
- `manager` tidak bisa langsung approve jika `staff` belum menilai.

---

## 8) Email Mailtrap akan terkirim otomatis

Saat status final berubah menjadi:
- pencairan dana -> `diterima` atau `ditolak`
- bukti pengeluaran -> `diterima` atau `ditolak`

maka sistem akan otomatis mengirim email ke email organisasi yang terkait menggunakan Mailtrap.

Pastikan `.env` sudah diisi dengan kredensial Mailtrap.

---

## 9) Cara import ke Postman

Jika Anda ingin import request dari file workspace, gunakan request sample yang sudah ada di:

- [postman/collections/New Collection/http-127.0.0.1-8000-api-organization-campaigns.request.yaml](../collections/New Collection/http-127.0.0.1-8000-api-organization-campaigns.request.yaml)

Untuk flow pencairan dana yang baru, Anda bisa meniru bentuk request di atas lalu buat request baru manual di Postman.

Request di collection memakai variabel `{{auth_token}}`, bukan token asli. Isi variabel itu di environment Postman lokal Anda dengan token hasil login. Jangan menyimpan token asli ke file di folder `postman/`, karena folder ini ikut ter-commit ke git.

---

## 10) Link cepat yang bisa dipakai

- Base URL API: http://127.0.0.1:8000/api
- Login: http://127.0.0.1:8000/api/login
- Ajukan pencairan: http://127.0.0.1:8000/api/organizations/disbursements
- List pending admin: http://127.0.0.1:8000/api/admin/disbursements/pending
- Detail pengajuan: http://127.0.0.1:8000/api/admin/disbursements/{id}
- Verifikasi manajer dana: http://127.0.0.1:8000/api/admin/disbursements/{id}/verify
- Catat transfer manual: http://127.0.0.1:8000/api/admin/disbursements/{id}/manual-transfer
- Upload bukti: http://127.0.0.1:8000/api/organizations/disbursements/{disbursementId}/proofs
- Verifikasi bukti admin: http://127.0.0.1:8000/api/admin/proof-verifications/{proofId}/verify

Jika Anda mau, saya bisa lanjutkan ke tahap berikutnya dengan membuatkan file collection Postman yang siap di-import lengkap untuk semua endpoint ini.
