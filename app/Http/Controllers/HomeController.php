<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $campaigns = Campaign::query()
            ->with([
                'organization',
                'donations' => fn ($query) => $query->where('status', 'sudah_bayar'),
            ])
            ->whereIn('status', ['aktif', 'disalurkan'])
            ->where(function ($query) {
                $query->whereNull('tanggal_selesai')
                    ->orWhere('tanggal_selesai', '>=', now());
            })
            ->latest()
            ->get()
            ->map(function (Campaign $campaign) {
                $campaign->total_terkumpul = $campaign->donations->sum('nominal');
                $campaign->progress = $campaign->target_dana > 0
                    ? min(100, round(($campaign->total_terkumpul / $campaign->target_dana) * 100))
                    : 0;
                $campaign->wishes_count = $campaign->donations
                    ->whereNotNull('note')
                    ->where('note', '!=', '')
                    ->count();

                return $campaign;
            });

        $farFromTarget = $campaigns
            ->filter(fn (Campaign $campaign) => $campaign->progress < 50);

        $topCampaigns = $campaigns
            ->filter(fn (Campaign $campaign) => $campaign->sisa_hari >= 0 && $campaign->sisa_hari <= 14)
            ->filter(fn (Campaign $campaign) => $campaign->progress < 50)
            ->sort(function (Campaign $firstCampaign, Campaign $secondCampaign) {
                return [$secondCampaign->wishes_count, $firstCampaign->progress]
                    <=> [$firstCampaign->wishes_count, $secondCampaign->progress];
            })
            ->take(5)
            ->values();

        if ($topCampaigns->isEmpty()) {
            $topCampaigns = $farFromTarget
                ->sort(function (Campaign $firstCampaign, Campaign $secondCampaign) {
                    return [$secondCampaign->wishes_count, $firstCampaign->progress]
                        <=> [$firstCampaign->wishes_count, $secondCampaign->progress];
                })
                ->take(5)
                ->values();
        }

        $todayCampaigns = $farFromTarget
            ->sortBy('progress')
            ->take(7)
            ->values();

        $priorityCampaigns = $campaigns
            ->filter(fn (Campaign $campaign) => $campaign->sisa_hari >= 0)
            ->where('sisa_hari', '<', 14)
            ->sortBy('sisa_hari')
            ->take(7)
            ->values();

        return view('home', compact('topCampaigns', 'todayCampaigns', 'priorityCampaigns'));
    }
}
