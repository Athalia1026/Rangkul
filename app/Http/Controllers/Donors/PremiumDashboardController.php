<?php

namespace App\Http\Controllers\Donors;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PremiumDashboardController extends Controller
{
    private const MONTHS = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

    /**
     * Data dashboard premium untuk satu periode.
     * ?period=2026-h1 (Januari–Juni), 2026-h2 (Juli–Desember), atau 2026 (satu tahun).
     */
    public function index(Request $request)
    {
        $donorId = $request->user()->donor->id;
        $period = $this->resolvePeriod($request->query('period'));

        $paid = $this->paidBetween($donorId, $period['start'], $period['end'])
            ->with(['campaign.organization:id,nama_lembaga,jumlah_anak', 'campaign.fundDisbursements.purchaseProofs' => fn ($query) => $query->where('status', 'diterima')])
            ->get();
        $previousTotal = (float) $this->paidBetween($donorId, $period['previous_start'], $period['previous_end'])->sum('nominal');
        $total = (float) $paid->sum('nominal');
        $organizations = $paid->pluck('campaign.organization')->filter()->unique('id');
        $beneficiaries = $this->beneficiaries($paid->filter(fn (Donation $donation) => $this->isDistributed($donation)));

        return response()->json([
            'status' => 'success',
            'data' => [
                'period' => $this->periodPayload($period),
                'periods' => $this->availablePeriods($donorId),
                'stats' => [
                    'total_donasi' => $total,
                    // Perubahan dibanding periode sebelumnya dengan panjang yang sama; null bila tidak ada pembanding.
                    'pertumbuhan_persen' => $previousTotal > 0 ? round(($total - $previousTotal) / $previousTotal * 100, 1) : null,
                    'jumlah_kampanye' => $paid->pluck('id_campaign')->unique()->count(),
                    'jumlah_organisasi' => $organizations->count(),
                    'penerima_manfaat' => $beneficiaries,
                ],
                'tren' => $this->monthlyTrend($paid, $period),
                'riwayat' => $this->recentDonations($donorId, $period),
                'subscription' => $request->attributes->get('premium_subscription'),
            ],
        ]);
    }

    // Data pratinjau laporan dampak CSR (JSON) untuk periode yang sama dengan dashboard.
    public function report(Request $request)
    {
        $report = $this->buildReport($request, $this->resolvePeriod($request->query('period')));

        return response()->json(['status' => 'success', 'data' => $report]);
    }

    public function export(Request $request)
    {
        $period = $this->resolvePeriod($request->query('period'));
        $report = $this->buildReport($request, $period, embedImages: true);

        return Pdf::loadView('reports.premium-impact', ['report' => $report])
            ->setPaper('a4', 'portrait')
            ->download('laporan-dampak-csr-' . $period['key'] . '.pdf');
    }

    /**
     * Isi laporan: ringkasan, detail donasi yang dibayar, dan dokumentasi bukti penyaluran.
     * Donasi dianggap tersalurkan bila kampanyenya punya bukti penyaluran yang disetujui admin.
     * $embedImages = true mengubah gambar menjadi data URI agar bisa dibaca DomPDF tanpa akses jaringan.
     */
    private function buildReport(Request $request, array $period, bool $embedImages = false): array
    {
        $user = $request->user();
        $donorId = $user->donor->id;
        $donations = $this->paidBetween($donorId, $period['start'], $period['end'])
            ->with(['campaign.organization:id,nama_lembaga,jumlah_anak', 'campaign.fundDisbursements.purchaseProofs' => fn ($query) => $query->where('status', 'diterima')])
            ->latest('paid_at')
            ->get();
        $previousTotal = (float) $this->paidBetween($donorId, $period['previous_start'], $period['previous_end'])->sum('nominal');
        $isDistributed = fn (Donation $donation) => $this->isDistributed($donation);
        $distributed = $donations->filter($isDistributed);
        $total = (float) $donations->sum('nominal');

        $documentation = $donations->pluck('campaign')->filter()->unique('id')
            ->flatMap(fn ($campaign) => $campaign->fundDisbursements->flatMap(fn ($item) => $item->purchaseProofs)
                ->map(fn ($proof) => ['proof' => $proof, 'organization' => $campaign->organization?->nama_lembaga ?? '-']))
            ->filter(fn ($item) => preg_match('/\.(jpe?g|png|webp)$/i', (string) $item['proof']->lokasi_file))
            ->sortByDesc(fn ($item) => $item['proof']->uploaded_at ?? $item['proof']->created_at)
            ->take(4)
            ->map(fn ($item) => [
                'judul' => $item['proof']->deskripsi ?: 'Dokumentasi penyaluran',
                'organisasi' => $item['organization'],
                'url' => $embedImages ? $this->embed($item['proof']->lokasi_file) : asset('storage/' . $item['proof']->lokasi_file),
            ])
            ->filter(fn ($item) => $item['url'])
            ->values();

        return [
            'period' => $this->periodPayload($period),
            'company' => [
                'nama' => $user->nama,
                'logo' => $user->profile_photo ? ($embedImages ? $this->embed($user->profile_photo) : $user->profilePhotoUrl()) : null,
            ],
            'stats' => [
                'total_donasi' => $total,
                'pertumbuhan_persen' => $previousTotal > 0 ? round(($total - $previousTotal) / $previousTotal * 100, 1) : null,
                'total_tersalurkan' => (float) $distributed->sum('nominal'),
                'kampanye_didukung' => $donations->pluck('id_campaign')->unique()->count(),
                'kampanye_tersalurkan' => $distributed->pluck('id_campaign')->unique()->count(),
                'penerima_manfaat' => $this->beneficiaries($distributed),
            ],
            'donasi' => $donations->map(fn (Donation $donation) => [
                'id' => $donation->id,
                'kampanye' => $donation->campaign?->judul ?? 'Kampanye',
                'organisasi' => $donation->campaign?->organization?->nama_lembaga ?? '-',
                'tanggal' => $donation->paid_at,
                'nominal' => (float) $donation->nominal,
                'status' => $isDistributed($donation) ? 'sudah_disalurkan' : 'sudah_bayar',
            ])->values()->all(),
            'dokumentasi' => $documentation->all(),
        ];
    }

    /** Donasi tersalurkan bila kampanyenya punya bukti penyaluran yang disetujui (relasi sudah difilter 'diterima'). */
    private function isDistributed(Donation $donation): bool
    {
        return ($donation->campaign?->fundDisbursements ?? collect())
            ->contains(fn ($item) => $item->purchaseProofs->isNotEmpty());
    }

    /**
     * Penerima manfaat = jumlah anak di organisasi yang menerima donasi tersalurkan.
     * Setiap organisasi dihitung sekali walaupun beberapa kampanyenya didukung.
     */
    private function beneficiaries(Collection $distributedDonations): int
    {
        return (int) $distributedDonations->pluck('campaign.organization')->filter()->unique('id')->sum('jumlah_anak');
    }

        private function embed(?string $path): ?string
    {
        if (!$path || filter_var($path, FILTER_VALIDATE_URL)) {
            return null;
        }
        $file = storage_path('app/public/' . ltrim($path, '/'));
        if (!is_file($file)) {
            return null;
        }

        return 'data:' . (mime_content_type($file) ?: 'image/jpeg') . ';base64,' . base64_encode(file_get_contents($file));
    }

    private function resolvePeriod(?string $key): array
    {
        $now = now();
        if (!$key || !preg_match('/^(\d{4})(?:-(h1|h2))?$/', $key, $match)) {
            $key = $now->year . ($now->month <= 6 ? '-h1' : '-h2');
            preg_match('/^(\d{4})(?:-(h1|h2))?$/', $key, $match);
        }

        $year = (int) $match[1];
        $half = $match[2] ?? null;
        $start = Carbon::create($year, $half === 'h2' ? 7 : 1, 1)->startOfDay();
        $end = $half === 'h1' ? $start->copy()->month(6)->endOfMonth() : $start->copy()->month(12)->endOfMonth();
        $months = $half ? 6 : 12;

        return [
            'key' => $key,
            'label' => match ($half) {
                'h1' => "Januari - Juni {$year}",
                'h2' => "Juli - Desember {$year}",
                default => "Tahun {$year}",
            },
            'start' => $start,
            'end' => $end,
            'previous_start' => $start->copy()->subMonths($months),
            'previous_end' => $start->copy()->subSecond(),
        ];
    }

    private function periodPayload(array $period): array
    {
        return ['key' => $period['key'], 'label' => $period['label'], 'start' => $period['start']->toDateString(), 'end' => $period['end']->toDateString()];
    }

    /** Periode dari tahun donasi pertama sampai sekarang, terbaru di atas. */
    private function availablePeriods(string $donorId): array
    {
        $firstYear = (int) (Donation::where('id_donatur', $donorId)->min('created_at')
            ? Carbon::parse(Donation::where('id_donatur', $donorId)->min('created_at'))->year
            : now()->year);
        $periods = [];
        for ($year = now()->year; $year >= $firstYear; $year--) {
            $keys = [$year . '-h1'];
            if ($year < now()->year || now()->month > 6) {
                $keys[] = $year . '-h2';
            }
            $keys[] = (string) $year;
            foreach ($keys as $key) {
                $periods[] = $this->periodPayload($this->resolvePeriod($key));
            }
        }

        return $periods;
    }

    private function monthlyTrend(Collection $paid, array $period): array
    {
        $byMonth = $paid->groupBy(fn ($donation) => Carbon::parse($donation->paid_at)->format('Y-m'));
        $trend = [];
        for ($month = $period['start']->copy(); $month->lte($period['end']); $month->addMonth()) {
            $items = $byMonth->get($month->format('Y-m'), collect());
            $trend[] = [
                'bulan' => self::MONTHS[$month->month - 1],
                'periode' => $month->format('Y-m'),
                'frekuensi' => $items->count(),
                'total' => (float) $items->sum('nominal'),
            ];
        }

        return $trend;
    }

    /** Tiga donasi terbaru pada periode, termasuk yang belum dibayar. */
    private function recentDonations(string $donorId, array $period): array
    {
        return Donation::with(['campaign.organization:id,nama_lembaga', 'campaign.fundDisbursements.purchaseProofs'])
            ->where('id_donatur', $donorId)
            ->whereIn('status', ['belum_bayar', 'sudah_bayar'])
            ->whereBetween('created_at', [$period['start'], $period['end']])
            ->latest()
            ->limit(3)
            ->get()
            ->map(function (Donation $donation) {
                $distributed = $donation->status === 'sudah_bayar'
                    && ($donation->campaign?->fundDisbursements ?? collect())
                        ->contains(fn ($item) => $item->purchaseProofs->where('status', 'diterima')->isNotEmpty());

                return [
                    'id' => $donation->id,
                    'kampanye' => $donation->campaign?->judul ?? 'Kampanye',
                    'organisasi' => $donation->campaign?->organization?->nama_lembaga ?? '-',
                    'tanggal' => $donation->created_at,
                    'nominal' => (float) $donation->nominal,
                    'status' => $distributed ? 'sudah_disalurkan' : $donation->status,
                ];
            })
            ->all();
    }

    private function paidBetween(string $donorId, Carbon $start, Carbon $end)
    {
        return Donation::query()
            ->where('id_donatur', $donorId)
            ->where('status', 'sudah_bayar')
            ->whereBetween('paid_at', [$start, $end]);
    }
}
