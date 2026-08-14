<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDSEducation extends Model
{
    protected $table = 'pds_education';
    protected $fillable = [
        'user_id',
        'order',
        'level',
        'school_name',
        'degree_course',
        'period_from',
        'period_to',
        'highest_level_earned',
        'year_graduated',
        'scholarship_honors',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
