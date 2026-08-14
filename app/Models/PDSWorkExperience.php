<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDSWorkExperience extends Model
{
    protected $table = 'pds_work_experiences';
    protected $fillable =[
        'user_id',
        'order',
        'inclusive_from',
        'inclusive_to',
        'position_title',
        'department_agency_office',
        'monthly_salary',
        'salary_grade',
        'status_of_appointment',
        'is_gov',
    ];

    protected $casts = [
        'inclusive_from' => 'date',
        'inclusive_to' => 'date',
        'monthly_salary' => 'decimal:2',
    ];
}
