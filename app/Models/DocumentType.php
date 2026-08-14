<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $fillable = [
        'code',
        'name',
        'category',
        'description',
        'is_required',
        'is_active',
        'sort_order',
        'csc_circular'
    ];

    // Scope for active document types
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
