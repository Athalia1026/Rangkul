<?php

namespace App\Http\Controllers\Donors;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PremiumDashboardController extends Controller
{
    public function index(Request $request)
    {
        $donorId = $request->user()->donor->id;
        $donations = $this->paidDonations($donorId);
        $monthly = $donations->get()->groupBy(fn ($donation) => Carbon::parse($donation->paid_at)->format('Y-m'));

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_donasi' => (float) $donations->sum('nominal'),
                'jumlah_penyaluran' => $donations->count(),
                'jumlah_kampanye' => (clone $donations)->distinct('id_campaign')->count('id_campaign'),
                'tren_bulanan' => collect(range(11, 0))->map(function (int $monthsAgo) use ($monthly) {
                    $month = now()->subMonths($monthsAgo);
                    $key = $month->format('Y-m');

                    return [
                        'periode' => $month->translatedFormat('M Y'),
                        'jumlah_donasi' => $monthly->get($key, collect())->count(),
                        'total_donasi' => (float) $monthly->get($key, collect())->sum('nominal'),
                    ];
                })->values(),
                'donasi_terbaru' => (clone $donations)->with('campaign:id,judul')->latest('paid_at')->limit(10)->get(),
                'subscription' => $request->attributes->get('premium_subscription'),
            ],
        ]);
    }

    public function export(Request $request)
    {
        $donorId = $request->user()->donor->id;
        $donations = $this->paidDonations($donorId)
            ->with('campaign:id,judul,deskripsi,foto_cover')
            ->latest('paid_at')
            ->get();
        $subscription = $request->attributes->get('premium_subscription');
        $company = $request->user()->companyPremium()->with('donor.user')->first();

        $pdf = Pdf::loadView('reports.premium-impact', [
            'company' => $company,
            'donations' => $donations,
            'subscription' => $subscription,
            'total' => (float) $donations->sum('nominal'),
            'campaignCount' => $donations->pluck('id_campaign')->unique()->count(),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-dampak-premium-' . now()->format('Y-m-d') . '.pdf');
    }

    private function paidDonations(string $donorId)
    {
        return Donation::query()
            ->where('id_donatur', $donorId)
            ->where('status', 'sudah_bayar');
    }
}
