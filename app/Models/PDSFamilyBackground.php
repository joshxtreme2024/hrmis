<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDSFamilyBackground extends Model
{
    protected $table = 'pds_family_background';
    
    protected $fillable = [
        'user_id',
        'spouse_first_name',
        'spouse_middle_name',
        'spouse_last_name',
        'spouse_name_extension',
        'spouse_occupation',
        'spouse_employer_business',
        'spouse_business_address',
        'spouse_telephone_no',
        'father_first_name',
        'father_middle_name',
        'father_last_name',
        'father_name_extension',
        'mother_first_name',
        'mother_middle_name',
        'mother_last_name',
        'mother_maiden_last_name',
    ];

    // ===================== RELATIONSHIPS =====================
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ===================== SPOUSE ACCESSORS =====================
    
    /**
     * Get spouse's full name
     */
    public function getSpouseFullNameAttribute()
    {
        $parts = [];
        
        if ($this->spouse_first_name) {
            $parts[] = $this->spouse_first_name;
        }
        if ($this->spouse_middle_name) {
            $parts[] = $this->spouse_middle_name;
        }
        if ($this->spouse_last_name) {
            $parts[] = $this->spouse_last_name;
        }
        if ($this->spouse_name_extension) {
            $parts[] = $this->spouse_name_extension;
        }
        
        return implode(' ', $parts) ?: 'N/A';
    }

    /**
     * Get spouse's first name and last name (short version)
     */
    public function getSpouseShortNameAttribute()
    {
        if ($this->spouse_first_name && $this->spouse_last_name) {
            return $this->spouse_first_name . ' ' . $this->spouse_last_name;
        }
        return $this->spouse_full_name;
    }

    /**
     * Get spouse's initials
     */
    public function getSpouseInitialsAttribute()
    {
        $initials = '';
        
        if ($this->spouse_first_name) {
            $initials .= substr($this->spouse_first_name, 0, 1);
        }
        if ($this->spouse_last_name) {
            $initials .= substr($this->spouse_last_name, 0, 1);
        }
        
        return strtoupper($initials) ?: 'N/A';
    }

    /**
     * Get spouse's complete information
     */
    public function getSpouseCompleteInfoAttribute()
    {
        $info = [];
        
        $fullName = $this->spouse_full_name;
        if ($fullName !== 'N/A') {
            $info[] = 'Name: ' . $fullName;
        }
        
        if ($this->spouse_occupation) {
            $info[] = 'Occupation: ' . $this->spouse_occupation;
        }
        
        if ($this->spouse_employer_business) {
            $info[] = 'Employer: ' . $this->spouse_employer_business;
        }
        
        if ($this->spouse_business_address) {
            $info[] = 'Business Address: ' . $this->spouse_business_address;
        }
        
        if ($this->spouse_telephone_no) {
            $info[] = 'Telephone: ' . $this->spouse_telephone_no;
        }
        
        return implode(' | ', $info) ?: 'No spouse information available';
    }

    /**
     * Check if spouse information exists
     */
    public function getHasSpouseAttribute()
    {
        return !empty($this->spouse_first_name) || !empty($this->spouse_last_name);
    }

    // ===================== FATHER ACCESSORS =====================
    
    /**
     * Get father's full name
     */
    public function getFatherFullNameAttribute()
    {
        $parts = [];
        
        if ($this->father_first_name) {
            $parts[] = $this->father_first_name;
        }
        if ($this->father_middle_name) {
            $parts[] = $this->father_middle_name;
        }
        if ($this->father_last_name) {
            $parts[] = $this->father_last_name;
        }
        if ($this->father_name_extension) {
            $parts[] = $this->father_name_extension;
        }
        
        return implode(' ', $parts) ?: 'N/A';
    }

    /**
     * Get father's first name and last name (short version)
     */
    public function getFatherShortNameAttribute()
    {
        if ($this->father_first_name && $this->father_last_name) {
            return $this->father_first_name . ' ' . $this->father_last_name;
        }
        return $this->father_full_name;
    }

    /**
     * Get father's initials
     */
    public function getFatherInitialsAttribute()
    {
        $initials = '';
        
        if ($this->father_first_name) {
            $initials .= substr($this->father_first_name, 0, 1);
        }
        if ($this->father_middle_name) {
            $initials .= substr($this->father_middle_name, 0, 1);
        }
        if ($this->father_last_name) {
            $initials .= substr($this->father_last_name, 0, 1);
        }
        
        return strtoupper($initials) ?: 'N/A';
    }

    // ===================== MOTHER ACCESSORS =====================
    
    /**
     * Get mother's full name
     */
    public function getMotherFullNameAttribute()
    {
        $parts = [];
        
        if ($this->mother_first_name) {
            $parts[] = $this->mother_first_name;
        }
        if ($this->mother_middle_name) {
            $parts[] = $this->mother_middle_name;
        }
        if ($this->mother_last_name) {
            $parts[] = $this->mother_last_name;
        }
        
        return implode(' ', $parts) ?: 'N/A';
    }

    /**
     * Get mother's maiden full name
     */
    public function getMotherMaidenNameAttribute()
    {
        $parts = [];
        
        if ($this->mother_first_name) {
            $parts[] = $this->mother_first_name;
        }
        if ($this->mother_middle_name) {
            $parts[] = $this->mother_middle_name;
        }
        if ($this->mother_maiden_last_name) {
            $parts[] = $this->mother_maiden_last_name;
        }
        
        return implode(' ', $parts) ?: 'N/A';
    }

    /**
     * Get mother's first name and last name (short version)
     */
    public function getMotherShortNameAttribute()
    {
        if ($this->mother_first_name && $this->mother_last_name) {
            return $this->mother_first_name . ' ' . $this->mother_last_name;
        }
        return $this->mother_full_name;
    }

    /**
     * Get mother's initials
     */
    public function getMotherInitialsAttribute()
    {
        $initials = '';
        
        if ($this->mother_first_name) {
            $initials .= substr($this->mother_first_name, 0, 1);
        }
        if ($this->mother_last_name) {
            $initials .= substr($this->mother_last_name, 0, 1);
        }
        
        return strtoupper($initials) ?: 'N/A';
    }

    // ===================== FAMILY SUMMARY ACCESSORS =====================
    
    /**
     * Get complete family information
     */
    public function getFamilySummaryAttribute()
    {
        return [
            'spouse' => [
                'name' => $this->spouse_full_name,
                'occupation' => $this->spouse_occupation,
                'employer' => $this->spouse_employer_business,
                'business_address' => $this->spouse_business_address,
                'telephone' => $this->spouse_telephone_no,
                'has_spouse' => $this->has_spouse,
            ],
            'father' => [
                'name' => $this->father_full_name,
                'initials' => $this->father_initials,
            ],
            'mother' => [
                'name' => $this->mother_full_name,
                'maiden_name' => $this->mother_maiden_name,
                'initials' => $this->mother_initials,
            ],
        ];
    }

    /**
     * Get formatted family background for display
     */
    public function getFormattedFamilyAttribute()
    {
        $html = '<div class="space-y-3">';
        
        // Spouse
        if ($this->has_spouse) {
            $html .= '<div class="bg-blue-50 rounded-lg p-3">';
            $html .= '<p class="font-semibold text-blue-700">Spouse</p>';
            $html .= '<p class="text-sm">' . e($this->spouse_full_name) . '</p>';
            if ($this->spouse_occupation) {
                $html .= '<p class="text-xs text-gray-600">Occupation: ' . e($this->spouse_occupation) . '</p>';
            }
            if ($this->spouse_employer_business) {
                $html .= '<p class="text-xs text-gray-600">Employer: ' . e($this->spouse_employer_business) . '</p>';
            }
            $html .= '</div>';
        }
        
        // Father
        $html .= '<div class="bg-gray-50 rounded-lg p-3">';
        $html .= '<p class="font-semibold text-gray-700">Father</p>';
        $html .= '<p class="text-sm">' . e($this->father_full_name) . '</p>';
        $html .= '</div>';
        
        // Mother
        $html .= '<div class="bg-gray-50 rounded-lg p-3">';
        $html .= '<p class="font-semibold text-gray-700">Mother</p>';
        $html .= '<p class="text-sm">' . e($this->mother_full_name) . '</p>';
        if ($this->mother_maiden_last_name) {
            $html .= '<p class="text-xs text-gray-600">Maiden Name: ' . e($this->mother_maiden_name) . '</p>';
        }
        $html .= '</div>';
        
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Check if any family information exists
     */
    public function getHasFamilyInformationAttribute()
    {
        return $this->has_spouse || 
               $this->father_full_name !== 'N/A' || 
               $this->mother_full_name !== 'N/A';
    }
}