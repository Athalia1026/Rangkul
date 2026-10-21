<?php

return [

    /*
    |--------------------------------------------------------------------------
    | HTTP Strict Transport Security (HSTS)
    |--------------------------------------------------------------------------
    |
    | Lama (detik) browser wajib memakai https untuk domain ini. 0 = mati.
    | Hanya dikirim pada request https. Mulai dari nilai kecil (mis. 300),
    | naikkan ke 31536000 (1 tahun) setelah https dipastikan stabil.
    |
    */

    'hsts_max_age' => (int) env('SECURITY_HSTS_MAX_AGE', 0),

    /*
    |--------------------------------------------------------------------------
    | Penguncian login per akun
    |--------------------------------------------------------------------------
    |
    | Melengkapi rate limit per IP: setelah beberapa kali password salah untuk
    | email yang sama, login ke email itu dikunci sementara dari IP mana pun.
    |
    */

    'login_max_attempts' => (int) env('LOGIN_MAX_ATTEMPTS', 5),
    'login_lockout_seconds' => (int) env('LOGIN_LOCKOUT_SECONDS', 900),

];
