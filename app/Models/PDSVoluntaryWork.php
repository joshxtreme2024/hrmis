<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDSVoluntaryWork extends Model
{
    protected $table = 'pds_voluntary_works';

    protected $fillable = [
        'user_id',
        'order',
        'organization_name',
        'organization_address',
        'inclusive_from',
        'inclusive_to',
        'number_of_hours',
        'position_nature_of_work',
    ];

    protected $casts = [
        'inclusive_from' => 'date',
        'inclusive_to' => 'date',
        'number_of_hours' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor for formatted hours
    public function getFormattedHoursAttribute()
    {
        if (is_null($this->number_of_hours) || $this->number_of_hours == 0) {
            return null;
        }
        
        if (floor($this->number_of_hours) == $this->number_of_hours) {
            return (int) $this->number_of_hours . ' hrs';
        }
        
        return number_format($this->number_of_hours, 1) . ' hrs';
    }

    // Accessor for date range display
    public function getDateRangeDisplayAttribute()
    {
        if (is_null($this->inclusive_from)) {
            return '—';
        }
        
        $from = $this->inclusive_from->format('M d, Y');
        
        if ($this->inclusive_to && $this->inclusive_to->format('Y-m-d') !== $this->inclusive_from->format('Y-m-d')) {
            return $from . ' - ' . $this->inclusive_to->format('M d, Y');
        }
        
        return $from;
    }

    // Check if work is ongoing
    public function getIsOngoingAttribute()
    {
        return is_null($this->inclusive_to);
    }

    // Get status badge
    public function getStatusBadgeAttribute()
    {
        if ($this->is_ongoing) {
            return 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400';
        }
        return 'bg-gray-100 dark:bg-gray-700/30 text-gray-700 dark:text-gray-400';
    }

    public function getStatusLabelAttribute()
    {
        return $this->is_ongoing ? 'Present' : 'Completed';
    }
}