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

    protected $appends = [
        'level_label',
        'period_formatted',
        'school_name_short',
        'education_summary',
        'level_badge_color',
    ];

    // ===================== RELATIONSHIPS =====================
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ===================== LEVEL FORMATTING HELPERS =====================
    
    /**
     * Format education level to proper case
     * e.g., "high_school" → "High School"
     * e.g., "senior_high_school" → "Senior High School"
     */
    public static function formatLevel($level)
    {
        if (empty($level)) {
            return 'N/A';
        }
        
        // Special cases for specific levels
        $specialCases = [
            'senior_high_school' => 'Senior High School',
            'high_school' => 'High School',
            'graduate_studies' => 'Graduate Studies',
            'post_graduate' => 'Post Graduate',
        ];
        
        if (isset($specialCases[$level])) {
            return $specialCases[$level];
        }
        
        // Replace underscores with spaces and convert to title case
        return ucwords(str_replace('_', ' ', $level));
    }

    /**
     * Get formatted level as attribute
     */
    public function getLevelLabelAttribute()
    {
        return self::formatLevel($this->level);
    }

    /**
     * Get level badge color based on education level
     */
    public function getLevelBadgeColorAttribute()
    {
        $colors = [
            'elementary' => 'bg-blue-100 text-blue-700 border border-blue-200',
            'high_school' => 'bg-green-100 text-green-700 border border-green-200',
            'senior_high_school' => 'bg-teal-100 text-teal-700 border border-teal-200',
            'vocational' => 'bg-purple-100 text-purple-700 border border-purple-200',
            'college' => 'bg-indigo-100 text-indigo-700 border border-indigo-200',
            'graduate_studies' => 'bg-red-100 text-red-700 border border-red-200',
            'post_graduate' => 'bg-rose-100 text-rose-700 border border-rose-200',
            'doctorate' => 'bg-amber-100 text-amber-700 border border-amber-200',
        ];
        
        return $colors[$this->level] ?? 'bg-gray-100 text-gray-700 border border-gray-200';
    }

    /**
     * Get level order for sorting
     */
    public function getLevelOrderAttribute()
    {
        return self::getLevelOrder()[$this->level] ?? 99;
    }

    // ===================== PERIOD FORMATTING =====================
    
    /**
     * Get formatted period (from - to)
     */
    public function getPeriodFormattedAttribute()
    {
        $from = $this->period_from;
        $to = $this->period_to;
        
        if ($from && $to) {
            return $from . ' - ' . $to;
        } elseif ($from) {
            return $from . ' - Present';
        }
        
        return 'N/A';
    }

    /**
     * Get the year range as string
     */
    public function getYearRangeAttribute()
    {
        $from = $this->period_from;
        $to = $this->period_to;
        
        if ($from && $to) {
            return $from . ' - ' . $to;
        } elseif ($from) {
            return $from . ' - Present';
        }
        
        return $this->year_graduated ?? 'N/A';
    }

    // ===================== SCHOOL INFORMATION =====================
    
    /**
     * Get shortened school name
     */
    public function getSchoolNameShortAttribute()
    {
        if (empty($this->school_name)) {
            return 'N/A';
        }
        
        $name = $this->school_name;
        
        // Common abbreviations
        $abbreviations = [
            'University' => 'Univ.',
            'College' => 'Coll.',
            'Institute' => 'Inst.',
            'School' => 'Sch.',
            'National' => 'Nat\'l',
            'Science' => 'Sci.',
            'Technology' => 'Tech.',
            'Philippines' => 'Phils.',
            'High School' => 'HS',
            'Senior High School' => 'SHS',
            'Elementary School' => 'ES',
            'Academy' => 'Acad.',
            'Foundation' => 'Found.',
            'International' => 'Intl.',
        ];
        
        foreach ($abbreviations as $full => $short) {
            $name = str_replace($full, $short, $name);
        }
        
        return $name;
    }

    // ===================== EDUCATION SUMMARY =====================
    
    /**
     * Get complete education summary
     */
    public function getEducationSummaryAttribute()
    {
        $summary = [];
        
        if ($this->level) {
            $summary['level'] = $this->level_label;
        }
        
        if ($this->school_name) {
            $summary['school'] = $this->school_name;
        }
        
        if ($this->degree_course) {
            $summary['degree'] = $this->degree_course;
        }
        
        if ($this->period_from || $this->period_to) {
            $summary['period'] = $this->period_formatted;
        }
        
        if ($this->year_graduated) {
            $summary['year_graduated'] = $this->year_graduated;
        }
        
        if ($this->highest_level_earned) {
            $summary['highest_level'] = $this->highest_level_earned;
        }
        
        if ($this->scholarship_honors) {
            $summary['honors'] = $this->scholarship_honors;
        }
        
        return $summary;
    }

    /**
     * Get HTML formatted education for display
     */
    public function getFormattedEducationAttribute()
    {
        $html = '<div class="education-item">';
        
        // Level badge
        $html .= '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $this->level_badge_color . '">';
        $html .= $this->level_label;
        $html .= '</span>';
        
        // School name
        if ($this->school_name) {
            $html .= '<h5 class="font-semibold text-gray-900 mt-2">' . e($this->school_name) . '</h5>';
        }
        
        // Degree/Course
        if ($this->degree_course) {
            $html .= '<p class="text-sm text-gray-600">' . e($this->degree_course) . '</p>';
        }
        
        // Period
        if ($this->period_from || $this->period_to) {
            $html .= '<p class="text-xs text-gray-500">' . e($this->period_formatted) . '</p>';
        }
        
        // Year Graduated
        if ($this->year_graduated) {
            $html .= '<p class="text-xs text-gray-500">Graduated: ' . e($this->year_graduated) . '</p>';
        }
        
        // Highest Level Earned
        if ($this->highest_level_earned) {
            $html .= '<p class="text-xs text-gray-500">Highest Level: ' . e($this->highest_level_earned) . '</p>';
        }
        
        // Scholarship/Honors
        if ($this->scholarship_honors) {
            $html .= '<p class="text-xs text-blue-600">🏅 ' . e($this->scholarship_honors) . '</p>';
        }
        
        $html .= '</div>';
        
        return $html;
    }

    // ===================== CHECK METHODS =====================
    
    /**
     * Check if education is complete
     */
    public function getIsCompleteAttribute()
    {
        return !empty($this->school_name) && !empty($this->level);
    }

    /**
     * Check if has honors/scholarship
     */
    public function getHasHonorsAttribute()
    {
        return !empty($this->scholarship_honors);
    }

    /**
     * Check if has degree/course
     */
    public function getHasDegreeAttribute()
    {
        return !empty($this->degree_course);
    }

    /**
     * Check if is tertiary education (college or higher)
     */
    public function getIsTertiaryAttribute()
    {
        return in_array($this->level, ['college', 'graduate_studies', 'post_graduate', 'doctorate']);
    }

    /**
     * Check if is secondary education (high school or senior high)
     */
    public function getIsSecondaryAttribute()
    {
        return in_array($this->level, ['high_school', 'senior_high_school']);
    }

    // ===================== STATIC HELPERS =====================
    
    /**
     * Get all education level options
     */
    public static function getLevelOptions()
    {
        return [
            'elementary' => 'Elementary',
            'high_school' => 'High School',
            'senior_high_school' => 'Senior High School',
            'vocational' => 'Vocational',
            'college' => 'College',
            'graduate_studies' => 'Graduate Studies',
            'post_graduate' => 'Post Graduate',
            'doctorate' => 'Doctorate',
        ];
    }

    /**
     * Get education level order for sorting
     */
    public static function getLevelOrder()
    {
        return [
            'elementary' => 1,
            'high_school' => 2,
            'senior_high_school' => 3,
            'vocational' => 4,
            'college' => 5,
            'graduate_studies' => 6,
            'post_graduate' => 7,
            'doctorate' => 8,
        ];
    }

    /**
     * Get education levels grouped by category
     */
    public static function getLevelsGrouped()
    {
        return [
            'Basic Education' => [
                'elementary' => 'Elementary',
                'high_school' => 'High School',
                'senior_high_school' => 'Senior High School',
            ],
            'Tertiary Education' => [
                'vocational' => 'Vocational',
                'college' => 'College',
            ],
            'Advanced Education' => [
                'graduate_studies' => 'Graduate Studies',
                'post_graduate' => 'Post Graduate',
                'doctorate' => 'Doctorate',
            ],
        ];
    }

    /**
     * Get level badge color mapping
     */
    public static function getBadgeColors()
    {
        return [
            'elementary' => 'bg-blue-100 text-blue-700 border border-blue-200',
            'high_school' => 'bg-green-100 text-green-700 border border-green-200',
            'senior_high_school' => 'bg-teal-100 text-teal-700 border border-teal-200',
            'vocational' => 'bg-purple-100 text-purple-700 border border-purple-200',
            'college' => 'bg-indigo-100 text-indigo-700 border border-indigo-200',
            'graduate_studies' => 'bg-red-100 text-red-700 border border-red-200',
            'post_graduate' => 'bg-rose-100 text-rose-700 border border-rose-200',
            'doctorate' => 'bg-amber-100 text-amber-700 border border-amber-200',
        ];
    }
}