<?php

namespace App\Http\Controllers\Organizations;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\FundDisbursement;
use App\Models\Visit;
use Illuminate\Http\Request;

class OrganizationDashboardController extends Controller
{
    public function index(Request $request)
    {
        $organization = $request->user()->organization;

        $campaigns = Campaign::where('id_organisasi', $organization->id)
            ->withFinancialSummary()
            ->latest()
            ->take(2)
            ->get();

        $paidDonations = Donation::where('status', 'sudah_bayar')
            ->whereHas('campaign', fn ($q) => $q->where('id_organisasi', $organization->id));

        $totalDonasi = (float) $paidDonations->sum('nominal');

        $totalDicairkan = (float) FundDisbursement::where('status', 'diterima')
            ->whereHas('campaign', fn ($q) => $q->where('id_organisasi', $organization->id))
            ->sum('nominal_dicairkan');

        $stats = [
            'kampanye_aktif'     => Campaign::where('id_organisasi', $organization->id)->where('status', 'aktif')->count(),
            'total_donasi'       => $totalDonasi,
            'kunjungan_menunggu' => Visit::where('id_organisasi', $organization->id)->where('status', 'terkirim')->count(),
            'saldo_tersedia'     => max(0, $totalDonasi - $totalDicairkan),
        ];

        $latestDonations = Donation::with(['campaign:id,judul', 'donor.user:id,nama'])
            ->whereHas('campaign', fn ($q) => $q->where('id_organisasi', $organization->id))
            ->latest()
            ->take(4)
            ->get();

        $latestVisits = Visit::with('donor.user:id,nama')
            ->where('id_organisasi', $organization->id)
            ->latest()
            ->take(4)
            ->get();

        return view('organisasi.dashboard', compact(
            'organization',
            'campaigns',
            'stats',
            'latestDonations',
            'latestVisits'
        ));
    }
}
