<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\FundDisbursement;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $hasDateFilter = $request->filled('tanggal_mulai') || $request->filled('tanggal_selesai');
        $startDate = $request->date('tanggal_mulai')?->startOfDay() ?? now()->startOfWeek(Carbon::MONDAY)->startOfDay();
        $endDate = $request->date('tanggal_selesai')?->endOfDay() ?? now()->endOfWeek(Carbon::SUNDAY)->endOfDay();

        if ($startDate->greaterThan($endDate)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tanggal mulai tidak boleh lebih besar dari tanggal selesai.',
            ], 422);
        }

        $donations = Donation::query()
            ->where('status', 'sudah_bayar')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->with('campaign.organization')
            ->get();

        $disbursementQuery = FundDisbursement::query()
            ->with(['campaign.organization', 'verifier.user']);

        if ($hasDateFilter) {
            $disbursementQuery->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->orWhereBetween('verified_at', [$startDate, $endDate])
                    ->orWhereBetween('paid_at', [$startDate, $endDate]);
            });
        }

        $disbursements = $disbursementQuery->latest()->get();
        $weeklyDisbursements = FundDisbursement::query()
            ->where('status', 'diterima')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->orWhereBetween('verified_at', [$startDate, $endDate])
                    ->orWhereBetween('paid_at', [$startDate, $endDate]);
            })
            ->get();

        $totalDonation = (float) $donations->sum('nominal');
        $allPaidDonationTotal = (float) Donation::query()
            ->where('status', 'sudah_bayar')
            ->sum('nominal');
        $allDisbursedTotal = (float) FundDisbursement::query()
            ->where('status', 'diterima')
            ->get()
            ->sum(fn ($item) => $item->nominal_dicairkan ?? $item->nominal_diajukan);
        $totalDisbursed = $allDisbursedTotal;
        $percentage = $allPaidDonationTotal > 0
            ? min(100, round(($allDisbursedTotal / $allPaidDonationTotal) * 100, 2))
            : 0;

        return response()->json([
            'status' => 'success',
            'data' => [
                'period' => [
                    'tanggal_mulai' => $startDate->toDateString(),
                    'tanggal_selesai' => $endDate->toDateString(),
                ],
                'trend' => $this->buildTrend($donations, $startDate, $endDate),
                'summary' => [
                    'total_donasi' => $totalDonation,
                    'donasi_tertinggi' => (float) ($donations->max('nominal') ?? 0),
                    'total_tersalurkan' => $totalDisbursed,
                    'persentase_tersalurkan' => $percentage,
                ],
                'organization_totals' => $this->organizationTotals($donations, $startDate, $endDate),
                'disbursements' => $disbursements->map(fn (FundDisbursement $item) => [
                    'id' => $item->id,
                    'organization_name' => $item->campaign?->organization?->nama_lembaga ?? '-',
                    'amount' => (float) ($item->nominal_dicairkan ?? $item->nominal_diajukan),
                    'verifier_name' => $item->verifier?->user?->nama ?? '-',
                    'submitted_at' => optional($item->created_at)->format('d/m/Y'),
                    'paid_at' => optional($item->paid_at ?? $item->verified_at)->format('d/m/Y'),
                    'status' => $item->status,
                ])->values(),
            ],
        ]);
    }

    private function buildTrend($donations, Carbon $startDate, Carbon $endDate): array
    {
        $days = $startDate->diffInDays($endDate) + 1;

        if ($days > 31) {
            $grouped = $donations->groupBy(fn ($donation) => Carbon::parse($donation->paid_at)->format('Y-m'));
            $months = $startDate->copy()->startOfMonth()->diffInMonths($endDate->copy()->startOfMonth()) + 1;

            return collect(range(0, $months - 1))->map(function (int $offset) use ($grouped, $startDate) {
                $month = $startDate->copy()->startOfMonth()->addMonths($offset);
                $items = $grouped->get($month->format('Y-m'), collect());

                return [
                    'label' => $month->format('M Y'),
                    'date' => $month->format('Y-m'),
                    'total' => (float) $items->sum('nominal'),
                ];
            })->values()->all();
        }

        $grouped = $donations->groupBy(fn ($donation) => Carbon::parse($donation->paid_at)->toDateString());

        return collect(range(0, min($days, 31) - 1))->map(function (int $offset) use ($startDate, $grouped) {
            $date = $startDate->copy()->addDays($offset);
            $items = $grouped->get($date->toDateString(), collect());

            return [
                'label' => $date->locale('id')->translatedFormat('D'),
                'date' => $date->toDateString(),
                'total' => (float) $items->sum('nominal'),
            ];
        })->values()->all();
    }

    private function buildDisbursementTrend($disbursements, Carbon $startDate, Carbon $endDate): array
    {
        $days = $startDate->diffInDays($endDate) + 1;
        $dateFormat = $days <= 31 ? 'Y-m-d' : 'Y-m';
        $periodCount = $days <= 31 ? $days : 12;
        $grouped = $disbursements
            ->where('status', 'diterima')
            ->groupBy(function ($item) use ($dateFormat) {
                return optional($item->paid_at ?? $item->verified_at ?? $item->created_at)->format($dateFormat);
            });

        return collect(range(0, $periodCount - 1))->map(function (int $offset) use ($grouped, $startDate, $days) {
            $period = $days <= 31
                ? $startDate->copy()->addDays($offset)
                : now()->subMonths(11 - $offset);
            $key = $period->format($days <= 31 ? 'Y-m-d' : 'Y-m');
            $items = $grouped->get($key, collect());

            return [
                'label' => $days <= 31 ? $period->format('d/m') : $period->format('M Y'),
                'total' => (float) $items->sum(fn ($item) => $item->nominal_dicairkan ?? $item->nominal_diajukan),
            ];
        })->values()->all();
    }

    private function organizationTotals($donations, Carbon $startDate, Carbon $endDate): array
    {
        $organizations = ['panti' => 'Panti', 'sekolah' => 'Sekolah'];
        $result = [];

        foreach ($organizations as $key => $label) {
            $organizationFilter = fn ($query) => $query->whereRaw('LOWER(tipe) LIKE ?', ['%' . $key . '%']);

            $monthlyTotal = Donation::query()
                ->where('status', 'sudah_bayar')
                ->whereBetween('paid_at', [
                    $endDate->copy()->startOfMonth(),
                    $endDate->copy()->endOfMonth(),
                ])
                ->whereHas('campaign.organization', $organizationFilter)
                ->sum('nominal');

            $yearlyTotal = Donation::query()
                ->where('status', 'sudah_bayar')
                ->whereYear('paid_at', $endDate->year)
                ->whereHas('campaign.organization', $organizationFilter)
                ->sum('nominal');

            $result[$key] = [
                'label' => $label,
                'monthly' => (float) $monthlyTotal,
                'yearly' => (float) $yearlyTotal,
            ];
        }

        return $result;
    }
}
