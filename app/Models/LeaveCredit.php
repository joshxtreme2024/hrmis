<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveCredit extends Model
{
    protected $table = 'leave_credits';

    protected $fillable = [
        'emp_id',
        'leave_type_id',
        'total_earned',
        'total_used',
        'as_of_date',
    ];

    public function employee()    { return $this->belongsTo(PDSPersonalData::class, 'emp_id'); }
    public function leaveType()   { return $this->belongsTo(LeaveType::class, 'leave_type_id'); }
    public function leaveApplication()  { return $this->belongsTo(LeaveApplication::class, 'leave_application_id'); }

    public function leaveBalance()
    {
        return (float) $this->total_earned
             - (float) $this->total_used;
    }
}
