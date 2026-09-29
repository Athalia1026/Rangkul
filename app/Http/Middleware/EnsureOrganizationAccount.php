<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pastikan user yang login adalah akun organisasi yang sudah disetujui admin.
 */
class EnsureOrganizationAccount
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $organization = $user?->organization;

        if (!$user || $user->account_type !== 'organisasi' || !$organization) {
            abort(403, 'Halaman ini hanya untuk akun organisasi.');
        }

        if ($organization->verification_status === 'ditolak') {
            return redirect()->route('organization.rejected', ['email' => $user->email]);
        }

        if ($organization->verification_status !== 'disetujui') {
            return redirect()->route('organization.pending');
        }

        return $next($request);
    }
}
