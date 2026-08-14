<?php

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (!function_exists('settings')) {
    function settings($group = null)
    {
        if ($group) {
            return \App\Models\Setting::where('group', $group)->pluck('value', 'key')->toArray();
        }
        return \App\Models\Setting::all()->pluck('value', 'key')->toArray();
    }
}

if (!function_exists('app_setting')) {
    function app_setting($key, $default = null)
    {
        // Try to get from database first
        $dbValue = setting($key);
        
        if ($dbValue !== null) {
            return $dbValue;
        }
        
        // Fallback to config
        return config($key, $default);
    }
}