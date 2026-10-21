<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Header keamanan untuk semua respons (halaman, API, unduhan).
 *
 * - nosniff: browser tidak menebak tipe file (mencegah file unggahan dijalankan sebagai HTML/JS).
 * - X-Frame-Options / frame-ancestors: halaman Rangkul tidak bisa dibingkai situs lain (clickjacking).
 * - Referrer-Policy: URL lengkap (token, signed URL) tidak bocor ke situs lain lewat header Referer.
 * - Permissions-Policy: mematikan API perangkat yang tidak dipakai aplikasi.
 * - HSTS: hanya pada https dan bila diaktifkan lewat SECURITY_HSTS_MAX_AGE.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $headers = $response->headers;

        $headers->set('X-Content-Type-Options', 'nosniff', false);
        $headers->set('X-Frame-Options', 'DENY', false);
        $headers->set('Referrer-Policy', 'strict-origin-when-cross-origin', false);
        $headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), usb=()', false);

        // CSP di sini hanya mengatur pembingkaian; CSP penuh untuk skrip belum diterapkan.
        if (!$headers->has('Content-Security-Policy')) {
            $headers->set('Content-Security-Policy', "frame-ancestors 'none'");
        }

        $maxAge = config('security.hsts_max_age');
        if ($maxAge > 0 && $request->isSecure()) {
            $headers->set('Strict-Transport-Security', "max-age={$maxAge}; includeSubDomains", false);
        }

        $headers->remove('X-Powered-By');
        header_remove('X-Powered-By');

        return $response;
    }
}
