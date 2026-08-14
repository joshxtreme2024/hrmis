<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CheckMaintenance
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip if in console
        if (app()->runningInConsole()) {
            return $next($request);
        }

        // Check if maintenance mode is enabled
        $maintenanceMode = $this->isMaintenanceMode();
        
        if (!$maintenanceMode) {
            return $next($request);
        }

        // Check if user can bypass maintenance
        if ($this->canBypassMaintenance($request)) {
            return $next($request);
        }

        // Check if current route should bypass maintenance
        if ($this->shouldBypassRoute($request)) {
            return $next($request);
        }

        // Render maintenance page
        return $this->renderMaintenancePage($request);
    }

    /**
     * Check if maintenance mode is enabled
     */
    protected function isMaintenanceMode(): bool
    {
        try {
            $mode = Setting::get('maintenance_mode', false);
            return $mode === true || $mode === '1' || $mode === 1;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if user can bypass maintenance
     */
    protected function canBypassMaintenance(Request $request): bool
    {
        // Check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user has admin role
            if ($user->hasRole('admin') || $user->hasRole('super_admin')) {
                return true;
            }
            
            // Check if user has specific permission
            if ($user->can('bypass-maintenance')) {
                return true;
            }
        }

        // Check if IP is whitelisted
        $whitelist = Setting::get('maintenance_whitelist_ips', '');
        if (!empty($whitelist)) {
            $ips = array_map('trim', explode(',', $whitelist));
            if (in_array($request->ip(), $ips)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if current route should bypass maintenance
     */
    protected function shouldBypassRoute(Request $request): bool
    {
        $currentPath = $request->path();
        
        // Always allow these routes - ADDED admin-login here
        $allowedRoutes = [
            'login',
            'admin-login',        // ← ADD THIS
            'admin/login',        // ← ADD THIS
            'logout',
            'register',
            'password/*',
            'password/reset',
            'password/email',
            'maintenance',
            'maintenance-status',
            'admin/settings',
            'api/*',
            '_debugbar/*',        // For debugging
            'telescope/*',        // For telescope if installed
        ];

        foreach ($allowedRoutes as $route) {
            if ($request->is($route)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Render maintenance page
     */
    protected function renderMaintenancePage(Request $request)
    {
        $message = $this->getMaintenanceMessage();
        $companyName = Setting::get('company_name', 'HRMIS');
        $contactEmail = Setting::get('company_email', '');
        $contactPhone = Setting::get('company_phone', '');
        $retryAfter = Setting::get('maintenance_retry_after', 3600);
        $estimatedReturn = Setting::get('maintenance_estimated_return', '');

        // Check if JSON response is requested
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => 'error',
                'message' => $message,
                'code' => 503,
                'data' => [
                    'retry_after' => $retryAfter,
                    'estimated_return' => $estimatedReturn,
                    'contact_email' => $contactEmail,
                    'contact_phone' => $contactPhone,
                ]
            ], 503);
        }

        // Render maintenance view
        return response()->view('errors.maintenance', [
            'message' => $message,
            'companyName' => $companyName,
            'contactEmail' => $contactEmail,
            'contactPhone' => $contactPhone,
            'retryAfter' => $retryAfter,
            'estimatedReturn' => $estimatedReturn,
        ], 503);
    }

    /**
     * Get maintenance message with fallbacks
     */
    protected function getMaintenanceMessage(): string
    {
        $message = Setting::get('maintenance_message');
        
        if (empty($message)) {
            $message = config('settings.maintenance_message');
        }
        
        if (empty($message)) {
            $message = 'We are currently undergoing scheduled maintenance. Please check back soon.';
        }
        
        return $message;
    }
}