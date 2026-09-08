<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Console\Command;
use Midtrans\Config;
use Midtrans\Transaction;

class SyncDonationStatuses extends Command
{
    protected $signature = 'donations:sync-midtrans';

    protected $description = 'Synchronize unpaid donation statuses with Midtrans';

    public function handle(): int
    {
        Config::$serverKey = config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
        Config::$isProduction = config('services.midtrans.is_production', env('MIDTRANS_IS_PRODUCTION', false));

        Donation::query()
            ->where('status', 'belum_bayar')
            ->whereNotNull('transaction_id')
            ->each(function (Donation $donation): void {
                try {
                    $status = Transaction::status($donation->id);
                    $transactionStatus = $status->transaction_status ?? null;

                    if (in_array($transactionStatus, ['cancel', 'deny', 'expire', 'failure'], true)) {
                        $donation->update([
                            'status' => 'gagal',
                            'transaction_id' => $status->transaction_id ?? $donation->transaction_id,
                        ]);
                    } elseif (in_array($transactionStatus, ['settlement', 'capture'], true)) {
                        $donation->update([
                            'status' => 'sudah_bayar',
                            'transaction_id' => $status->transaction_id ?? $donation->transaction_id,
                            'paid_at' => now(),
                        ]);

                        Campaign::where('id', $donation->id_campaign)
                            ->increment('target_terkumpul', $donation->nominal);
                    }
                } catch (\Throwable $exception) {
                    $this->warn("Failed to synchronize donation {$donation->id}: {$exception->getMessage()}");
                }
            });

        return self::SUCCESS;
    }
}
