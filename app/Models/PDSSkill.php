<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDSSkill extends Model
{
    protected $table = 'pds_skills';

    protected $fillable = [
        'user_id',
        'skill_hobby',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get skills as an array (for comma-separated display)
    public function getSkillListAttribute()
    {
        $skills = explode(',', $this->skill_hobby);
        return array_map('trim', $skills);
    }

    // Get first skill (for avatar initials)
    public function getFirstSkillInitialAttribute()
    {
        $first = $this->getSkillListAttribute()[0] ?? '';
        return substr($first, 0, 1);
    }

    // Check if skill contains certain keywords (for categorization)
    public function getCategoryAttribute()
    {
        $skill = strtolower($this->skill_hobby);
        
        $categories = [
            'technical' => ['programming', 'web', 'design', 'development', 'coding', 'software', 'it', 'technical'],
            'creative' => ['art', 'music', 'writing', 'photography', 'design', 'creative', 'craft', 'painting'],
            'sports' => ['sport', 'game', 'athletic', 'fitness', 'exercise', 'swim', 'run', 'basketball'],
            'academic' => ['research', 'analysis', 'writing', 'study', 'science', 'math', 'history'],
            'communication' => ['public speaking', 'communication', 'presentation', 'negotiation', 'leadership'],
            'organizational' => ['planning', 'management', 'organization', 'leadership', 'coordination'],
        ];
        
        foreach ($categories as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($skill, $keyword) !== false) {
                    return $category;
                }
            }
        }
        
        return 'other';
    }

    // Get category color
    public function getCategoryBadgeAttribute()
    {
        $colors = [
            'technical' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
            'creative' => 'bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-400',
            'sports' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
            'academic' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400',
            'communication' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
            'organizational' => 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400',
            'other' => 'bg-gray-100 dark:bg-gray-700/30 text-gray-700 dark:text-gray-400',
        ];
        
        return $colors[$this->category] ?? $colors['other'];
    }
}