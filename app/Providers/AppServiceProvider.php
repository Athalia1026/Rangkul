<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use Illuminate\Auth\Notifications\ResetPassword;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
       
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return 'http://127.0.0.1:8000/reset-password?token=' . $token . '&email=' . $notifiable->getEmailForPasswordReset();
        });
        
    }
}
