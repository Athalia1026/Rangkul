<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPremium
{
    public function handle(Request $request, Closure $next)
    {
        $subscription = $request->user()?->companyPremium?->activeSubscription()->first();

        if (!$subscription) {
            return response()->json([
                'status' => 'error',
                'message' => 'Fitur ini hanya tersedia untuk perusahaan premium aktif.',
            ], 403);
        }

        $request->attributes->set('premium_subscription', $subscription);

        return $next($request);
    }
}