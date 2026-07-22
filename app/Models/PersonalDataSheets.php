<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalDataSheets extends Model
{
    protected $table = 'pds_personal_data';

    protected $fillable = [
            'emp_id',
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
        return $this->belongsTo(User::class, 'emp_id');
    }
    public function position()
    {
        return $this->belongsTo(Positions::class, 'position_id');
    }
}
