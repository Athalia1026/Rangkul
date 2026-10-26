<?php

namespace App\Services;

use App\Services\NotificationService;
use App\Mail\PremiumActivatedMail;
use App\Models\CompanyPremium;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Midtrans\Config;
use Midtrans\Snap;

class PremiumService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /** Rincian biaya langganan premium: harga paket + PPN, tanpa biaya layanan. */
    public function pricing(): array
    {
        $price = (int) config('services.premium.price', 1000000);
        $tax = (int) round($price * (float) config('services.premium.tax_rate', 0.11));

        return [
            'price' => $price,
            'service_fee' => 0,
            'tax_rate' => (float) config('services.premium.tax_rate', 0.11),
            'tax' => $tax,
            'total' => $price + $tax,
            'duration_months' => (int) config('services.premium.duration_months', 12),
        ];
    }

    public function createRegistration(array $data, $user): array
    {
        return DB::transaction(function () use ($data, $user) {
            $donor = $user->donor()->firstOrCreate([], [
                'tipe' => 'perusahaan',
                'no_telp' => $data['nomor_pic'],
                'kota' => $data['kota'] ?? '-',
            ]);
            $donor->update(['tipe' => 'perusahaan']);

            $company = CompanyPremium::updateOrCreate(
                ['id_donatur' => $donor->id],
                [
                    'nama_pic' => $data['nama_pic'],
                    'jabatan' => $data['jabatan'],
                    'nomor_pic' => $data['nomor_pic'],
                    'email_korporat' => $data['email_korporat'],
                    'NPWP' => $data['NPWP'],
                ]
            );

            $subscription = Subscription::create([
                'id_perusahaan' => $company->id,
                'status' => 'menunggu_bayar',
            ]);

            $orderId = 'PREM-' . strtoupper(str_replace('-', '', $subscription->id));
            $subscription->update(['transaction_id' => $orderId]);

            $pricing = $this->pricing();
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $pricing['total'],
                ],
                'item_details' => [
                    ['id' => 'PREMIUM', 'price' => $pricing['price'], 'quantity' => 1, 'name' => 'Akun Premium Perusahaan'],
                    ['id' => 'PPN', 'price' => $pricing['tax'], 'quantity' => 1, 'name' => 'Pajak (PPN 11%)'],
                ],
                'customer_details' => [
                    'first_name' => $data['nama_pic'],
                    'email' => $data['email_korporat'],
                    'phone' => $data['nomor_pic'],
                ],
                'enabled_payments' => ['gopay', 'qris'],
                'expiry' => ['unit' => 'hours', 'duration' => DonationService::PAYMENT_WINDOW_HOURS],
                // Setelah bayar, Midtrans mengarahkan kembali ke halaman hasil premium (bukan Finish URL di dashboard Midtrans).
                'callbacks' => ['finish' => route('donatur.premium.berhasil', $subscription->id)],
            ];

            // Satu kali panggilan Snap: token & redirect_url berasal dari transaksi yang sama.
            $transaction = Snap::createTransaction($params);

            return [
                'company' => $company->fresh(),
                'subscription' => $subscription->fresh(),
                'pricing' => $pricing,
                'snap_token' => $transaction->token,
                'redirect_url' => $transaction->redirect_url,
            ];
        });
    }

    public function handleCallback(array $payload): array
    {
        $orderId = $payload['order_id'] ?? null;
        if (!$orderId || !str_starts_with($orderId, 'PREM-')) {
            return ['statusCode' => 404, 'response' => ['status' => 'error', 'message' => 'Order premium tidak ditemukan.']];
        }

        // Tanpa server key, signature bisa dihitung siapa saja: tolak callback.
        if (!Config::$serverKey) {
            return ['statusCode' => 503, 'response' => ['status' => 'error', 'message' => 'Pembayaran belum dikonfigurasi.']];
        }

        if (!$this->isValidSignature($payload, $orderId)) {
            return ['statusCode' => 401, 'response' => ['status' => 'error', 'message' => 'Signature callback tidak valid.']];
        }

        $subscription = Subscription::where('transaction_id', $orderId)->first();
        if (!$subscription) {
            return ['statusCode' => 404, 'response' => ['status' => 'error', 'message' => 'Subscription tidak ditemukan.']];
        }

        // transaction_status tidak ikut ditandatangani Midtrans: status diambil ulang langsung dari Midtrans.
        try {
            $status = $this->fetchGatewayStatus($orderId);
        } catch (\Throwable $exception) {
            Log::warning('Midtrans premium status check failed', ['order_id' => $orderId, 'message' => $exception->getMessage()]);

            // Respons non-2xx membuat Midtrans mengirim ulang notifikasi ini nanti.
            return ['statusCode' => 502, 'response' => ['status' => 'error', 'message' => 'Status transaksi belum dapat dikonfirmasi.']];
        }

        if (($status['order_id'] ?? null) !== $orderId || !$this->amountMatches($status)) {
            return ['statusCode' => 422, 'response' => ['status' => 'error', 'message' => 'Nominal atau status transaksi tidak sesuai.']];
        }

        $this->applyPaymentStatus($subscription, $status);

        return ['statusCode' => 200, 'response' => ['status' => 'success', 'message' => 'Callback premium berhasil diproses.']];
    }

    /**
     * Tanyakan status langsung ke Midtrans, agar langganan tetap aktif walaupun
     * webhook tidak sampai (mis. saat aplikasi berjalan di localhost).
     */
    public function refreshPayment(Subscription $subscription): Subscription
    {
        if ($subscription->status !== 'menunggu_bayar' || !$subscription->transaction_id) {
            return $subscription;
        }

        $pending = false;
        try {
            $payload = $this->fetchGatewayStatus($subscription->transaction_id);
            if ($this->amountMatches($payload)) {
                $this->applyPaymentStatus($subscription, $payload);
                $pending = ($payload['transaction_status'] ?? null) === 'pending';
            }
        } catch (\Throwable $exception) {
            // 404 dari Midtrans berarti donatur belum memilih metode pembayaran.
            Log::info('Premium payment status unavailable', ['order_id' => $subscription->transaction_id, 'message' => $exception->getMessage()]);
        }

        // Lewat batas waktu bayar tanpa pembayaran yang berjalan: nonaktifkan agar tidak tertahan
        // "menunggu pembayaran" selamanya (Midtrans tidak mengirim status bila metode belum dipilih).
        $subscription->refresh();
        if ($subscription->status === 'menunggu_bayar' && !$pending
            && $subscription->created_at->lte(now()->subHours(DonationService::PAYMENT_WINDOW_HOURS))) {
            $subscription->update(['status' => 'nonaktif']);
        }

        return $subscription->fresh();
    }

    /** Status transaksi langsung dari API Midtrans (sumber yang dipercaya, bukan isi notifikasi). */
    protected function fetchGatewayStatus(string $orderId): array
    {
        return (array) \Midtrans\Transaction::status($orderId);
    }

    /** Langganan hanya aktif bila yang dibayar sama dengan harga paket + PPN. */
    private function amountMatches(array $payload): bool
    {
        return (int) ($payload['gross_amount'] ?? 0) === $this->pricing()['total'];
    }

    private function applyPaymentStatus(Subscription $subscription, array $payload): void
    {
        $status = $payload['transaction_status'] ?? null;
        // Hanya langganan yang masih menunggu pembayaran yang bisa diaktifkan; notifikasi
        // settlement yang dikirim ulang untuk langganan lama tidak boleh menambah masa aktif.
        if (in_array($status, ['settlement', 'capture'], true)
            && in_array($payload['fraud_status'] ?? null, ['accept', null, ''], true)
            && $subscription->status === 'menunggu_bayar') {
            $this->activate($subscription, $payload['transaction_id'] ?? $subscription->transaction_id);
        } elseif (in_array($status, ['cancel', 'deny', 'expire', 'failure'], true) && $subscription->status === 'menunggu_bayar') {
            $subscription->update(['status' => 'nonaktif']);
        }
    }

    public function activate(Subscription $subscription, ?string $midtransId = null): void
    {
        $wasActive = $subscription->isActive();
        $startedAt = $wasActive ? $subscription->started_at : now();

        $subscription->update([
            'status' => 'aktif',
            'started_at' => $startedAt,
            'expired_at' => $startedAt->copy()->addMonths((int) config('services.premium.duration_months', 12)),
            'transaction_id' => $subscription->transaction_id ?: $midtransId,
            'paid_at' => now(),
        ]);

        if (!$wasActive) {
            app(NotificationService::class)->subscriptionActivated($subscription->fresh());

            $company = $subscription->company()->with('donor.user')->first();
            $email = $company?->email_korporat ?? $company?->donor?->user?->email;
            if ($email) {
                try {
                    Mail::to($email)->send(new PremiumActivatedMail($subscription->fresh()));
                } catch (\Throwable $exception) {
                    Log::error('Premium activation email failed', ['message' => $exception->getMessage()]);
                }
            }
        }
    }

    private function isValidSignature(array $payload, string $orderId): bool
    {
        $signature = $payload['signature_key'] ?? null;
        $expected = hash('sha512', $orderId . ($payload['status_code'] ?? '') . ($payload['gross_amount'] ?? '') . Config::$serverKey);

        return $signature !== null && hash_equals($expected, $signature);
    }
}