<?php

namespace App\View\Components;

use Illuminate\View\Component;

class EducationBadge extends Component
{
    public $level;

    public function __construct($level)
    {
        $this->level = $level;
    }

    public function getLabel()
    {
        $levels = [
            'elementary' => 'Elementary',
            'high_school' => 'High School',
            'senior_high_school' => 'Senior High School',
            'college' => 'College',
            'post_graduate' => 'Post Graduate',
            'vocational' => 'Vocational',
        ];

        return $levels[$this->level] ?? ucfirst($this->level);
    }

    public function getColor()
    {
        $colors = [
            'elementary' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
            'high_school' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400',
            'senior_high_school' => 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400',
            'college' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400',
            'post_graduate' => 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400',
            'vocational' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
        ];

        return $colors[$this->level] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-400';
    }

    public function render()
    {
        return view('components.education-badge', [
            'label' => $this->getLabel(),
            'color' => $this->getColor()
        ]);
    }
}