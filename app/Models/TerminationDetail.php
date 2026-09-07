<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TerminationDetail extends Model
{
    protected $fillable = [
        'user_id','termination_date','termination_reason','remarks','created_by',
    ];

    protected $casts = [
        'termination_date' => 'date',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id'); 
    }

    public function createdBy(){
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
