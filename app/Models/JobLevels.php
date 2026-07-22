<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobLevels extends Model
{
    protected $table ='job_levels';
    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
        'is_active',
    ];

    public function positions(){
        return $this->hasMany(Positions::class, 'job_level_id');
    }

}
