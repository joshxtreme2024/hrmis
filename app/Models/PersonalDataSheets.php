<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalDataSheets extends Model
{
    protected $table = 'pds_personal_data';

    protected $fillable = [
            'emp_id',
            'user_id',
            'first_name',
            'middle_name',
            'last_name',
            'ext_name',
            'date_of_birth',
            'place_of_birth',
            'sex',
            'civil_status',
            'height_m',
            'weight_kg',
            'blood_type',
            'deleted_at',
            'telephone_no',
            'mobile_no',
            'photo_path',
            'account_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function position()
    {
        return $this->belongsTo(Positions::class, 'position_id');
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
