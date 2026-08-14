<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'label', 'description'];
    
    /**
     * ✅ Get the roles that have this permission.
     * Correct pivot table name: role_has_permissions
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions');
    }
    
    /**
     * ✅ Check if permission is assigned to a role
     */
    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }
}