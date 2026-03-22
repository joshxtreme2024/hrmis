<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Positions extends Model
{
    protected $table = 'positions';
    protected $fillable = [
        'title','department_id','level','description','status','salary_grade','reports_to_id'
    ];

    public function employee(){
        return $this->hasMany(PersonalDataSheets::class, 'position_id');
    }
}
