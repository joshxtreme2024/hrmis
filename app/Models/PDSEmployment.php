<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PDSEmployment extends Model
{
    protected $table = "pds_employment_info";

    protected $fillable = [
        'user_id',
        'employee_id',
        'position_id',
        'department_id',
        'hired_at',
        'status',
        'employment_type',
        'date_of_original_appointment',
        'date_of_last_promotion',
        'salary',
        'salary_grade',
        'step_increment',
    ];

    protected $casts = [
        'hired_at' => 'date',
        'date_of_original_appointment' => 'date',
        'date_of_last_promotion' => 'date',
        'salary' => 'decimal:2',
        'step_increment' => 'integer',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Positions::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Departments::class);
    }

    // Status Helpers
    public function getStatusBadgeAttribute(): string
    {
        $statuses = [
            'active' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
            'inactive' => 'bg-gray-100 dark:bg-gray-700/30 text-gray-700 dark:text-gray-400',
            'resigned' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
            'retired' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
            'on_leave' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
            'suspended' => 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400',
            'terminated' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
        ];

        return $statuses[strtolower($this->status)] ?? 'bg-gray-100 dark:bg-gray-700/30 text-gray-700 dark:text-gray-400';
    }

    public function getStatusLabelAttribute(): string
    {
        $statuses = [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'resigned' => 'Resigned',
            'retired' => 'Retired',
            'on_leave' => 'On Leave',
            'suspended' => 'Suspended',
            'terminated' => 'Terminated',
        ];

        return $statuses[strtolower($this->status)] ?? ucfirst($this->status);
    }

    public function getEmploymentTypeLabelAttribute(): string
    {
        $types = [
            'permanent' => 'Permanent',
            'temporary' => 'Temporary',
            'contractual' => 'Contractual',
            'casual' => 'Casual',
            'job_order' => 'Job Order',
            'consultant' => 'Consultant',
            'co_term' => 'Co-Terminus',
        ];

        return $types[strtolower($this->employment_type)] ?? ucfirst($this->employment_type);
    }

    // Date Formatters
    public function getFormattedHiredDateAttribute(): string
    {
        return $this->hired_at?->format('F d, Y') ?? 'N/A';
    }

    public function getFormattedOriginalAppointmentAttribute(): string
    {
        return $this->date_of_original_appointment?->format('F d, Y') ?? 'N/A';
    }

    public function getFormattedLastPromotionAttribute(): string
    {
        return $this->date_of_last_promotion?->format('F d, Y') ?? 'N/A';
    }

    public function getFormattedSalaryAttribute(): string
    {
        if (is_null($this->salary)) {
            return 'N/A';
        }
        return '₱' . number_format($this->salary, 2);
    }

    // Service Duration
    public function getYearsOfServiceAttribute(): int
    {
        if (!$this->hired_at) {
            return 0;
        }
        return $this->hired_at->diffInYears(now());
    }

    public function getMonthsOfServiceAttribute(): int
    {
        if (!$this->hired_at) {
            return 0;
        }
        return $this->hired_at->diffInMonths(now());
    }

    public function getDaysOfServiceAttribute(): int
    {
        if (!$this->hired_at) {
            return 0;
        }
        return $this->hired_at->diffInDays(now());
    }

    public function getServiceDurationAttribute(): string
    {
        if (!$this->hired_at) {
            return 'N/A';
        }

        $years = $this->years_of_service;
        $months = $this->months_of_service % 12;

        $parts = [];
        if ($years > 0) {
            $parts[] = $years . ' ' . ($years > 1 ? 'years' : 'year');
        }
        if ($months > 0) {
            $parts[] = $months . ' ' . ($months > 1 ? 'months' : 'month');
        }

        return implode(', ', $parts) ?: 'Less than a month';
    }

    public function getIsActiveAttribute(): bool
    {
        return strtolower($this->status) === 'active';
    }
}