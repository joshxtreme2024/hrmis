<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDSWorkExperience extends Model
{
    protected $table = 'pds_work_experiences';
    
    protected $fillable = [
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
        'is_gov' => 'boolean',
    ];

    protected $appends = [
        'period_formatted',
        'period_range',
        'duration',
        'formatted_salary',
        'employment_type_label',
        'status_badge_color',
        'sector_label',
        'sector_badge_color',
        'experience_summary',
        'is_current',
        'duration_years',
        'duration_months',
    ];

    // ===================== RELATIONSHIPS =====================
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ===================== PERIOD FORMATTING =====================
    
    /**
     * Get formatted period (From - To)
     */
    public function getPeriodFormattedAttribute()
    {
        $from = $this->inclusive_from ? $this->inclusive_from->format('M d, Y') : 'N/A';
        $to = $this->inclusive_to ? $this->inclusive_to->format('M d, Y') : 'Present';
        
        return $from . ' - ' . $to;
    }

    /**
     * Get period range (short format)
     */
    public function getPeriodRangeAttribute()
    {
        $from = $this->inclusive_from ? $this->inclusive_from->format('M Y') : 'N/A';
        $to = $this->inclusive_to ? $this->inclusive_to->format('M Y') : 'Present';
        
        return $from . ' - ' . $to;
    }

    /**
     * Get year range only
     */
    public function getYearRangeAttribute()
    {
        $from = $this->inclusive_from ? $this->inclusive_from->format('Y') : 'N/A';
        $to = $this->inclusive_to ? $this->inclusive_to->format('Y') : 'Present';
        
        return $from . ' - ' . $to;
    }

    /**
     * Check if currently employed
     */
    public function getIsCurrentAttribute()
    {
        return is_null($this->inclusive_to);
    }

    // ===================== DURATION CALCULATIONS =====================
    
    /**
     * Get duration in months
     */
    public function getDurationMonthsAttribute()
    {
        if (!$this->inclusive_from) {
            return 0;
        }
        
        $end = $this->inclusive_to ?? now();
        return $this->inclusive_from->diffInMonths($end);
    }

    /**
     * Get duration in years
     */
    public function getDurationYearsAttribute()
    {
        if (!$this->inclusive_from) {
            return 0;
        }
        
        $end = $this->inclusive_to ?? now();
        return $this->inclusive_from->diffInYears($end);
    }

    /**
     * Get human-readable duration
     */
    public function getDurationAttribute()
    {
        if (!$this->inclusive_from) {
            return 'N/A';
        }
        
        $end = $this->inclusive_to ?? now();
        $diff = $this->inclusive_from->diff($end);
        
        $parts = [];
        
        $years = $diff->y;
        $months = $diff->m;
        $days = $diff->d;
        
        if ($years > 0) {
            $parts[] = $years . ' ' . ($years > 1 ? 'years' : 'year');
        }
        if ($months > 0) {
            $parts[] = $months . ' ' . ($months > 1 ? 'months' : 'month');
        }
        if ($days > 0 && $years == 0 && $months == 0) {
            $parts[] = $days . ' ' . ($days > 1 ? 'days' : 'day');
        }
        
        return !empty($parts) ? implode(' ', $parts) : 'Less than a month';
    }

    /**
     * Get duration in years with decimal
     */
    public function getDurationDecimalAttribute()
    {
        if (!$this->inclusive_from) {
            return 0;
        }
        
        $end = $this->inclusive_to ?? now();
        $months = $this->inclusive_from->diffInMonths($end);
        
        return round($months / 12, 2);
    }

    // ===================== SALARY FORMATTING =====================
    
    /**
     * Get formatted salary with currency
     */
    public function getFormattedSalaryAttribute()
    {
        if (is_null($this->monthly_salary)) {
            return 'N/A';
        }
        
        return '₱ ' . number_format($this->monthly_salary, 2);
    }

    /**
     * Get salary with grade
     */
    public function getSalaryWithGradeAttribute()
    {
        $salary = $this->formatted_salary;
        $grade = $this->salary_grade;
        
        if ($grade) {
            return $salary . ' (SG ' . $grade . ')';
        }
        
        return $salary;
    }

    // ===================== STATUS FORMATTING =====================
    
    /**
     * Get status of appointment label
     */
    public function getStatusOfAppointmentLabelAttribute()
    {
        $statuses = [
            'permanent' => 'Permanent',
            'temporary' => 'Temporary',
            'casual' => 'Casual',
            'contractual' => 'Contractual',
            'coterminous' => 'Coterminous',
            'part_time' => 'Part Time',
            'full_time' => 'Full Time',
            'probationary' => 'Probationary',
            'regular' => 'Regular',
            'project_based' => 'Project Based',
            'seasonal' => 'Seasonal',
        ];
        
        return $statuses[$this->status_of_appointment] ?? ucfirst(str_replace('_', ' ', $this->status_of_appointment ?? 'N/A'));
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColorAttribute()
    {
        $colors = [
            'permanent' => 'bg-green-100 text-green-700 border border-green-200',
            'regular' => 'bg-green-100 text-green-700 border border-green-200',
            'temporary' => 'bg-yellow-100 text-yellow-700 border border-yellow-200',
            'casual' => 'bg-blue-100 text-blue-700 border border-blue-200',
            'contractual' => 'bg-purple-100 text-purple-700 border border-purple-200',
            'coterminous' => 'bg-indigo-100 text-indigo-700 border border-indigo-200',
            'part_time' => 'bg-gray-100 text-gray-700 border border-gray-200',
            'full_time' => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
            'probationary' => 'bg-orange-100 text-orange-700 border border-orange-200',
            'project_based' => 'bg-rose-100 text-rose-700 border border-rose-200',
            'seasonal' => 'bg-amber-100 text-amber-700 border border-amber-200',
        ];
        
        return $colors[$this->status_of_appointment] ?? 'bg-gray-100 text-gray-700 border border-gray-200';
    }

    // ===================== SECTOR FORMATTING =====================
    
    /**
     * Get sector label
     */
    public function getSectorLabelAttribute()
    {
        if (is_null($this->is_gov)) {
            return 'N/A';
        }
        
        return $this->is_gov ? 'Government' : 'Private';
    }

    /**
     * Get sector badge color
     */
    public function getSectorBadgeColorAttribute()
    {
        if (is_null($this->is_gov)) {
            return 'bg-gray-100 text-gray-700 border border-gray-200';
        }
        
        return $this->is_gov 
            ? 'bg-blue-100 text-blue-700 border border-blue-200' 
            : 'bg-purple-100 text-purple-700 border border-purple-200';
    }

    /**
     * Get employment type label
     */
    public function getEmploymentTypeLabelAttribute()
    {
        $type = $this->sector_label;
        $status = $this->status_of_appointment_label;
        
        if ($type !== 'N/A' && $status !== 'N/A') {
            return $type . ' - ' . $status;
        }
        
        return $type !== 'N/A' ? $type : $status;
    }

    // ===================== SUMMARY =====================
    
    /**
     * Get complete experience summary
     */
    public function getExperienceSummaryAttribute()
    {
        $summary = [];
        
        if ($this->position_title) {
            $summary['position'] = $this->position_title;
        }
        
        if ($this->department_agency_office) {
            $summary['organization'] = $this->department_agency_office;
        }
        
        if ($this->inclusive_from || $this->inclusive_to) {
            $summary['period'] = $this->period_formatted;
            $summary['duration'] = $this->duration;
        }
        
        if ($this->monthly_salary) {
            $summary['salary'] = $this->formatted_salary;
        }
        
        if ($this->salary_grade) {
            $summary['salary_grade'] = 'SG ' . $this->salary_grade;
        }
        
        if ($this->status_of_appointment) {
            $summary['status'] = $this->status_of_appointment_label;
        }
        
        if (!is_null($this->is_gov)) {
            $summary['sector'] = $this->sector_label;
        }
        
        return $summary;
    }

    /**
     * Get HTML formatted experience for display
     */
    public function getFormattedExperienceAttribute()
    {
        $html = '<div class="experience-item">';
        
        // Position and Organization
        $html .= '<div class="flex items-start justify-between">';
        $html .= '<div>';
        if ($this->position_title) {
            $html .= '<h5 class="font-semibold text-gray-900">' . e($this->position_title) . '</h5>';
        }
        if ($this->department_agency_office) {
            $html .= '<p class="text-sm text-gray-600">' . e($this->department_agency_office) . '</p>';
        }
        $html .= '</div>';
        
        // Duration badge
        if ($this->duration) {
            $html .= '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">';
            $html .= e($this->duration);
            $html .= '</span>';
        }
        $html .= '</div>';
        
        // Period
        $html .= '<p class="text-xs text-gray-500 mt-1">' . e($this->period_range) . '</p>';
        
        // Sector and Status
        $html .= '<div class="flex items-center gap-2 mt-2">';
        if (!is_null($this->is_gov)) {
            $html .= '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ' . $this->sector_badge_color . '">';
            $html .= e($this->sector_label);
            $html .= '</span>';
        }
        if ($this->status_of_appointment) {
            $html .= '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ' . $this->status_badge_color . '">';
            $html .= e($this->status_of_appointment_label);
            $html .= '</span>';
        }
        $html .= '</div>';
        
        // Salary
        if ($this->monthly_salary) {
            $html .= '<p class="text-xs text-gray-500 mt-2">';
            $html .= '<span class="font-semibold">Monthly Salary:</span> ';
            $html .= e($this->formatted_salary);
            if ($this->salary_grade) {
                $html .= ' (SG ' . e($this->salary_grade) . ')';
            }
            $html .= '</p>';
        }
        
        $html .= '</div>';
        
        return $html;
    }

    // ===================== CHECK METHODS =====================
    
    /**
     * Check if experience is complete
     */
    public function getIsCompleteAttribute()
    {
        return !empty($this->position_title) && 
               !empty($this->department_agency_office) && 
               !empty($this->inclusive_from);
    }

    /**
     * Check if has salary information
     */
    public function getHasSalaryAttribute()
    {
        return !is_null($this->monthly_salary);
    }

    /**
     * Check if has salary grade
     */
    public function getHasSalaryGradeAttribute()
    {
        return !empty($this->salary_grade);
    }

    /**
     * Check if has status
     */
    public function getHasStatusAttribute()
    {
        return !empty($this->status_of_appointment);
    }

    // ===================== STATIC HELPERS =====================
    
    /**
     * Get all status of appointment options
     */
    public static function getStatusOptions()
    {
        return [
            'permanent' => 'Permanent',
            'temporary' => 'Temporary',
            'casual' => 'Casual',
            'contractual' => 'Contractual',
            'coterminous' => 'Coterminous',
            'part_time' => 'Part Time',
            'full_time' => 'Full Time',
            'probationary' => 'Probationary',
            'regular' => 'Regular',
            'project_based' => 'Project Based',
            'seasonal' => 'Seasonal',
        ];
    }

    /**
     * Get status badge colors mapping
     */
    public static function getStatusBadgeColors()
    {
        return [
            'permanent' => 'bg-green-100 text-green-700',
            'regular' => 'bg-green-100 text-green-700',
            'temporary' => 'bg-yellow-100 text-yellow-700',
            'casual' => 'bg-blue-100 text-blue-700',
            'contractual' => 'bg-purple-100 text-purple-700',
            'coterminous' => 'bg-indigo-100 text-indigo-700',
            'part_time' => 'bg-gray-100 text-gray-700',
            'full_time' => 'bg-emerald-100 text-emerald-700',
            'probationary' => 'bg-orange-100 text-orange-700',
            'project_based' => 'bg-rose-100 text-rose-700',
            'seasonal' => 'bg-amber-100 text-amber-700',
        ];
    }

    /**
     * Get sector options
     */
    public static function getSectorOptions()
    {
        return [
            '1' => 'Government',
            '0' => 'Private',
        ];
    }
}