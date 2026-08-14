<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDSReference extends Model
{
    protected $table = 'pds_references';

    protected $fillable = [
        'user_id',
        'order',
        'name',
        'occupation',
        'contact_number',
        'address',
        'email',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Format contact number (e.g., 09171234567 -> 0917-123-4567)
    public function getFormattedContactAttribute()
    {
        $contact = $this->contact_number;
        
        // Remove any non-numeric characters
        $clean = preg_replace('/[^0-9]/', '', $contact);
        
        // If it's a 11-digit mobile number (Philippines)
        if (strlen($clean) === 11 && substr($clean, 0, 2) === '09') {
            return substr($clean, 0, 4) . '-' . substr($clean, 4, 3) . '-' . substr($clean, 7);
        }
        
        // If it's a 10-digit mobile number
        if (strlen($clean) === 10) {
            return substr($clean, 0, 3) . '-' . substr($clean, 3, 3) . '-' . substr($clean, 6);
        }
        
        // Return original if format is unknown
        return $contact;
    }

    // Get initials for avatar
    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->name);
        $initials = '';
        
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
        }
        
        return $initials;
    }

    // Get full name in reverse order (Last, First)
    public function getReverseNameAttribute()
    {
        $parts = explode(' ', $this->name);
        $last = array_pop($parts);
        $first = implode(' ', $parts);
        
        return $last . ', ' . $first;
    }

    // Check if contact is valid
    public function getIsContactValidAttribute()
    {
        $clean = preg_replace('/[^0-9]/', '', $this->contact_number);
        $length = strlen($clean);
        
        // Valid Philippine mobile numbers: 10-11 digits
        return $length >= 10 && $length <= 11;
    }

    // Get contact type (Mobile/Landline)
    public function getContactTypeAttribute()
    {
        $clean = preg_replace('/[^0-9]/', '', $this->contact_number);
        
        if (strlen($clean) >= 10 && substr($clean, 0, 2) === '09') {
            return 'Mobile';
        }
        
        if (strlen($clean) >= 7) {
            return 'Landline';
        }
        
        return 'Unknown';
    }

    // Check if email is valid
    public function getIsEmailValidAttribute()
    {
        if (empty($this->email)) {
            return null;
        }
        
        return filter_var($this->email, FILTER_VALIDATE_EMAIL) !== false;
    }

    // Get masked email (for privacy)
    public function getMaskedEmailAttribute()
    {
        if (empty($this->email)) {
            return null;
        }
        
        $parts = explode('@', $this->email);
        $name = $parts[0];
        $domain = $parts[1] ?? '';
        
        if (strlen($name) <= 2) {
            return $this->email;
        }
        
        $masked = substr($name, 0, 2) . str_repeat('*', strlen($name) - 2);
        return $masked . '@' . $domain;
    }

    // Get full address with line breaks
    public function getFormattedAddressAttribute()
    {
        if (empty($this->address)) {
            return null;
        }
        
        return nl2br(e($this->address));
    }
}