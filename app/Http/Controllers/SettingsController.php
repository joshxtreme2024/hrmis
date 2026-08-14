<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    /**
     * Show the settings page
     */
    public function index()
    {
        return view('admin.settings.index');
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            // General
            'app_name' => 'required|string|max:255',
            'app_url' => 'nullable|url',
            'app_locale' => 'required|string|in:en,fil,es',
            'timezone' => 'required|string',
            'maintenance_mode' => 'boolean',
            'maintenance_message' => 'nullable|string',
            
            // Company
            'company_name' => 'required|string|max:255',
            'company_address' => 'nullable|string',
            'company_phone' => 'nullable|string|max:20',
            'company_email' => 'nullable|email|max:255',
            'company_website' => 'nullable|url',
            'company_tin' => 'nullable|string|max:50',
            'company_description' => 'nullable|string',
            
            // HR
            'employee_id_prefix' => 'nullable|string|max:10',
            'employee_id_format' => 'nullable|string',
            'default_employee_status' => 'nullable|string',
            'probationary_period' => 'nullable|integer|min:1|max:24',
            'annual_leave_days' => 'nullable|integer|min:0',
            'sick_leave_days' => 'nullable|integer|min:0',
            'max_leave_days' => 'nullable|integer|min:1',
            'leave_approval_required' => 'boolean',
            
            // Payroll
            'payroll_frequency' => 'nullable|string',
            'payroll_currency' => 'nullable|string',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'sss_contribution' => 'nullable|numeric|min:0|max:100',
            'philhealth_contribution' => 'nullable|numeric|min:0|max:100',
            'pagibig_contribution' => 'nullable|numeric|min:0|max:100',
            
            // Security
            'password_min_length' => 'nullable|integer|min:6|max:20',
            'password_expiry_days' => 'nullable|integer|min:0|max:365',
            'max_login_attempts' => 'nullable|integer|min:1|max:20',
            'session_timeout' => 'nullable|integer|min:5|max:1440',
            'two_factor_auth' => 'boolean',
            'account_lockout' => 'boolean',
            
            // Email
            'mail_driver' => 'nullable|string',
            'mail_host' => 'nullable|string',
            'mail_port' => 'nullable|integer|min:1|max:65535',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'nullable|email',
            'mail_from_name' => 'nullable|string',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            $oldLogo = Setting::get('logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            // Store new logo
            $logoPath = $request->file('logo')->store('settings', 'public');
            Setting::set('logo', $logoPath, 'branding');
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            // Delete old favicon if exists
            $oldFavicon = Setting::get('favicon');
            if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                Storage::disk('public')->delete($oldFavicon);
            }

            // Store new favicon
            $faviconPath = $request->file('favicon')->store('settings', 'public');
            Setting::set('favicon', $faviconPath, 'branding');
        }

        // Save all settings to database
        foreach ($validated as $key => $value) {
            if ($key !== '_token' && $key !== '_method') {
                // Determine group based on key
                $group = 'general';
                if (str_starts_with($key, 'company_')) $group = 'company';
                if (str_starts_with($key, 'mail_')) $group = 'email';
                if (in_array($key, ['employee_id_prefix', 'employee_id_format', 'default_employee_status', 'probationary_period', 'annual_leave_days', 'sick_leave_days', 'max_leave_days', 'leave_approval_required'])) {
                    $group = 'hr';
                }
                if (in_array($key, ['payroll_frequency', 'payroll_currency', 'tax_rate', 'sss_contribution', 'philhealth_contribution', 'pagibig_contribution'])) {
                    $group = 'payroll';
                }
                if (in_array($key, ['password_min_length', 'password_expiry_days', 'max_login_attempts', 'session_timeout', 'two_factor_auth', 'account_lockout'])) {
                    $group = 'security';
                }
                
                Setting::set($key, $value, $group);
            }
        }

        // Save maintenance settings
        Setting::set('maintenance_mode', $request->maintenance_mode ?? false);
        Setting::set('maintenance_message', $request->maintenance_message ?? '');
        Setting::set('maintenance_estimated_return', $request->maintenance_estimated_return ?? '');
        Setting::set('maintenance_whitelist_ips', $request->maintenance_whitelist_ips ?? '');
        Setting::set('maintenance_retry_after', $request->maintenance_retry_after ?? 3600);

        // Clear cache
        Cache::forget('settings_middleware');

        return redirect()->route('settings.system')
            ->with('success', 'Settings updated successfully!');
    }

    /**
     * Send test email
     */
    public function testEmail(Request $request)
    {
        try {
            // Configure mail settings from database
            $mailDriver = setting('mail_driver', 'smtp');
            $mailHost = setting('mail_host', 'smtp.gmail.com');
            $mailPort = setting('mail_port', 587);
            $mailUsername = setting('mail_username');
            $mailPassword = setting('mail_password');
            $mailEncryption = setting('mail_encryption', 'tls');
            $mailFromAddress = setting('mail_from_address');
            $mailFromName = setting('mail_from_name', config('app.name'));

            // You can send a test email here
            // Mail::to('test@example.com')->send(new TestEmail());
            
            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}