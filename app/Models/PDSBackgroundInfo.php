<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PDSBackgroundInfo extends Model
{
    use HasFactory;

    protected $table = 'pds_background_infos';

    protected $fillable = [
        'user_id',
        // Question 34
        'q34_a',
        'q34_a_details',
        'q34_b',
        'q34_b_details',
        // Question 35
        'q35_a',
        'q35_a_details',
        'q35_b',
        'q35_b_details',
        // Question 36
        'q36',
        'q36_details',
        // Question 37
        'q37',
        'q37_details',
        // Question 38
        'q38_a',
        'q38_a_details',
        'q38_b',
        'q38_b_details',
        // Question 39
        'q39',
        'q39_details',
        // Question 40
        'q40_a',
        'q40_a_details',
        'q40_b',
        'q40_b_details',
        'q40_c',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the background information.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the background info is complete.
     */
    public function isComplete(): bool
    {
        $requiredFields = [
            'q34_a', 'q34_b', 'q35_a', 'q35_b', 'q36', 
            'q37', 'q38_a', 'q38_b', 'q39', 'q40_a', 'q40_b', 'q40_c'
        ];

        foreach ($requiredFields as $field) {
            if (is_null($this->$field)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get completion percentage.
     */
    public function getCompletionPercentage(): int
    {
        $total = 12; // Total number of questions
        $answered = 0;

        $fields = ['q34_a', 'q34_b', 'q35_a', 'q35_b', 'q36', 'q37', 'q38_a', 'q38_b', 'q39', 'q40_a', 'q40_b', 'q40_c'];
        
        foreach ($fields as $field) {
            if (!is_null($this->$field)) {
                $answered++;
            }
        }

        return round(($answered / $total) * 100);
    }

    /**
     * Get summary of answers for display.
     */
    public function getSummary(): array
    {
        return [
            'q34' => [
                'a' => $this->q34_a === 'yes' ? 'Yes' : 'No',
                'a_details' => $this->q34_a_details,
                'b' => $this->q34_b === 'yes' ? 'Yes' : 'No',
                'b_details' => $this->q34_b_details,
            ],
            'q35' => [
                'a' => $this->q35_a === 'yes' ? 'Yes' : 'No',
                'a_details' => $this->q35_a_details,
                'b' => $this->q35_b === 'yes' ? 'Yes' : 'No',
                'b_details' => $this->q35_b_details,
            ],
            'q36' => [
                'answer' => $this->q36 === 'yes' ? 'Yes' : 'No',
                'details' => $this->q36_details,
            ],
            'q37' => [
                'answer' => $this->q37 === 'yes' ? 'Yes' : 'No',
                'details' => $this->q37_details,
            ],
            'q38' => [
                'a' => $this->q38_a === 'yes' ? 'Yes' : 'No',
                'a_details' => $this->q38_a_details,
                'b' => $this->q38_b === 'yes' ? 'Yes' : 'No',
                'b_details' => $this->q38_b_details,
            ],
            'q39' => [
                'answer' => $this->q39 === 'yes' ? 'Yes' : 'No',
                'details' => $this->q39_details,
            ],
            'q40' => [
                'a' => $this->q40_a === 'yes' ? 'Yes' : 'No',
                'a_details' => $this->q40_a_details,
                'b' => $this->q40_b === 'yes' ? 'Yes' : 'No',
                'b_details' => $this->q40_b_details,
                'c' => $this->q40_c === 'yes' ? 'Yes' : 'No',
            ],
        ];
    }

    /**
     * Get the status badge class.
     */
    public function getStatusBadge(): string
    {
        if ($this->isComplete()) {
            return 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400';
        }
        return 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400';
    }

    /**
     * Get the status label.
     */
    public function getStatusLabel(): string
    {
        if ($this->isComplete()) {
            return 'Complete';
        }
        return 'Incomplete';
    }

    /**
     * Scope a query to only include complete records.
     */
    public function scopeComplete($query)
    {
        return $query->whereNotNull('q34_a')
            ->whereNotNull('q34_b')
            ->whereNotNull('q35_a')
            ->whereNotNull('q35_b')
            ->whereNotNull('q36')
            ->whereNotNull('q37')
            ->whereNotNull('q38_a')
            ->whereNotNull('q38_b')
            ->whereNotNull('q39')
            ->whereNotNull('q40_a')
            ->whereNotNull('q40_b')
            ->whereNotNull('q40_c');
    }

    /**
     * Scope a query to only include incomplete records.
     */
    public function scopeIncomplete($query)
    {
        return $query->where(function($q) {
            $q->whereNull('q34_a')
                ->orWhereNull('q34_b')
                ->orWhereNull('q35_a')
                ->orWhereNull('q35_b')
                ->orWhereNull('q36')
                ->orWhereNull('q37')
                ->orWhereNull('q38_a')
                ->orWhereNull('q38_b')
                ->orWhereNull('q39')
                ->orWhereNull('q40_a')
                ->orWhereNull('q40_b')
                ->orWhereNull('q40_c');
        });
    }

    /**
     * Check if any question has a "Yes" answer.
     */
    public function hasAnyYes(): bool
    {
        $fields = ['q34_a', 'q34_b', 'q35_a', 'q35_b', 'q36', 'q37', 'q38_a', 'q38_b', 'q39', 'q40_a', 'q40_b', 'q40_c'];
        
        foreach ($fields as $field) {
            if ($this->$field === 'yes') {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get all "Yes" answers with their details.
     */
    public function getYesAnswers(): array
    {
        $yesAnswers = [];
        
        $mapping = [
            'q34_a' => ['question' => '34a: Related within 3rd degree', 'details' => 'q34_a_details'],
            'q34_b' => ['question' => '34b: Related within 4th degree', 'details' => 'q34_b_details'],
            'q35_a' => ['question' => '35a: Found guilty of administrative offense', 'details' => 'q35_a_details'],
            'q35_b' => ['question' => '35b: Criminally charged', 'details' => 'q35_b_details'],
            'q36' => ['question' => '36: Convicted of crime/violation', 'details' => 'q36_details'],
            'q37' => ['question' => '37: Separated from service', 'details' => 'q37_details'],
            'q38_a' => ['question' => '38a: Candidate in election', 'details' => 'q38_a_details'],
            'q38_b' => ['question' => '38b: Resigned for election campaign', 'details' => 'q38_b_details'],
            'q39' => ['question' => '39: Immigrant/permanent resident', 'details' => 'q39_details'],
            'q40_a' => ['question' => '40a: Member of indigenous group', 'details' => 'q40_a_details'],
            'q40_b' => ['question' => '40b: Person with disability', 'details' => 'q40_b_details'],
        ];
        
        foreach ($mapping as $field => $info) {
            if ($this->$field === 'yes') {
                $detailsField = $info['details'];
                $yesAnswers[] = [
                    'question' => $info['question'],
                    'details' => $this->$detailsField,
                ];
            }
        }
        
        return $yesAnswers;
    }
}