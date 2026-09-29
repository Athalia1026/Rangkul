<?php

namespace App\Http\Controllers\Organizations;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;

class OrganizationDonationController extends Controller
{
    public const STATUS_FILTERS = ['sudah_bayar', 'belum_bayar', 'gagal'];

    // Halaman riwayat donasi yang masuk ke seluruh kampanye organisasi (web)
    public function index(Request $request)
    {
        $organizationId = $request->user()->organization->id;

        $baseQuery = fn () => Donation::whereHas('campaign', fn ($q) => $q->where('id_organisasi', $organizationId));
        $paid = fn () => $baseQuery()->where('status', 'sudah_bayar');

        $stats = [
            'total'          => (float) $paid()->sum('nominal'),
            'hari_ini'       => (float) $paid()->whereDate('paid_at', today())->sum('nominal'),
            'bulan_ini'      => (float) $paid()
                ->whereYear('paid_at', now()->year)
                ->whereMonth('paid_at', now()->month)
                ->sum('nominal'),
            'kampanye_aktif' => Campaign::where('id_organisasi', $organizationId)->where('status', 'aktif')->count(),
        ];

        $donations = $baseQuery()
            ->with(['campaign:id,judul', 'donor.user:id,nama'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where('anonim', false)
                    ->whereHas('donor.user', fn ($q) => $q->where('nama', 'like', '%' . $request->q . '%'));
            })
            ->when(
                in_array($request->status, self::STATUS_FILTERS, true),
                fn ($q) => $q->where('status', $request->status)
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('organisasi.donasi', compact('stats', 'donations'));
    }

    // Halaman detail satu donasi (web)
    public function show(Request $request, string $donationId)
    {
        $organizationId = $request->user()->organization->id;

        $donation = Donation::with(['campaign:id,judul', 'donor.user:id,nama'])
            ->whereKey($donationId)
            ->whereHas('campaign', fn ($q) => $q->where('id_organisasi', $organizationId))
            ->firstOrFail();

        return view('organisasi.donasi-detail', compact('donation'));
    }
}
