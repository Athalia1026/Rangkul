<?php

namespace App\Http\Controllers\Donors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Visit; // Pastikan model Visit sudah ada
use Illuminate\Support\Facades\Auth;

class ActivityHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
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
                'invoice_id'        => $donation->transaction_id ?? 'D-NS-' . strtoupper(substr($donation->id, 0, 8)),
                'amount'            => (float) $donation->nominal,
                'campaign_name'     => $donation->campaign?->judul ?? 'Campaign Tidak Diketahui',
                'organization_name' => $donation->campaign?->organization?->nama_lembaga ?? '-',
                'created_at'        => $donation->created_at,
                'status_asli'       => $donation->status,
                'display_status'    => $displayStatus,
                'campaign_image'    => $donation->campaign?->foto_cover ? asset('storage/' . $donation->campaign->foto_cover) : null,
            ];
        });

        // ==========================================
        // 3. Query Riwayat Kunjungan
        // ==========================================
        $visits = Visit::with('organization')
            ->where('id_donatur', $donorId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($visit) {
                return [
                    'id'                => $visit->id,
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