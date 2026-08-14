<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDSGovernmentId extends Model
{
    protected $table = 'pds_government_ids';

    protected $fillable = [
        'user_id',
        'umid_number',
        'pagibig_number',
        'philhealth_number',
        'philsys_number',
        'tin_number',
        'sss_number',
        'gsis_number',
        'dl_number',
        'passport_number',
        'prc_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Format helpers for display
    public function getFormattedUmidAttribute()
    {
        if (empty($this->umid_number)) {
            return '—';
        }
        return $this->umid_number;
    }

    public function getFormattedPagibigAttribute()
    {
        if (empty($this->pagibig_number)) {
            return '—';
        }
        return $this->pagibig_number;
    }

    public function getFormattedPhilhealthAttribute()
    {
        if (empty($this->philhealth_number)) {
            return '—';
        }
        return $this->philhealth_number;
    }

    public function getFormattedPhilsysAttribute()
    {
        if (empty($this->philsys_number)) {
            return '—';
        }
        return $this->philsys_number;
    }

    public function getFormattedTinAttribute()
    {
        if (empty($this->tin_number)) {
            return '—';
        }
        return $this->tin_number;
    }

    public function getFormattedSssAttribute()
    {
        if (empty($this->sss_number)) {
            return '—';
        }
        return $this->sss_number;
    }

    public function getFormattedGsisAttribute()
    {
        if (empty($this->gsis_number)) {
            return '—';
        }
        return $this->gsis_number;
    }

    // Count filled IDs
    public function getFilledCountAttribute()
    {
        $fields = [
            'umid_number',
            'pagibig_number',
            'philhealth_number',
            'philsys_number',
            'tin_number',
            'sss_number',
        ];
        
        $count = 0;
        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $count++;
            }
        }
        return $count;
    }

    public function getTotalRequiredAttribute()
    {
        return 6; // UMID, PAG-IBIG, PhilHealth, PhilSys, TIN, SSS
    }

    public function getCompletionPercentageAttribute()
    {
        return round(($this->filled_count / $this->total_required) * 100);
    }

    // Check if all required IDs are filled
    public function getIsCompleteAttribute()
    {
        return $this->filled_count === $this->total_required;
    }
}