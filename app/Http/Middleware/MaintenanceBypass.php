<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceBypass
{
    public function handle(Request $request, Closure $next)
    {
        // If user bypassed maintenance via admin login
        if (session('bypassed_maintenance') && Auth::check()) {
            return $next($request);
        }
        
        // Check if user is admin
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            return $next($request);
        }
        
        // Redirect to admin login
        return redirect()->route('admin.login');
    }
}