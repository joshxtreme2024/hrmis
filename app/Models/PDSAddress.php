<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDSAddress extends Model
{
    protected $table = 'pds_addresses';

    protected $fillable = [
        'user_id',
        'address_type',
        'hbl_number',
        'street',
        'subdi_village',
        'barangay',
        'city_municipality',
        'province',
        'zip_code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get full address as a string
    public function getFullAddressAttribute()
    {
        $parts = [];
        
        if ($this->hbl_number) {
            $parts[] = $this->hbl_number;
        }
        if ($this->street) {
            $parts[] = $this->street;
        }
        if ($this->subdi_village) {
            $parts[] = $this->subdi_village;
        }
        if ($this->barangay) {
            $parts[] = 'Brgy. ' . $this->barangay;
        }
        if ($this->city_municipality) {
            $parts[] = $this->city_municipality;
        }
        if ($this->province) {
            $parts[] = $this->province;
        }
        if ($this->zip_code) {
            $parts[] = $this->zip_code;
        }
        
        return implode(', ', $parts) ?: '—';
    }

    // Get address type badge color
    public function getBadgeColorAttribute()
    {
        $colors = [
            'present' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
            'permanent' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
            'provincial' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400',
            'other' => 'bg-gray-100 dark:bg-gray-700/30 text-gray-700 dark:text-gray-400',
        ];

        return $colors[$this->address_type] ?? $colors['other'];
    }
}