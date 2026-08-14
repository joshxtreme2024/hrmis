<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDSOrganization extends Model
{
    protected $table = 'pds_organizations';

    protected $fillable = [
        'user_id',
        'organization',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get organizations as an array (for comma-separated display)
    public function getOrganizationListAttribute()
    {
        $organizations = explode(',', $this->organization);
        return array_map('trim', $organizations);
    }

    // Get first organization (for avatar initials)
    public function getFirstOrganizationInitialAttribute()
    {
        $first = $this->getOrganizationListAttribute()[0] ?? '';
        return substr($first, 0, 1);
    }

    // Check if organization contains certain keywords (for categorization)
    public function getCategoryAttribute()
    {
        $organization = strtolower($this->organization);
        
        $categories = [
            'academic' => ['university', 'college', 'school', 'academic', 'student', 'council', 'debate'],
            'professional' => ['association', 'society', 'professional', 'engineer', 'doctor', 'lawyer', 'accountant'],
            'service' => ['rotary', 'lion', 'kiwanis', 'red cross', 'volunteer', 'service', 'community'],
            'cultural' => ['cultural', 'arts', 'music', 'dance', 'theatre', 'heritage'],
            'sports' => ['sports', 'athletic', 'club', 'team', 'fitness', 'basketball', 'volleyball'],
            'religious' => ['church', 'temple', 'mosque', 'youth', 'bible', 'christian', 'catholic'],
            'social' => ['social', 'club', 'society', 'organization', 'fraternity', 'sorority'],
        ];
        
        foreach ($categories as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($organization, $keyword) !== false) {
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
            'academic' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
            'professional' => 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400',
            'service' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
            'cultural' => 'bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-400',
            'sports' => 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400',
            'religious' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400',
            'social' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
            'other' => 'bg-gray-100 dark:bg-gray-700/30 text-gray-700 dark:text-gray-400',
        ];
        
        return $colors[$this->category] ?? $colors['other'];
    }

    // Get emoji icon for organization type
    public function getCategoryIconAttribute()
    {
        $icons = [
            'academic' => '🎓',
            'professional' => '💼',
            'service' => '🤝',
            'cultural' => '🎭',
            'sports' => '⚽',
            'religious' => '⛪',
            'social' => '👥',
            'other' => '🏢',
        ];
        
        return $icons[$this->category] ?? $icons['other'];
    }
}