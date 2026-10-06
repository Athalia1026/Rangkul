<?php

namespace App\Http\Controllers\Donors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Visit; // Pastikan model Visit sudah ada
use App\Services\DonationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ActivityHistoryController extends Controller
{
    public function distribution(Request $request, string $id)
    {
        abort_unless($request->user()->account_type === 'donatur' && $request->user()->donor, 403);
        $donation = Donation::with(['campaign.organization', 'campaign.fundDisbursements.purchaseProofs'])
            ->where('id_donatur', $request->user()->donor->id)->where('status', 'sudah_bayar')->findOrFail($id);
        $reports = ($donation->campaign?->fundDisbursements ?? collect())->map(function ($report) {
            $proofs = $report->purchaseProofs->where('status', 'diterima');
            if ($proofs->isEmpty()) return null;
            return ['id' => $report->id, 'date' => $report->paid_at ?: $proofs->max('uploaded_at'),
                'total' => (float) $proofs->sum('nominal'), 'description' => $report->alokasi_dana ?: $report->alasan,
                'proofs' => $proofs->map(fn ($proof) => ['description' => $proof->deskripsi, 'amount' => (float) $proof->nominal, 'url' => asset('storage/' . $proof->lokasi_file)])->values()];
        })->filter()->values();
        return response()->json(['campaign' => $donation->campaign?->judul, 'organization' => $donation->campaign?->organization?->nama_lembaga, 'reports' => $reports]);
    }

    public function receipt(Request $request, string $id)
    {
        abort_unless($request->user()->account_type === 'donatur' && $request->user()->donor, 403);
        $donation = Donation::with(['campaign.organization', 'donor.user'])
            ->where('id_donatur', $request->user()->donor->id)->where('status', 'sudah_bayar')->findOrFail($id);
        $invoiceId = $donation->transaction_id ?? 'D-NS-' . strtoupper(substr($donation->id, 0, 8));
        $fee = (float) ($donation->payment_fee ?? DonationService::PAYMENT_FEE);

        return Pdf::loadView('reports.donation-receipt', [
            'donation' => $donation,
            'invoiceId' => $invoiceId,
            'fee' => $fee,
            'total' => (float) $donation->nominal + $fee,
        ])->setPaper('a5', 'portrait')->download('bukti-pembayaran-' . $invoiceId . '.pdf');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        abort_unless($user->account_type === 'donatur', 403);
        $donorId = $user?->donor?->id ?? $user?->id;

        // ==========================================
        // 1. Kalkulasi Statistik Atas (Header Stats)
        // ==========================================
        $totalDonasiBerhasil = Donation::where('id_donatur', $donorId)
            ->where('status', 'sudah_bayar')
            ->count();

        $totalNominalDonasi = Donation::where('id_donatur', $donorId)
            ->where('status', 'sudah_bayar')
            ->sum('nominal');

        $totalKunjungan = Visit::where('id_donatur', $donorId)->count();

        // ==========================================
        // 2. Query Riwayat Donasi & Logika "Sudah Disalurkan"
        // ==========================================
        $rawDonations = Donation::with([
            'campaign.organization',
            'campaign.fundDisbursements.purchaseProofs'
        ])
            ->where('id_donatur', $donorId)
            ->orderBy('created_at', 'desc')
            ->get();

        $donations = $rawDonations->map(function ($donation) {
            $displayStatus = $donation->status;

            if ($donation->status === 'sudah_bayar') {
                $isDistributed = false;

                if ($donation->campaign && $donation->campaign->fundDisbursements) {
                    foreach ($donation->campaign->fundDisbursements as $disbursement) {
                        if ($disbursement->purchaseProofs->where('status', 'diterima')->isNotEmpty()) {
                            $isDistributed = true;
                            break;
                        }
                    }
                }

                if ($isDistributed) {
                    $displayStatus = 'sudah_disalurkan';
                }
            }

            return [
                'id'                => $donation->id,
                'proofs' => $displayStatus === 'sudah_disalurkan' ? $donation->campaign->fundDisbursements->flatMap(fn ($item) => $item->purchaseProofs->where('status', 'diterima'))->map(fn ($proof) => ['url' => asset('storage/' . $proof->lokasi_file), 'description' => $proof->deskripsi])->values() : [],
                'invoice_id'        => $donation->transaction_id ?? 'D-NS-' . strtoupper(substr($donation->id, 0, 8)),
                'amount'            => (float) $donation->nominal,
                'campaign_name'     => $donation->campaign?->judul ?? 'Campaign Tidak Diketahui',
                'organization_name' => $donation->campaign?->organization?->nama_lembaga ?? '-',
                'created_at'        => $donation->created_at,
                'paid_at'           => $donation->paid_at,
                'distributed_at'    => $displayStatus === 'sudah_disalurkan' ? $donation->campaign->fundDisbursements->flatMap(fn ($item) => $item->purchaseProofs->where('status', 'diterima'))->max('updated_at') : null,
                'status_asli'       => $donation->status,
                'display_status'    => $displayStatus,
                'campaign_image'    => $donation->campaign?->foto_cover ? asset('storage/' . $donation->campaign->foto_cover) : null,
            ];
        });

        // ==========================================
        // 3. Query Riwayat Kunjungan
        // ==========================================
        $visits = Visit::with(['organization.galleries', 'documents'])
            ->where('id_donatur', $donorId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($visit) {
                return [
                    'id'                => $visit->id,
                    'organization_id'   => $visit->id_organisasi,
                    'updated_at' => $visit->updated_at,
                    'image' => $visit->organization?->galleries->first()?->image_url,
                    'date' => $visit->tanggal_kunjungan,
                    'time' => $visit->waktu_kunjungan,
                    'message' => $visit->pesan_donatur,
                    'response' => $visit->pesan_organisasi,
                    'documents' => $visit->documents->map(fn ($document) => asset('storage/' . $document->lokasi_file))->values(),
                    'organization_name' => $visit->organization?->nama_lembaga ?? '-',
                    'lokasi'            => $visit->organization?->kota ?? '-',
                    'tanggal_waktu'     => trim($visit->tanggal_kunjungan . ' ' . ($visit->waktu_kunjungan ?? '')),
                    'jumlah_orang'      => (int) $visit->pengunjung,
                    'status'            => $visit->status,
                ];
            });

        // ==========================================
        // 4. Return Data
        // ==========================================
        return response()->json([
            'status' => 'success',
            'data' => [
                'stats' => [
                    'total_donasi_kali' => $totalDonasiBerhasil,
                    'total_nominal_rp'  => (float) $totalNominalDonasi,
                    'total_kunjungan'   => $totalKunjungan,
                ],
                'riwayat_donasi'    => $donations,
                'riwayat_kunjungan' => $visits,
            ]
        ]);
    }
}
