<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;

class MaintenanceMode extends Command
{
    protected $signature = 'maintenance:mode {mode? : on/off} {--message= : Maintenance message} {--time= : Estimated return time}';
    protected $description = 'Enable or disable maintenance mode';

    public function handle()
    {
        $mode = $this->argument('mode');
        
        if (!$mode) {
            $this->showStatus();
            return;
        }

        if ($mode === 'on') {
            $this->enableMaintenance();
        } elseif ($mode === 'off') {
            $this->disableMaintenance();
        } else {
            $this->error('Invalid mode. Use "on" or "off".');
            return 1;
        }

        return 0;
    }

    protected function showStatus()
    {
        $status = Setting::get('maintenance_mode', false);
        $message = Setting::get('maintenance_message', 'No message set');
        
        $this->info('Maintenance Mode: ' . ($status ? 'ON' : 'OFF'));
        $this->info('Message: ' . $message);
    }

    protected function enableMaintenance()
    {
        Setting::set('maintenance_mode', true);
        
        if ($this->option('message')) {
            Setting::set('maintenance_message', $this->option('message'));
        }
        
        if ($this->option('time')) {
            Setting::set('maintenance_estimated_return', $this->option('time'));
        }
        
        $this->info('Maintenance mode enabled!');
        $this->info('Message: ' . Setting::get('maintenance_message'));
    }

    protected function disableMaintenance()
    {
        Setting::set('maintenance_mode', false);
        $this->info('Maintenance mode disabled!');
    }
}