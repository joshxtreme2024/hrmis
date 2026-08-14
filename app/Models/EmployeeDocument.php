<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class EmployeeDocument extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'document_type_id',
        'title',
        'description',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'version',
        'document_date',
        'expiry_date',
        'reference_number',
        'issuing_authority',
        'received_from',
        'received_date',
        'metadata',
        'status',
        'remarks',
        'uploaded_by',
        'approved_by',
        'approved_at',
        'is_confidential',
        'is_original',
        'original_location',
        'version_number',
        'document_year',
    ];

    protected $casts = [
        'metadata' => 'array',
        'document_date' => 'date',
        'expiry_date' => 'date',
        'received_date' => 'date',
        'approved_at' => 'datetime',
        'is_confidential' => 'boolean',
        'is_original' => 'boolean'
    ];

    // Relationships
    public function employee()
    {
        return $this->belongsTo(PDSPersonalData::class, 'user_id');
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function versions()
    {
        return $this->hasMany(DocumentVersion::class);
    }

    // Accessors
    public function getFileUrlAttribute()
    {
        return Storage::disk('documents')->url($this->file_path);
    }

    public function getFileSizeFormattedAttribute()
    {
        if (!$this->file_size) return '0 B';
        $bytes = (int) $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => 'badge-secondary',
            'pending' => 'badge-warning',
            'approved' => 'badge-success',
            'rejected' => 'badge-danger',
            'expired' => 'badge-secondary',
            'archived' => 'badge-dark'
        ];
        return $badges[$this->status] ?? 'badge-secondary';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'archived');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->whereBetween('expiry_date', [now(), now()->addDays($days)]);
    }

    // Helper Methods
    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date < now();
    }

    public function isExpiringSoon($days = 30)
    {
        return $this->expiry_date && $this->expiry_date->diffInDays(now()) <= $days;
    }
}