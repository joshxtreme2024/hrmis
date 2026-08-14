<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'app_name', 'value' => 'HRMIS', 'group' => 'general'],
            ['key' => 'app_url', 'value' => 'http://localhost', 'group' => 'general'],
            ['key' => 'app_locale', 'value' => 'en', 'group' => 'general'],
            ['key' => 'timezone', 'value' => 'Asia/Manila', 'group' => 'general'],
            ['key' => 'maintenance_mode', 'value' => false, 'group' => 'general'],
            ['key' => 'maintenance_message', 'value' => 'We are currently undergoing maintenance.', 'group' => 'general'],
            
            // Company
            ['key' => 'company_name', 'value' => 'Municipality of Aguinaldo', 'group' => 'company'],
            ['key' => 'company_address', 'value' => 'Aguinaldo, Ifugao, Philippines', 'group' => 'company'],
            ['key' => 'company_phone', 'value' => '', 'group' => 'company'],
            ['key' => 'company_email', 'value' => '', 'group' => 'company'],
            ['key' => 'company_website', 'value' => '', 'group' => 'company'],
            ['key' => 'company_tin', 'value' => '', 'group' => 'company'],
            ['key' => 'company_description', 'value' => 'Municipality of Aguinaldo - Human Resource Management System', 'group' => 'company'],
            
            // HR
            ['key' => 'employee_id_prefix', 'value' => 'EMP-', 'group' => 'hr'],
            ['key' => 'employee_id_format', 'value' => 'YYYY-XXXX', 'group' => 'hr'],
            ['key' => 'default_employee_status', 'value' => 'active', 'group' => 'hr'],
            ['key' => 'probationary_period', 'value' => 6, 'group' => 'hr'],
            ['key' => 'annual_leave_days', 'value' => 15, 'group' => 'hr'],
            ['key' => 'sick_leave_days', 'value' => 10, 'group' => 'hr'],
            ['key' => 'max_leave_days', 'value' => 30, 'group' => 'hr'],
            ['key' => 'leave_approval_required', 'value' => true, 'group' => 'hr'],
            
            // Payroll
            ['key' => 'payroll_frequency', 'value' => 'monthly', 'group' => 'payroll'],
            ['key' => 'payroll_currency', 'value' => 'PHP', 'group' => 'payroll'],
            ['key' => 'tax_rate', 'value' => 10, 'group' => 'payroll'],
            ['key' => 'sss_contribution', 'value' => 4.5, 'group' => 'payroll'],
            ['key' => 'philhealth_contribution', 'value' => 3.5, 'group' => 'payroll'],
            ['key' => 'pagibig_contribution', 'value' => 2, 'group' => 'payroll'],
            
            // Security
            ['key' => 'password_min_length', 'value' => 8, 'group' => 'security'],
            ['key' => 'password_expiry_days', 'value' => 90, 'group' => 'security'],
            ['key' => 'max_login_attempts', 'value' => 5, 'group' => 'security'],
            ['key' => 'session_timeout', 'value' => 60, 'group' => 'security'],
            ['key' => 'two_factor_auth', 'value' => false, 'group' => 'security'],
            ['key' => 'account_lockout', 'value' => true, 'group' => 'security'],
            
            // Branding
            ['key' => 'logo', 'value' => null, 'group' => 'branding'],
            ['key' => 'favicon', 'value' => null, 'group' => 'branding'],
        ];

        foreach ($settings as $setting) {
            Setting::create([
                'key' => $setting['key'],
                'value' => $setting['value'],
                'group' => $setting['group'],
                'type' => is_array($setting['value']) ? 'json' : gettype($setting['value']),
            ]);
        }
    }
}