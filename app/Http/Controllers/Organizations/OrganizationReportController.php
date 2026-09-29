<?php

namespace App\Http\Controllers\Organizations;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\FundDisbursement;
use App\Models\Visit;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrganizationReportController extends Controller
{
    // Halaman laporan: ringkasan keseluruhan organisasi (web)
    public function index(Request $request)
    {
        $organization = $request->user()->organization;
        $filters = $this->validatedFilters($request, $organization->id);

        $campaignSummaries = Campaign::where('id_organisasi', $organization->id)
            ->withFinancialSummary()
            ->when($filters['campaign'], fn ($q, $id) => $q->whereKey($id))
            ->latest()
            ->get();

        $visits = Visit::with('donor.user:id,nama')
            ->where('id_organisasi', $organization->id)
            ->when($filters['start'], fn ($q, $start) => $q->whereDate('tanggal_kunjungan', '>=', $start))
            ->when($filters['end'], fn ($q, $end) => $q->whereDate('tanggal_kunjungan', '<=', $end))
            ->latest('tanggal_kunjungan')
            ->take(10)
            ->get();

        return view('organisasi.laporan', [
            'filters'           => $filters,
            'campaignOptions'   => $this->campaignOptions($organization->id),
            'stats'             => $this->stats($organization->id, $filters),
            'campaignSummaries' => $campaignSummaries,
            'visits'            => $visits,
            'disbursements'     => $this->disbursementQuery($organization->id, $filters)
                ->with('campaign:id,judul')
                ->latest()
                ->take(10)
                ->get(),
        ]);
    }

    // Halaman hasil laporan sesuai periode & campaign yang dipilih (web)
    public function result(Request $request)
    {
        $organization = $request->user()->organization;
        $filters = $this->validatedFilters($request, $organization->id);

        return view('organisasi.laporan-hasil', [
            'filters'         => $filters,
            'campaignOptions' => $this->campaignOptions($organization->id),
            'stats'           => $this->stats($organization->id, $filters),
            'monthlyChart'    => $this->monthlyChart($organization->id, $filters),
            'donations'       => $this->paidDonationQuery($organization->id, $filters)
                ->with('donor.user:id,nama')
                ->latest('paid_at')
                ->paginate(10)
                ->withQueryString(),
            'disbursements'   => $this->disbursementQuery($organization->id, $filters)
                ->latest()
                ->get(),
        ]);
    }

    // Unduh laporan dalam bentuk PDF sesuai filter (web)
    public function download(Request $request)
    {
        $organization = $request->user()->organization;
        $filters = $this->validatedFilters($request, $organization->id);

        $pdf = Pdf::loadView('reports.organization-report', [
            'organization'  => $organization,
            'filters'       => $filters,
            'campaignName'  => $filters['campaign']
                ? $this->campaignOptions($organization->id)->firstWhere('id', $filters['campaign'])?->judul
                : null,
            'stats'         => $this->stats($organization->id, $filters),
            'donations'     => $this->paidDonationQuery($organization->id, $filters)
                ->with(['donor.user:id,nama', 'campaign:id,judul'])
                ->latest('paid_at')
                ->get(),
            'disbursements' => $this->disbursementQuery($organization->id, $filters)
                ->with('campaign:id,judul')
                ->latest()
                ->get(),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-organisasi-' . now()->format('Y-m-d') . '.pdf');
    }

    private function validatedFilters(Request $request, string $organizationId): array
    {
        $validated = $request->validate([
            'tanggal_awal'  => 'nullable|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_awal',
            'campaign'      => [
                'nullable',
                Rule::exists('campaigns', 'id')->where('id_organisasi', $organizationId),
            ],
        ]);

        return [
            'start'    => isset($validated['tanggal_awal']) ? Carbon::parse($validated['tanggal_awal'])->startOfDay() : null,
            'end'      => isset($validated['tanggal_akhir']) ? Carbon::parse($validated['tanggal_akhir'])->endOfDay() : null,
            'campaign' => $validated['campaign'] ?? null,
        ];
    }

    private function campaignOptions(string $organizationId)
    {
        return Campaign::where('id_organisasi', $organizationId)
            ->orderBy('judul')
            ->get(['id', 'judul']);
    }

    private function paidDonationQuery(string $organizationId, array $filters)
    {
        return Donation::where('status', 'sudah_bayar')
            ->whereHas('campaign', fn ($q) => $q->where('id_organisasi', $organizationId))
            ->when($filters['campaign'], fn ($q, $id) => $q->where('id_campaign', $id))
            ->when($filters['start'], fn ($q, $start) => $q->where('paid_at', '>=', $start))
            ->when($filters['end'], fn ($q, $end) => $q->where('paid_at', '<=', $end));
    }

    private function disbursementQuery(string $organizationId, array $filters)
    {
        return FundDisbursement::whereHas('campaign', fn ($q) => $q->where('id_organisasi', $organizationId))
            ->when($filters['campaign'], fn ($q, $id) => $q->where('id_campaign', $id))
            ->when($filters['start'], fn ($q, $start) => $q->where('created_at', '>=', $start))
            ->when($filters['end'], fn ($q, $end) => $q->where('created_at', '<=', $end));
    }

    private function stats(string $organizationId, array $filters): array
    {
        $totalDonasi = (float) $this->paidDonationQuery($organizationId, $filters)->sum('nominal');
        $totalPencairan = (float) $this->disbursementQuery($organizationId, $filters)
            ->where('status', 'diterima')
            ->sum('nominal_dicairkan');

        return [
            'total_donasi'    => $totalDonasi,
            'total_pencairan' => $totalPencairan,
            'saldo_tersisa'   => max(0, $totalDonasi - $totalPencairan),
            'total_donatur'   => $this->paidDonationQuery($organizationId, $filters)->distinct()->count('id_donatur'),
        ];
    }

    /**
     * Total donasi per bulan pada periode filter (default: 6 bulan terakhir, maksimal 12 bulan).
     */
    private function monthlyChart(string $organizationId, array $filters): array
    {
        $end = ($filters['end'] ?? now())->copy()->startOfMonth();
        $start = ($filters['start'] ?? $end->copy()->subMonths(5))->copy()->startOfMonth();

        if ($start->diffInMonths($end) > 11) {
            $start = $end->copy()->subMonths(11);
        }

        $totals = $this->paidDonationQuery($organizationId, $filters)
            ->whereNotNull('paid_at')
            ->get(['nominal', 'paid_at'])
            ->groupBy(fn ($donation) => Carbon::parse($donation->paid_at)->format('Y-m'))
            ->map(fn ($items) => (float) $items->sum('nominal'));

        $months = collect(CarbonPeriod::create($start, '1 month', $end))
            ->map(fn (Carbon $month) => [
                'label' => $month->locale('id')->translatedFormat('M'),
                'total' => $totals->get($month->format('Y-m'), 0.0),
            ]);

        $max = max(1, $months->max('total'));

        return $months
            ->map(fn ($month) => $month + ['height' => (int) round(($month['total'] / $max) * 220)])
            ->values()
            ->all();
    }
}
