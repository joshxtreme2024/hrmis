<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    /**
     * Show the admin login form
     */
    public function showLoginForm()
    {
        // Check if maintenance mode is enabled
        $maintenanceMode = Setting::get('maintenance_mode', false);
        
        if (!$maintenanceMode) {
            return redirect()->route('login');
        }

        return view('auth.admin-login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Attempt to login
        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $user = Auth::user();
            
            // Check if user has admin role
            if ($user->hasRole('admin') || $user->hasRole('super_admin')) {
                $request->session()->regenerate();
                
                // Set a session flag that user bypassed maintenance
                session(['bypassed_maintenance' => true]);
                
                return redirect()->intended('/dashboard');
            }

            // If not admin, logout and redirect back
            Auth::logout();
            return back()->withErrors([
                'email' => 'You do not have administrator privileges to access the system during maintenance.',
            ]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Show the maintenance notice
     */
    public function maintenanceNotice()
    {
        $message = Setting::get('maintenance_message', 'We are currently undergoing maintenance.');
        $companyName = Setting::get('company_name', 'HRMIS');
        
        return view('errors.maintenance', compact('message', 'companyName'));
    }
}