<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDSDistinction extends Model
{
    protected $table = 'pds_distinctions';

    protected $fillable = [
        'user_id',
        'distinctions',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get distinctions as an array (for comma-separated display)
    public function getDistinctionListAttribute()
    {
        $distinctions = explode(',', $this->distinctions);
        return array_map('trim', $distinctions);
    }

    // Get first distinction (for avatar initials)
    public function getFirstDistinctionInitialAttribute()
    {
        $first = $this->getDistinctionListAttribute()[0] ?? '';
        return substr($first, 0, 1);
    }

    // Check if distinction contains certain keywords (for categorization)
    public function getCategoryAttribute()
    {
        $distinction = strtolower($this->distinctions);
        
        $categories = [
            'academic_excellence' => ['cum laude', 'magna', 'summa', 'dean', 'honor', 'scholar', 'academic excellence'],
            'leadership' => ['leadership', 'president', 'executive', 'director', 'officer'],
            'research' => ['research', 'thesis', 'dissertation', 'publication', 'research'],
            'service' => ['service', 'community', 'volunteer', 'extension', 'outreach'],
            'arts' => ['arts', 'music', 'performance', 'creative', 'design'],
            'sports' => ['sports', 'athletic', 'champion', 'tournament', 'games'],
        ];
        
        foreach ($categories as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($distinction, $keyword) !== false) {
                    return $category;
                }
            }
        }
        
        return 'other';
    }

    // Get category badge color
    public function getCategoryBadgeAttribute()
    {
        $colors = [
            'academic_excellence' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
            'leadership' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400',
            'research' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
            'service' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
            'arts' => 'bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-400',
            'sports' => 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400',
            'other' => 'bg-gray-100 dark:bg-gray-700/30 text-gray-700 dark:text-gray-400',
        ];
        
        return $colors[$this->category] ?? $colors['other'];
    }

    // Get emoji icon for distinction type
    public function getCategoryIconAttribute()
    {
        $icons = [
            'academic_excellence' => '🎓',
            'leadership' => '👔',
            'research' => '🔬',
            'service' => '🤝',
            'arts' => '🎨',
            'sports' => '🏆',
            'other' => '⭐',
        ];
        
        return $icons[$this->category] ?? $icons['other'];
    }
}