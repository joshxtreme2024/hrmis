<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveApplicationDate extends Model
{
    protected $fillable = ['leave_application_id', 'leave_date', 'day_fraction'];

    protected $casts = ['leave_date' => 'date', 'day_fraction' => 'decimal:2'];

    public function application()
    {
        return $this->belongsTo(LeaveApplication::class, 'leave_application_id');
    }
}