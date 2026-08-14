<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDSEligibility extends Model
{
    protected $table ='pds_eligibilities';
    protected $fillable = [
        'user_id',
        'order',
        'career_service',
        'rating',
        'examination_date',
        'examination_place',
        'license_number',
        'license_date_validity',
    ];

    protected $casts = [
        'examination_date' => 'date',
        'license_date_validity' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor for formatted rating
    public function getFormattedRatingAttribute()
    {
        if (is_null($this->rating)) {
            return null;
        }
        return $this->rating . '%';
    }

    // Accessor for license validity status
    public function getIsLicenseValidAttribute()
    {
        if (is_null($this->license_date_validity)) {
            return null;
        }
        return $this->license_date_validity->isFuture();
    }

    // Accessor for license status badge
    public function getLicenseStatusBadgeAttribute()
    {
        $valid = $this->is_license_valid;
        
        if (is_null($valid)) {
            return null;
        }
        
        return $valid 
            ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400'
            : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400';
    }

    public function getLicenseStatusLabelAttribute()
    {
        $valid = $this->is_license_valid;
        
        if (is_null($valid)) {
            return null;
        }
        
        return $valid ? 'Valid' : 'Expired';
    }
}