<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDSTraining extends Model
{
    protected $table = 'pds_trainings';
    protected $fillable = [
        'user_id',
        'order',
        'title_of_program',
        'inclusive_from',
        'inclusive_to',
        'number_of_hours',
        'type_of_ld',
        'conducted_by',
    ];

    protected $casts = [
        'inclusive_from' => 'date',
        'inclusive_to' => 'date',
        'number_of_hours' => 'float',
    ];

    // Type badge color mapping
    public function getTypeBadgeColorAttribute()
    {
        $colors = [
            'managerial' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400',
            'supervisory' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
            'technical' => 'bg-cyan-100 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-400',
            'behavioral' => 'bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-400',
            'leadership' => 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400',
            'functional' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
            'other' => 'bg-gray-100 dark:bg-gray-700/30 text-gray-700 dark:text-gray-400',
        ];

        return $colors[strtolower($this->type_of_ld)] ?? 'bg-gray-100 dark:bg-gray-700/30 text-gray-700 dark:text-gray-400';
    }

    // Formatted type label
    public function getFormattedTypeAttribute()
    {
        if (is_null($this->type_of_ld)) {
            return null;
        }
        
        return ucfirst(str_replace('_', ' ', $this->type_of_ld));
    }

    // Check if type exists
    public function getHasTypeAttribute()
    {
        return !is_null($this->type_of_ld) && $this->type_of_ld !== '';
    }
}