<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    protected $table = 'departments';
    protected $fillable = [
        'name','code','description','status',
    ];

    public function positions(){
        return $this->hasMany(Positions::class, 'department_id');
    }

    public function users()
    {
        return $this->hasManyThrough(
            User::class,
            PDSEmployment::class,
            'department_id', // Foreign key on employment table
            'id', // Foreign key on users table
            'id', // Local key on departments table
            'user_id' // Local key on employment table
        );
    }
}
