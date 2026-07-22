<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Positions extends Model
{
    protected $table = 'positions';

    protected $fillable = [
        'title',
        'department_id',
        'job_level_id',
        'description',
        'status',
        'salary_grade',
        'reports_to_id'
    ];

    public function department(){
        return $this->belongsTo(Departments::class, 'department_id');
    }

    public function employees()
    {
        return $this->hasMany(PersonalDataSheets::class, 'position_id');
    }

    public function jobLevel()
    {
        return $this->belongsTo(JobLevels::class, 'job_level_id');
    }

    public function reportsTo()
    {
        return $this->belongsTo(Positions::class, 'reports_to_id');
    }

    public function subordinates()
    {
        return $this->hasMany(Positions::class, 'reports_to_id');
    }
}
