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

---

## 5) Admin menyetujui / menolak pencairan dana

### Endpoint

```http
PUT http://127.0.0.1:8000/api/admin/disbursements/{id}/verify
```

Header:

```text
Authorization: Bearer <token-admin>
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

---

## 10) Link cepat yang bisa dipakai

- Base URL API: http://127.0.0.1:8000/api
- Login: http://127.0.0.1:8000/api/login
- Ajukan pencairan: http://127.0.0.1:8000/api/organizations/disbursements
- List pending admin: http://127.0.0.1:8000/api/admin/disbursements/pending
- Verifikasi admin dana: http://127.0.0.1:8000/api/admin/disbursements/{id}/verify
- Upload bukti: http://127.0.0.1:8000/api/organizations/disbursements/{disbursementId}/proofs
- Verifikasi bukti admin: http://127.0.0.1:8000/api/admin/proof-verifications/{proofId}/verify

Jika Anda mau, saya bisa lanjutkan ke tahap berikutnya dengan membuatkan file collection Postman yang siap di-import lengkap untuk semua endpoint ini.
