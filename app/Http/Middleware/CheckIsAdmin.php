<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // 1. Cek apakah user terautentikasi dan memiliki account_type 'admin'
        if (!$user || $user->account_type !== 'admin') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Akses ditolak. Fitur ini hanya dapat diakses oleh Admin.'
            ], 403);
        }

        // 2. Cek status akun di tabel admins melalui relasi adminProfile
        if ($user->adminProfile && $user->adminProfile->status_akun !== 'aktif') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Akun admin Anda sedang dalam status nonaktif.'
            ], 403);
        }

        return $next($request);
    }
}