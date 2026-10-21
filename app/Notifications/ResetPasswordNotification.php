<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Email reset password lewat antrean. Selain tidak menahan request saat SMTP lambat,
 * waktu respons lupa password jadi sama untuk email terdaftar maupun tidak
 * (tanpa antrean, email terdaftar terasa lebih lambat karena menunggu SMTP).
 * URL reset tetap dibuat oleh ResetPassword::createUrlUsing di AppServiceProvider.
 */
class ResetPasswordNotification extends ResetPassword implements ShouldQueue
{
    use Queueable;
}
