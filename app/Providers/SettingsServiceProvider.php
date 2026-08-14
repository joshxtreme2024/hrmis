<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->booted(function () {
            $this->loadSettings();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Load settings from database and merge with config
     */
    protected function loadSettings(): void
    {
        try {
            // Cache settings for 1 hour to reduce database queries
            $settings = Cache::remember('settings', 3600, function () {
                return Setting::all()->pluck('value', 'key')->toArray();
            });

            // Merge with config values
            foreach ($settings as $key => $value) {
                // Map database keys to config keys
                $configKey = $this->mapToConfigKey($key);
                
                if ($configKey) {
                    Config::set($configKey, $value);
                }

                // Also set as environment variable alternative
                $envKey = strtoupper($key);
                if (!empty($value)) {
                    putenv("{$envKey}={$value}");
                    $_ENV[$envKey] = $value;
                    $_SERVER[$envKey] = $value;
                }
            }
        } catch (\Exception $e) {
            // If table doesn't exist yet, silently fail
            // (useful during installation/migration)
        }
    }

    /**
     * Map database key to config key
     */
    protected function mapToConfigKey(string $key): ?string
    {
        $mapping = [
            // App config
            'app_name' => 'app.name',
            'app_url' => 'app.url',
            'app_locale' => 'app.locale',
            'timezone' => 'app.timezone',
            
            // Mail config
            'mail_driver' => 'mail.default',
            'mail_host' => 'mail.mailers.smtp.host',
            'mail_port' => 'mail.mailers.smtp.port',
            'mail_username' => 'mail.mailers.smtp.username',
            'mail_password' => 'mail.mailers.smtp.password',
            'mail_encryption' => 'mail.mailers.smtp.encryption',
            'mail_from_address' => 'mail.from.address',
            'mail_from_name' => 'mail.from.name',
            
            // You can add more mappings as needed
        ];

        return $mapping[$key] ?? null;
    }
}