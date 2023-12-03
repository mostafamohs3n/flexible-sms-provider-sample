<?php

namespace App\Providers;

use App\Interfaces\SmsProvider;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $currentSmsProvider = config('sms.config.current');
        $this->app->bind(SmsProvider::class, config("sms.{$currentSmsProvider}.provider"));
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
