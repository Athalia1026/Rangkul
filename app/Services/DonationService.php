<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\Donation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class DonationService
{
    public const PAYMENT_FEE = 3000;
    public function refreshPayment(Donation $donation): Donation
    {
        if ($donation->status !== 'belum_bayar' || !$donation->snap_token) return $donation;
        try {
            $payload = (array) \Midtrans\Transaction::status($donation->id);
            DB::transaction(function () use ($donation, $payload) {
                $locked = Donation::lockForUpdate()->findOrFail($donation->id);
                if ($locked->status === 'sudah_bayar') return;
                if (($payload['order_id'] ?? '') !== $locked->id) return;
                if ((float) ($payload['gross_amount'] ?? 0) !== (float) ($locked->nominal + ($locked->payment_fee ?? self::PAYMENT_FEE))) return;
                $this->processDonationCallback($locked, $payload);
            });
        } catch (\Throwable $exception) {
            // A not-yet-started Snap transaction can return 404; keep it pending.
        }
        return $donation->fresh();
    }
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
        Config::$isProduction = config('services.midtrans.is_production', env('MIDTRANS_IS_PRODUCTION', false));
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createDonationTransaction(array $validated, $user): array
    {
        $donorId = $user->donor?->id ?? $user->id;
        $adminFee = self::PAYMENT_FEE;
        $totalAmount = $validated['nominal'] + $adminFee;

        return DB::transaction(function () use ($validated, $user, $donorId, $adminFee, $totalAmount) {
            $donation = Donation::create([
                'id_campaign' => $validated['id_campaign'],
                'id_donatur' => $donorId,
                'nominal' => $validated['nominal'],
                'note' => $validated['note'] ?? null,
                'anonim' => !empty($validated['anonim']) ? 1 : 0,
                'status' => 'belum_bayar',
                'payment_fee' => $adminFee,
            ]);

            $params = [
                'transaction_details' => [
                    'order_id' => $donation->id,
                    'gross_amount' => (int) $totalAmount,
                ],
                'customer_details' => [
                    'first_name' => $donation->anonim ? 'Hamba Allah' : ($user->nama ?? 'Donatur'),
                    'email' => $user->email,
                ],
                'enabled_payments' => ['gopay', 'qris'],
            ];

            $transaction = $this->createGatewayTransaction($params);
            $snapToken = $transaction->token;
            $snapRedirectUrl = $transaction->redirect_url;

            $donation->update(['snap_token' => $snapToken, 'payment_url' => $snapRedirectUrl]);

            return [
                'status' => 'success',
                'message' => 'Transaksi donasi berhasil dibuat.',
                'data' => [
                    'donation' => $donation,
                    'nominal_donasi' => (int) $validated['nominal'],
                    'biaya_admin' => $adminFee,
                    'total_bayar' => $totalAmount,
                    'snap_token' => $snapToken,
                    'redirect_url' => $snapRedirectUrl,
                ],
                'http_status' => 201,
            ];
        });
    }

    protected function createGatewayTransaction(array $params): object
    {
        return Snap::createTransaction($params);
    }

    public function handleCallback(array $payload): array
    {
        try {
            $this->logCallbackReceived($payload);

            $orderId = $this->extractOrderId($payload);
            if ($orderId === null) {
                return $this->buildErrorResponse(400, 'Order ID tidak ditemukan dalam request.');
            }

            if (!$this->isValidSignature($payload, $orderId)) {
                return $this->buildErrorResponse(401, 'Signature callback tidak valid.');
            }

            if (!Config::$serverKey) return $this->buildErrorResponse(503, 'Pembayaran belum dikonfigurasi.');

            $donation = $this->findDonationByOrderId($orderId);
            if ($donation === null) {
                return $this->buildErrorResponse(404, 'Data donasi tidak ditemukan.');
            }

            if ($this->isAlreadyProcessed($donation)) {
                return $this->buildAlreadyProcessedResponse();
            }

            if ((float) ($payload['gross_amount'] ?? 0) !== (float) ($donation->nominal + ($donation->payment_fee ?? self::PAYMENT_FEE))) {
                return $this->buildErrorResponse(422, 'Nominal pembayaran tidak sesuai.');
            }
            DB::transaction(function () use ($donation, $payload) {
                $locked = Donation::lockForUpdate()->findOrFail($donation->id);
                if (!$this->isAlreadyProcessed($locked)) $this->processDonationCallback($locked, $payload);
            });

            return $this->buildSuccessResponse();
        } catch (\Exception $e) {
            $this->logCallbackFailed($e);

            return $this->buildErrorResponse(500, $e->getMessage());
        }
    }

    private function processDonationCallback(Donation $donation, array $payload): void
    {
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        $transactionIdMidtrans = $payload['transaction_id'] ?? $donation->transaction_id;

        if ($this->isSuccessfulTransaction($transactionStatus, $fraudStatus)) {
            $this->markDonationPaid($donation, $transactionIdMidtrans);

            return;
        }

        if ($this->isFailedTransaction($transactionStatus)) {
            $this->markDonationFailed($donation, $transactionIdMidtrans);

            return;
        }

        if ($transactionStatus === 'pending') {
            $this->markDonationPending($donation);
        }
    }

    private function isSuccessfulTransaction(?string $transactionStatus, ?string $fraudStatus): bool
    {
        return in_array($transactionStatus, ['settlement', 'capture'], true)
            && ($fraudStatus === 'accept' || $fraudStatus === null || $fraudStatus === '');
    }

    private function isFailedTransaction(?string $transactionStatus): bool
    {
        return in_array($transactionStatus, ['cancel', 'deny', 'expire', 'failure'], true);
    }

    private function markDonationFailed(Donation $donation, ?string $transactionIdMidtrans): void
    {
        $donation->update([
            'status' => 'gagal',
            'transaction_id' => $transactionIdMidtrans,
        ]);
    }

    private function markDonationPending(Donation $donation): void
    {
        $donation->update([
            'status' => 'belum_bayar',
        ]);
    }

    private function logCallbackReceived(array $payload): void
    {
        Log::info('Midtrans notification received', [
            'order_id' => $payload['order_id'] ?? null,
            'transaction_status' => $payload['transaction_status'] ?? null,
            'transaction_id' => $payload['transaction_id'] ?? null,
        ]);
    }

    private function findDonationByOrderId(string $orderId): ?Donation
    {
        return Donation::find($orderId);
    }

    private function buildAlreadyProcessedResponse(): array
    {
        return [
            'statusCode' => 200,
            'response' => [
                'status' => 'success',
                'message' => 'Transaksi sudah diproses sebelumnya.',
            ],
        ];
    }

    private function buildSuccessResponse(): array
    {
        return [
            'statusCode' => 200,
            'response' => [
                'status' => 'success',
                'message' => 'Callback Midtrans berhasil diproses.',
            ],
        ];
    }

    private function logCallbackFailed(\Exception $e): void
    {
        Log::error('Midtrans notification failed', [
            'message' => $e->getMessage(),
        ]);
    }

    private function extractOrderId(array $payload): ?string
    {
        return $payload['order_id'] ?? null;
    }

    private function isValidSignature(array $payload, string $orderId): bool
    {
        $signatureKey = $payload['signature_key'] ?? null;
        if (!$signatureKey) {
            return false;
        }

        $expectedSignature = hash('sha512', implode('', [
            $orderId,
            $payload['status_code'] ?? '',
            $payload['gross_amount'] ?? '',
            Config::$serverKey,
        ]));

        return hash_equals($expectedSignature, $signatureKey);
    }

    private function isAlreadyProcessed(Donation $donation): bool
    {
        return $donation->status === 'sudah_bayar';
    }

    private function markDonationPaid(Donation $donation, ?string $transactionIdMidtrans): void
    {
        $donation->update([
            'status' => 'sudah_bayar',
            'transaction_id' => $transactionIdMidtrans,
            'paid_at' => now(),
        ]);

        // Campaign totals are calculated from paid donations, not a cached column.
    }

    private function buildErrorResponse(int $statusCode, string $message): array
    {
        return [
            'statusCode' => $statusCode,
            'response' => [
                'status' => 'error',
                'message' => $message,
            ],
        ];
    }

    public function getCampaignWishes(string $campaignId)
    {
        Carbon::setLocale('id');

        $wishes = Donation::with(['donor.user'])
            ->where('id_campaign', $campaignId)
            ->where('status', 'sudah_bayar')
            ->whereNotNull('note')
            ->where('note', '!=', '')
            ->orderBy('paid_at', 'desc')
            ->paginate(10);

        $wishes->getCollection()->transform(function ($donation) {
            $user = $donation->donor?->user;

            return [
                'id' => $donation->id,
                'nama_donatur' => $donation->anonim ? 'Hamba Allah' : ($user?->nama ?? 'Donatur'),
                'photo_profile' => $donation->anonim ? null : ($user?->profile_photo ? asset('storage/' . $user->profile_photo) : null),
                'nominal' => (int) $donation->nominal,
                'note' => $donation->note,
                'paid_at' => $donation->paid_at ? Carbon::parse($donation->paid_at)->diffForHumans() : null,
            ];
        });

        return $wishes;
    }
}
