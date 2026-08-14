<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key', 
        'value', 
        'type', 
        'group'
    ];
    
    protected $casts = [
        'value' => 'json',
    ];

    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value
     */
    public static function set($key, $value, $group = 'general')
    {
        $type = is_array($value) ? 'json' : gettype($value);
        
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'type' => $type
            ]
        );
    }

    /**
     * Get all settings grouped by group
     */
    public static function getAllGrouped()
    {
        return static::all()->groupBy('group')->map(function ($items) {
            return $items->pluck('value', 'key');
        });
    }
}