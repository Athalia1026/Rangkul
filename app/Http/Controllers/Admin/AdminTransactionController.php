<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Support\Carbon;

class AdminTransactionController extends Controller
{
    private const STATUS = [
        'sudah_bayar' => ['key' => 'disetujui', 'label' => 'Selesai'],
        'belum_bayar' => ['key' => 'menunggu', 'label' => 'Menunggu'],
        'gagal' => ['key' => 'gagal', 'label' => 'Gagal'],
    ];

    // Daftar seluruh transaksi donasi untuk halaman Laporan Transaksi
    public function index()
    {
        $donations = Donation::with(['donor.user', 'campaign.organization'])
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_selesai' => $donations->where('status', 'sudah_bayar')->count(),
                'transactions' => $donations->map(fn (Donation $donation) => [
                    'id' => $donation->id,
                    'date' => optional($donation->paid_at ? Carbon::parse($donation->paid_at) : $donation->created_at)->format('d/m/Y'),
                    'donor_name' => $donation->donor?->user?->nama ?? '-',
                    'organization_name' => $donation->campaign?->organization?->nama_lembaga ?? '-',
                    'amount' => (float) $donation->nominal,
                    'method' => $donation->paymentMethodLabel(),
                    ...$this->status($donation),
                ])->values(),
            ],
        ]);
    }

    // Detail satu transaksi beserta bukti penyaluran dana campaign-nya
    public function show($id)
    {
        $donation = Donation::with([
            'donor.user',
            'campaign.organization',
            'campaign.fundDisbursements.purchaseProofs',
        ])->findOrFail($id);

        $date = $donation->paid_at ? Carbon::parse($donation->paid_at) : $donation->created_at;

        // Bukti penyaluran hanya relevan untuk donasi yang sudah dibayar dan bukti yang sudah diverifikasi.
        $disbursements = $donation->status === 'sudah_bayar'
            ? ($donation->campaign?->fundDisbursements ?? collect())
            : collect();
        $proofs = $disbursements
            ->flatMap(fn ($disbursement) => $disbursement->purchaseProofs
                ->where('status', 'diterima')
                ->map(fn ($proof) => [
                    'url' => asset('storage/' . ltrim($proof->lokasi_file, '/')),
                    'is_image' => (bool) preg_match('/\.(jpe?g|png|gif|webp)$/i', $proof->lokasi_file),
                    'description' => $proof->deskripsi ?: $disbursement->alokasi_dana,
                    'uploaded_at' => $proof->uploaded_at,
                ]))
            ->sortByDesc('uploaded_at')
            ->values();
        $latestUpload = $proofs->first()['uploaded_at'] ?? null;

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $donation->id,
                'transaction_id' => $donation->transaction_id ?? $donation->id,
                'date' => $date?->locale('id')->translatedFormat('j F Y'),
                'organization_name' => $donation->campaign?->organization?->nama_lembaga ?? '-',
                'organization_address' => $donation->campaign?->organization?->alamat ?? '-',
                'campaign_title' => $donation->campaign?->judul,
                'donor_name' => $donation->donor?->user?->nama ?? '-',
                'donor_type' => $donation->donor ? ucfirst($donation->donor->tipe) : '-',
                'amount' => (float) $donation->nominal,
                'method' => $donation->paymentMethodLabel(),
                ...$this->status($donation),
                'proofs' => $proofs->map(fn ($proof) => [
                    'url' => $proof['url'],
                    'is_image' => $proof['is_image'],
                ]),
                'proof_description' => $proofs->pluck('description')->filter()->unique()->implode('; ') ?: null,
                'proof_uploaded_at' => $latestUpload
                    ? Carbon::parse($latestUpload)->locale('id')->translatedFormat('j F Y')
                    : null,
            ],
        ]);
    }

    private function status(Donation $donation): array
    {
        $status = self::STATUS[$donation->status] ?? ['key' => $donation->status, 'label' => ucfirst((string) $donation->status)];

        return ['status' => $status['key'], 'status_label' => $status['label']];
    }
}
