<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class RefreshSettingsCache extends Command
{
    protected $signature = 'settings:refresh';
    protected $description = 'Refresh settings cache';

    public function handle()
    {
        // Clear cache
        Cache::forget('settings');
        
        // Reload settings
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        Cache::put('settings', $settings, 3600);
        
        $this->info('Settings cache refreshed successfully!');
        $this->table(['Key', 'Value'], collect($settings)->map(function ($value, $key) {
            return ['Key' => $key, 'Value' => $value];
        })->toArray());
    }
}