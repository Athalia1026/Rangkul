<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Membatasi endpoint admin per tipe akun (kolom admins.tipe), dipasang setelah CheckIsAdmin.
 * Contoh: CheckAdminRole::class . ':manager,staff'
 */
class CheckAdminRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $role = $request->user()?->adminProfile?->tipe;

        if (!in_array($role, $roles, true)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Akses ditolak. Fitur ini tidak tersedia untuk peran admin Anda.'
            ], 403);
        }

        return $next($request);
    }
}
