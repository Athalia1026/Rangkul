<?php

namespace App\Console\Commands;

use App\Models\Donation;
use App\Services\DonationService;
use Illuminate\Console\Command;

class SyncDonationStatuses extends Command
{
    protected $signature = 'donations:sync-midtrans';

    protected $description = 'Synchronize unpaid donation statuses with Midtrans';

    public function handle(DonationService $service): int
    {
        // refreshPayment memproses lewat processDonationCallback, sehingga status,
        // notifikasi berhasil/gagal, dan validasi nominal ikut ditangani di sana.
        Donation::where('status', 'belum_bayar')->whereNotNull('snap_token')
            ->each(fn (Donation $donation) => $service->refreshPayment($donation));

        return self::SUCCESS;
    }
}
