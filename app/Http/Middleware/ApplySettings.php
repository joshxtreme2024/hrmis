<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;

class ApplySettings
{
    public function handle(Request $request, Closure $next)
    {
        try {
            // Get settings from cache or database
            $settings = Cache::remember('settings_middleware', 3600, function () {
                return Setting::all()->pluck('value', 'key')->toArray();
            });

            // Apply settings for current request
            foreach ($settings as $key => $value) {
                // Set locale if changed
                if ($key === 'app_locale' && $value !== config('app.locale')) {
                    app()->setLocale($value);
                }
                
                // Set timezone if changed
                if ($key === 'timezone' && $value !== config('app.timezone')) {
                    date_default_timezone_set($value);
                    config(['app.timezone' => $value]);
                }
            }

            // Check maintenance mode
            if (setting('maintenance_mode', false) && !$request->is('login*') && !$request->is('admin*')) {
                $message = setting('maintenance_message') ?: 'We are currently undergoing maintenance. Please check back soon.';
                abort(503, $message);
            }

        } catch (\Exception $e) {
            // Silently fail if settings table doesn't exist
        }

        return $next($request);
    }
}