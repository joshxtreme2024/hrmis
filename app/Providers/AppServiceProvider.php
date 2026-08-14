<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Set locale from database
        $locale = setting('app_locale', config('app.locale', 'en'));
        app()->setLocale($locale);
        
        // Set timezone from database
        $timezone = setting('timezone', config('app.timezone', 'UTC'));
        date_default_timezone_set($timezone);
        Config::set('app.timezone', $timezone);
    }
}