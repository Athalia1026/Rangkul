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

];
