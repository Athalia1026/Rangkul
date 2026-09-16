<?php

namespace App\Console\Commands;

use App\Mail\PremiumExpiringMail;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendPremiumExpiryReminders extends Command
{
    protected $signature = 'premium:send-expiry-reminders';
    protected $description = 'Send reminders for premium subscriptions expiring soon';

    public function handle(): int
    {
        Subscription::with('company.donor.user')
            ->where('status', 'aktif')
            ->whereBetween('expired_at', [now(), now()->addDays(7)])
            ->whereNull('reminder_sent_at')
            ->each(function (Subscription $subscription) {
                $email = $subscription->company?->donor?->user?->email
                    ?? $subscription->company?->email_korporat;

                if ($email) {
                    Mail::to($email)->send(new PremiumExpiringMail($subscription));
                    $subscription->update(['reminder_sent_at' => now()]);
                }
            });

        return self::SUCCESS;
    }
}
