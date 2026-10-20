<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LeaveType extends Model
{
    protected $fillable = ['code', 'name', 'legal_basis', 'is_credit_based', 'is_active'];

    protected $casts = [
        'is_credit_based' => 'boolean',
        'is_active'       => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** True when any leave application or credit record uses this type. */
    public function isInUse(): bool
    {
        return DB::table('leave_applications')->where('leave_type_id', $this->id)->exists()
            || DB::table('leave_credits')->where('leave_type_id', $this->id)->exists();
    }
}