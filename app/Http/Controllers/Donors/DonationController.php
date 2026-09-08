<?php

namespace App\Http\Controllers\Donors;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Carbon\Carbon;

class DonationController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
        Config::$isProduction = config('services.midtrans.is_production', env('MIDTRANS_IS_PRODUCTION', false));
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    // 1. Donatur Mengajukan Donasi (Generate QRIS Payment)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_campaign' => 'required|exists:campaigns,id',
            'nominal' => 'required|numeric|min:10000|max:10000000',
            'note' => 'nullable|string|max:255',
            'anonim' => 'nullable|boolean',
        ], [
            // Custom Pesan Error dalam Bahasa Indonesia
            'nominal.min' => 'Nominal donasi minimal adalah Rp 10.000.',
            'nominal.max' => 'Nominal donasi maksimal adalah Rp 10.000.000.',
        ]);

        $user = auth()->user();
        $donorId = $user->donor?->id ?? $user->id;

        $adminFee = 3000; // Biaya admin platform Rangkul
        $totalAmount = $validated['nominal'] + $adminFee; // Total yang harus dibayar donatur

        return DB::transaction(function () use ($validated, $user, $donorId, $adminFee, $totalAmount) {
            // Buat record donasi awal
            $donation = Donation::create([
                'id_campaign' => $validated['id_campaign'],
                'id_donatur' => $donorId,
                'nominal' => $validated['nominal'],
                'note' => $validated['note'] ?? null,
                'anonim' => $validated['anonim'] ? 1 : 0,
                'status' => 'belum_bayar',
            ]);

            // Payload Transaksi ke Midtrans khusus QRIS (Gopay/QRIS)
            $params = [
                'transaction_details' => [
                    'order_id' => $donation->id,
                    'gross_amount' => (int) $totalAmount, // Total nominal + biaya admin
                ],
                'customer_details' => [
                    'first_name' => $donation->anonim ? 'Hamba Allah' : $user->name,
                    'email' => $user->email,
                ],
                'enabled_payments' => ['gopay', 'qris'], // Membatasi opsi pembayaran hanya ke QRIS/GoPay

            ];

            // Request Snap Token dari Midtrans
            $snapToken = Snap::getSnapToken($params);

            $snapRedirectUrl = Snap::getSnapUrl($params);

            // Simpan snapToken / transaction_id
            $donation->update(['transaction_id' => $snapToken]);

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi donasi berhasil dibuat.',
                'data' => [
                    'donation' => $donation,
                    'nominal_donasi' => (int) $validated['nominal'],
                    'biaya_admin' => $adminFee,
                    'total_bayar' => $totalAmount,
                    'snap_token' => $snapToken,
                    'redirect_url' => $snapRedirectUrl,
                ]
            ], 201);
        });
    }

    // 2. Webhook Callback Notifikasi dari Midtrans
    public function handleCallback(Request $request)
    {
        try {
            $payload = $request->all();
            Log::info('Midtrans notification received', [
                'order_id' => $payload['order_id'] ?? null,
                'transaction_status' => $payload['transaction_status'] ?? null,
                'transaction_id' => $payload['transaction_id'] ?? null,
            ]);

            $orderId = $payload['order_id'] ?? null;
            $transactionStatus = $payload['transaction_status'] ?? null;
            $fraudStatus = $payload['fraud_status'] ?? null;
            $signatureKey = $payload['signature_key'] ?? null;

            if (!$orderId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Order ID tidak ditemukan dalam request.'
                ], 400);
            }

            $expectedSignature = hash('sha512', implode('', [
                $orderId,
                $payload['status_code'] ?? '',
                $payload['gross_amount'] ?? '',
                Config::$serverKey,
            ]));

            if (!$signatureKey || !hash_equals($expectedSignature, $signatureKey)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Signature callback tidak valid.'
                ], 401);
            }

            // Cari data donasi berdasarkan Primary Key (id)
            $donation = Donation::find($orderId);

            if (!$donation) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data donasi tidak ditemukan.'
                ], 404);
            }

            // Ambil transaction_id dari Midtrans jika ada, atau gunakan transaksi bawaan
            $transactionIdMidtrans = $payload['transaction_id'] ?? $donation->transaction_id;

            if ($donation->status === 'sudah_bayar') {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Transaksi sudah diproses sebelumnya.'
                ], 200);
            }

            // 1. SKENARIO PEMBAYARAN SUKSES
            if (in_array($transactionStatus, ['settlement', 'capture'], true)) {
                if ($fraudStatus == 'accept' || empty($fraudStatus)) {
                    $donation->update([
                        'status' => 'sudah_bayar',
                        'transaction_id' => $transactionIdMidtrans,
                        'paid_at' => now(),
                    ]);

                    // Akumulasi dana murni donasi ke Campaign
                    Campaign::where('id', $donation->id_campaign)
                        ->increment('target_terkumpul', $donation->nominal);
                }
            }
            // 2. SKENARIO PEMBAYARAN GAGAL / EXPIRED / CANCEL / DENY
            elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire', 'failure'], true)) {
                $donation->update([
                    'status' => 'gagal',
                    'transaction_id' => $transactionIdMidtrans,
                ]);

            }
            // 3. SKENARIO MENUNGGU PEMBAYARAN (pending)
            elseif ($transactionStatus === 'pending') {
                $donation->update([
                    'status' => 'belum_bayar'
                ]);

            }

            return response()->json([
                'status' => 'success',
                'message' => 'Callback Midtrans berhasil diproses.'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Midtrans notification failed', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getCampaignWishes($campaignId)
{
    // Set locale Carbon ke Bahasa Indonesia agar diffForHumans menghasilkan "x hari yang lalu"
    Carbon::setLocale('id');

    $wishes = Donation::with(['donor.user'])
        ->where('id_campaign', $campaignId)
        ->where('status', 'sudah_bayar')
        ->whereNotNull('note') // Hanya ambil yang memiliki catatan/doa
        ->where('note', '!=', '')
        ->orderBy('paid_at', 'desc')
        ->paginate(10); // Gunakan paginasi agar performa tetap cepat

    // Transformasi data response
    $wishes->getCollection()->transform(function ($donation) {
        $user = $donation->donor?->user;

        return [
            'id'           => $donation->id,
            'nama_donatur' => $donation->anonim ? 'Hamba Allah' : ($user?->name ?? 'Donatur'),
            'photo_profile'=> $donation->anonim ? null : ($user?->photo_url ?? null),
            'nominal'      => (int) $donation->nominal,
            'note'         => $donation->note,
            'paid_at'      => $donation->paid_at ? Carbon::parse($donation->paid_at)->diffForHumans() : null,
        ];
    });

    return response()->json([
        'status' => 'success',
        'data'   => $wishes
    ], 200);
}
}