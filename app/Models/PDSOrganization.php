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

    protected $appends = [
        'category_badge',
        'category_icon',
        'category_label',
    ];

    // ===================== RELATIONSHIPS =====================
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ===================== CATEGORIZATION =====================
    
    /**
     * Get the category of the organization based on keywords
     */
    public function getCategoryAttribute()
    {
        $organization = strtolower($this->organization);
        
        $categories = [
            // Academic & Education
            'academic' => ['university', 'college', 'school', 'academic', 'student', 'council', 'debate', 'education', 'campus', 'institute'],
            
            // Professional
            'professional' => ['association', 'society', 'professional', 'engineer', 'doctor', 'lawyer', 'accountant', 'institute', 'board'],
            
            // Service & Charity
            'service' => ['rotary', 'lion', 'kiwanis', 'red cross', 'volunteer', 'service', 'community', 'charity', 'foundation'],
            
            // Cultural & Arts
            'cultural' => ['cultural', 'arts', 'music', 'dance', 'theatre', 'heritage', 'museum', 'film'],
            
            // Sports
            'sports' => ['sports', 'athletic', 'team', 'fitness', 'basketball', 'volleyball', 'football', 'swimming', 'baseball'],
            
            // Religious
            'religious' => ['church', 'temple', 'mosque', 'youth', 'bible', 'christian', 'catholic', 'ministry', 'fellowship'],
            
            // Social (moved 'club' here from sports)
            'social' => ['social', 'society', 'organization', 'fraternity', 'sorority', 'brotherhood', 'sisterhood'],
            
            // Government
            'government' => ['government', 'agency', 'bureau', 'department', 'commission', 'authority', 'office'],
            
            // Business
            'business' => ['corporation', 'inc', 'company', 'enterprise', 'business', 'firm', 'holdings'],
            
            // Automotive (general)
            'automotive' => ['auto', 'car', 'truck', 'vehicle', 'driving', 'motors', 'racing'],
            
            // Motorcycle (specific - CHECK THIS FIRST using priority order)
            'motorcycle' => ['motorcycle', 'moto', 'dominar', 'bajaj', 'honda', 'cfmoto', 'kawasaki', 'suzuki', 'yamaha', 'aprilia', 'ducati', 'bmw', 'triumph', 'harley', 'ktm'],
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

    /**
     * Get category label (human-readable)
     */
    public function getCategoryLabelAttribute()
    {
        $labels = [
            'academic' => 'Academic',
            'professional' => 'Professional',
            'service' => 'Service',
            'cultural' => 'Cultural',
            'sports' => 'Sports',
            'religious' => 'Religious',
            'social' => 'Social',
            'government' => 'Government',
            'business' => 'Business',
            'automotive' => 'Automotive',
            'motorcycle' => 'Motorcycle',
            'other' => 'Other',
        ];
        
        return $labels[$this->category] ?? 'Other';
    }

    /**
     * Get category badge color
     */
    public function getCategoryBadgeAttribute()
    {
        $colors = [
            'academic' => 'bg-blue-100 text-blue-700 border border-blue-200',
            'professional' => 'bg-indigo-100 text-indigo-700 border border-indigo-200',
            'service' => 'bg-green-100 text-green-700 border border-green-200',
            'cultural' => 'bg-pink-100 text-pink-700 border border-pink-200',
            'sports' => 'bg-orange-100 text-orange-700 border border-orange-200',
            'religious' => 'bg-purple-100 text-purple-700 border border-purple-200',
            'social' => 'bg-yellow-100 text-yellow-700 border border-yellow-200',
            'government' => 'bg-red-100 text-red-700 border border-red-200',
            'business' => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
            'automotive' => 'bg-cyan-100 text-cyan-700 border border-cyan-200',
            'motorcycle' => 'bg-amber-100 text-amber-700 border border-amber-200',
            'other' => 'bg-gray-100 text-gray-700 border border-gray-200',
        ];
        
        return $colors[$this->category] ?? $colors['other'];
    }

    /**
     * Get emoji icon for organization type
     */
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
            'government' => '🏛️',
            'business' => '🏢',
            'automotive' => '🚗',
            'motorcycle' => '🏍️',
            'other' => '📌',
        ];
        
        return $icons[$this->category] ?? $icons['other'];
    }

    /**
     * Get Bootstrap icon class for category
     */
    public function getCategoryIconClassAttribute()
    {
        $icons = [
            'academic' => 'bi-mortarboard',
            'professional' => 'bi-briefcase',
            'service' => 'bi-hand-thumbs-up',
            'cultural' => 'bi-palette',
            'sports' => 'bi-trophy',
            'religious' => 'bi-building-church',
            'social' => 'bi-people',
            'government' => 'bi-building',
            'business' => 'bi-shop',
            'automotive' => 'bi-car-front',
            'motorcycle' => 'bi-motorcycle',
            'other' => 'bi-tag',
        ];
        
        return $icons[$this->category] ?? $icons['other'];
    }

    // ===================== FORMATTING =====================
    
    /**
     * Get organization name with category badge (HTML)
     */
    public function getFormattedOrganizationAttribute()
    {
        return '<span class="inline-flex items-center gap-2">' .
               '<span class="text-lg">' . $this->category_icon . '</span>' .
               '<span>' . e($this->organization) . '</span>' .
               '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ' . $this->category_badge . '">' .
               $this->category_label .
               '</span>' .
               '</span>';
    }

    /**
     * Get organization name with icon only
     */
    public function getOrganizationWithIconAttribute()
    {
        return $this->category_icon . ' ' . $this->organization;
    }

    /**
     * Get initials of organization (for avatar)
     */
    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->organization);
        $initials = '';
        
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
        }
        
        return $initials ?: substr(strtoupper($this->organization), 0, 2);
    }

    /**
     * Get truncated organization name
     */
    public function getTruncatedAttribute()
    {
        return strlen($this->organization) > 30 
            ? substr($this->organization, 0, 30) . '...' 
            : $this->organization;
    }

    // ===================== CHECK METHODS =====================
    
    /**
     * Check if organization is in a specific category
     */
    public function isCategory($category)
    {
        return $this->category === $category;
    }

    /**
     * Check if organization is academic
     */
    public function getIsAcademicAttribute()
    {
        return $this->isCategory('academic');
    }

    /**
     * Check if organization is professional
     */
    public function getIsProfessionalAttribute()
    {
        return $this->isCategory('professional');
    }

    /**
     * Check if organization is service-oriented
     */
    public function getIsServiceAttribute()
    {
        return $this->isCategory('service');
    }

    /**
     * Check if organization is motorcycle-related
     */
    public function getIsMotorcycleAttribute()
    {
        return $this->isCategory('motorcycle');
    }

    /**
     * Check if organization is automotive-related
     */
    public function getIsAutomotiveAttribute()
    {
        return $this->isCategory('automotive');
    }

    // ===================== STATIC HELPERS =====================
    
    /**
     * Get all category options
     */
    public static function getCategories()
    {
        return [
            'academic' => 'Academic',
            'professional' => 'Professional',
            'service' => 'Service',
            'cultural' => 'Cultural',
            'sports' => 'Sports',
            'religious' => 'Religious',
            'social' => 'Social',
            'government' => 'Government',
            'business' => 'Business',
            'automotive' => 'Automotive',
            'motorcycle' => 'Motorcycle',
            'other' => 'Other',
        ];
    }

    /**
     * Get category icon for a specific category
     */
    public static function getCategoryIcon($category)
    {
        $icons = [
            'academic' => '🎓',
            'professional' => '💼',
            'service' => '🤝',
            'cultural' => '🎭',
            'sports' => '⚽',
            'religious' => '⛪',
            'social' => '👥',
            'government' => '🏛️',
            'business' => '🏢',
            'automotive' => '🚗',
            'motorcycle' => '🏍️',
            'other' => '📌',
        ];
        
        return $icons[$category] ?? $icons['other'];
    }

    /**
     * Get category badge color for a specific category
     */
    public static function getCategoryBadge($category)
    {
        $colors = [
            'academic' => 'bg-blue-100 text-blue-700 border border-blue-200',
            'professional' => 'bg-indigo-100 text-indigo-700 border border-indigo-200',
            'service' => 'bg-green-100 text-green-700 border border-green-200',
            'cultural' => 'bg-pink-100 text-pink-700 border border-pink-200',
            'sports' => 'bg-orange-100 text-orange-700 border border-orange-200',
            'religious' => 'bg-purple-100 text-purple-700 border border-purple-200',
            'social' => 'bg-yellow-100 text-yellow-700 border border-yellow-200',
            'government' => 'bg-red-100 text-red-700 border border-red-200',
            'business' => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
            'automotive' => 'bg-cyan-100 text-cyan-700 border border-cyan-200',
            'motorcycle' => 'bg-amber-100 text-amber-700 border border-amber-200',
            'other' => 'bg-gray-100 text-gray-700 border border-gray-200',
        ];
        
        return $colors[$category] ?? $colors['other'];
    }
}