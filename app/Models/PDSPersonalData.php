<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDSPersonalData extends Model
{
    protected $table = 'pds_personal_data';

    protected $fillable = [
        'user_id',
        'emp_id',
        'first_name',
        'middle_name',
        'last_name',
        'ext_name',
        'birth_date',
        'place_of_birth',
        'sex',
        'civil_status',
        'height',
        'weight',
        'blood_type',
        'deleted_at',
        'telephone_no',
        'mobile_no',
        'photo_path',
        'account_status',
        'nationality',
        'religion',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class, 'user_id');
    }

    public function employment()
    {
        return $this->hasOne(PDSEmployment::class, 'user_id');
    }

    public function completeName()
    {
        $fullName = $this->first_name;

        if (!empty($this->middle_name)) {
            $fullName .= ' ' . $this->middle_name;
        }

        $fullName .= ' ' . $this->last_name;

        if (!empty($this->ext_name)) {
            $fullName .= ' ' . $this->ext_name;
        }

        return $fullName;
    }

    public function semiCompleteName()
    {
        $fullName = $this->first_name;

        if (!empty($this->middle_name)) {
            $fullName .= ' ' . strtoupper(substr($this->middle_name, 0, 1));
        }

        $fullName .= ' ' . $this->last_name;

        if (!empty($this->ext_name)) {
            $fullName .= ' ' . $this->ext_name;
        }

        return $fullName;
    }

    public function getInitialsAttribute()
    {
        $initials = '';

        if (!empty($this->first_name)) {
            $initials .= strtoupper(substr($this->first_name, 0, 1));
        }

        if (!empty($this->middle_name)) {
            $initials .= strtoupper(substr($this->middle_name, 0, 1));
        }

        if (!empty($this->last_name)) {
            $initials .= strtoupper(substr($this->last_name, 0, 1));
        }

        return $initials;
    }
}
