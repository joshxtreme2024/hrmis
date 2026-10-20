<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Support\EmployeeProfile;

class LeaveApplication extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'date_of_filing'        => 'date',
        'date_from'             => 'date',
        'date_to'               => 'date',
        'credits_as_of'         => 'date',
        'certified_at'          => 'datetime',
        'recommended_at'        => 'datetime',
        'decided_at'            => 'datetime',
        'commutation_requested' => 'boolean',
        'working_days'          => 'float',
    ];

    public const STATUSES = [
        'draft'       => 'Draft',
        'pending'     => 'Pending',
        'certified'   => 'Credits certified',
        'recommended' => 'Recommended',
        'approved'    => 'Approved',
        'disapproved' => 'Disapproved',
        'cancelled'   => 'Cancelled',
    ];

    /* ---- Relationships (Department namespace may need adjusting) ---- */
    public function employee()    { return $this->belongsTo(PDSPersonalData::class, 'emp_id'); }
    public function department()  { return $this->belongsTo(Departments::class); }
    public function leaveType()   { return $this->belongsTo(LeaveType::class); }
    public function dates()       { return $this->hasMany(LeaveApplicationDate::class)->orderBy('leave_date'); }
    public function certifier()   { return $this->belongsTo(\App\Models\User::class, 'certified_by'); }
    public function recommender() { return $this->belongsTo(\App\Models\User::class, 'recommended_by'); }
    public function approver()    { return $this->belongsTo(\App\Models\User::class, 'approved_by'); }

    /* ---- Helpers ---- */
    public function employeeName(): string
    {
        return EmployeeProfile::name($this->employee);
    }

    public function isEditable(): bool   { return in_array($this->status, ['draft', 'pending'], true); }
    public function isCancellable(): bool { return in_array($this->status, ['draft', 'pending', 'certified', 'recommended'], true); }
    public function isDeletable(): bool  { return in_array($this->status, ['draft', 'pending', 'cancelled'], true); }

    public function statusLabel(): string { return self::STATUSES[$this->status] ?? ucfirst($this->status); }

    public function statusBadge(): string
    {
        return match ($this->status) {
            'approved'    => 'bg-green-100 text-green-800',
            'disapproved' => 'bg-red-100 text-red-800',
            'cancelled'   => 'bg-gray-200 text-gray-700',
            'pending', 'draft' => 'bg-amber-100 text-amber-800',
            default       => 'bg-indigo-100 text-indigo-800',
        };
    }

    /** Human-readable version of the 6.B details. */
    public function detailsSummary(): string
    {
        $parts = [];
        if ($this->vacation_location) {
            $parts[] = $this->vacation_location === 'abroad' ? 'Abroad' : 'Within the Philippines';
            if ($this->vacation_location_specify) $parts[count($parts) - 1] .= ' (' . $this->vacation_location_specify . ')';
        }
        if ($this->sick_setting) $parts[] = $this->sick_setting === 'in_hospital' ? 'In hospital' : 'Out patient';
        if ($this->illness_specify) $parts[] = 'Illness: ' . $this->illness_specify;
        if ($this->study_purpose) $parts[] = $this->study_purpose === 'masters_degree' ? "Completion of Master's Degree" : 'BAR/Board Examination Review';
        if ($this->other_purpose) $parts[] = $this->other_purpose === 'monetization' ? 'Monetization of Leave Credits' : 'Terminal Leave';
        return $parts ? implode(' · ', $parts) : '—';
    }
}