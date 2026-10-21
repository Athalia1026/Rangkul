<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Trusted proxies
    |--------------------------------------------------------------------------
    |
    | Dibaca middleware TrustProxies bawaan Laravel. Isi bila aplikasi berada di
    | belakang proxy/CDN (mis. Cloudflare), agar IP pengunjung asli dan skema https
    | terbaca benar. Tanpa ini, rate limit login dipakai bersama semua pengunjung,
    | IP di log aktivitas salah, dan signed URL gagal (403).
    | Format: daftar IP/CIDR dipisah koma, atau "*" bila server hanya menerima
    | koneksi dari proxy tersebut. Kosongkan untuk lokal.
    |
    */

    'proxies' => env('TRUSTED_PROXIES'),

];
