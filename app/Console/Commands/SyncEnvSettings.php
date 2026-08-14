<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SyncEnvSettings extends Command
{
    protected $signature = 'settings:sync-env {--dry-run : Preview changes without writing}';
    protected $description = 'Sync database settings with .env file';

    public function handle()
    {
        $this->info('Syncing settings with .env...');
        
        // Get all settings
        $settings = Setting::all();
        
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            $this->error('.env file not found!');
            return 1;
        }
        
        $envContent = file_get_contents($envPath);
        $updated = 0;
        $changes = [];
        
        foreach ($settings as $setting) {
            // Map to ENV keys
            $envKey = strtoupper($setting->key);
            
            // Skip if setting is null or empty
            if (is_null($setting->value) || $setting->value === '') {
                continue;
            }
            
            // Format the value for .env file
            $envValue = $this->formatEnvValue($setting->value);
            $replacement = "{$envKey}={$envValue}";
            
            $pattern = "/^{$envKey}=.*$/m";
            
            if (preg_match($pattern, $envContent)) {
                // Get current value for comparison
                preg_match($pattern, $envContent, $matches);
                $currentLine = $matches[0] ?? '';
                
                if ($currentLine !== $replacement) {
                    $envContent = preg_replace($pattern, $replacement, $envContent);
                    $changes[] = "Updated {$envKey}={$envValue}";
                    $updated++;
                }
            } else {
                $envContent .= "\n{$replacement}";
                $changes[] = "Added {$envKey}={$envValue}";
                $updated++;
            }
        }
        
        // Show changes
        if ($this->option('dry-run')) {
            $this->info('Dry run - changes to be made:');
            foreach ($changes as $change) {
                $this->line($change);
            }
            $this->info("Total changes: {$updated}");
            return 0;
        }
        
        // Save .env file
        if ($updated > 0) {
            file_put_contents($envPath, $envContent);
            $this->info("Settings synced successfully! ({$updated} settings updated)");
            
            // Show changes
            foreach ($changes as $change) {
                $this->line($change);
            }
        } else {
            $this->info('No changes needed. All settings are up to date.');
        }
        
        // Reload config
        \Artisan::call('config:clear');
        \Artisan::call('config:cache');
        
        return 0;
    }

    /**
     * Format value for .env file
     */
    protected function formatEnvValue($value): string
    {
        // Convert boolean to string
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        
        // Handle null
        if (is_null($value)) {
            return '';
        }
        
        $stringValue = (string) $value;
        
        // If value is empty, return empty string
        if ($stringValue === '') {
            return '';
        }
        
        // Check if value needs quoting
        $needsQuoting = false;
        
        // Check for spaces
        if (str_contains($stringValue, ' ')) {
            $needsQuoting = true;
        }
        
        // Check for special characters
        if (preg_match('/[#\'"$<>`|&;]/', $stringValue)) {
            $needsQuoting = true;
        }
        
        // Check if it's a number (don't quote numbers)
        if (is_numeric($stringValue) && !str_contains($stringValue, ' ')) {
            return $stringValue;
        }
        
        // Check for boolean-like values
        if (in_array(strtolower($stringValue), ['true', 'false', 'null', 'yes', 'no', 'on', 'off'])) {
            return strtolower($stringValue);
        }
        
        // Quote if needed
        if ($needsQuoting) {
            // Escape existing quotes and backslashes
            $stringValue = str_replace('\\', '\\\\', $stringValue);
            $stringValue = str_replace('"', '\"', $stringValue);
            return '"' . $stringValue . '"';
        }
        
        return $stringValue;
    }
}