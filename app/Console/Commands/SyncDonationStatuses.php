<?php

namespace App\Console\Commands;

use App\Models\Donation;
use App\Models\Subscription;
use App\Services\DonationService;
use App\Services\PremiumService;
use Illuminate\Console\Command;

class SyncDonationStatuses extends Command
{
    protected $signature = 'donations:sync-midtrans';

    protected $description = 'Synchronize unpaid donation statuses with Midtrans';

    public function handle(DonationService $service, PremiumService $premiumService): int
    {
        // refreshPayment memproses lewat processDonationCallback, sehingga status,
        // notifikasi berhasil/gagal, dan validasi nominal ikut ditangani di sana. Donasi tanpa
        // snap_token juga diproses agar ikut ditandai kedaluwarsa (tidak tertahan belum_bayar).
        Donation::where('status', 'belum_bayar')
            ->each(fn (Donation $donation) => $service->refreshPayment($donation));

        // Langganan premium yang menunggu pembayaran diperlakukan sama.
        Subscription::where('status', 'menunggu_bayar')
            ->each(fn (Subscription $subscription) => $premiumService->refreshPayment($subscription));

        return self::SUCCESS;
    }
}
