<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalDataSheets extends Model
{
    protected $table = 'personal_data_sheets';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'middle_name',
        'date_of_birth',
        'place_of_birth',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
