<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDSChildren extends Model
{
    protected $table = 'pds_children';
    protected $fillable = [
        'user_id','order','name','date_of_birth','sex',
    ];
    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
