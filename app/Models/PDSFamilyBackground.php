<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDSFamilyBackground extends Model
{
    protected $table ="pds_family_background";
    protected $fillable = [
        'user_id',
        'spouse_first_name',
        'spouse_middle_name',
        'spouse_last_name',
        'spouse_name_extension',
        'spouse_occupation',
        'spouse_employer_business',
        'spouse_business_address',
        'spouse_telephone_no',
        'father_first_name',
        'father_middle_name',
        'father_last_name',
        'father_name_extension',
        'mother_first_name',
        'mother_middle_name',
        'mother_last_name',
        'mother_maiden_last_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
