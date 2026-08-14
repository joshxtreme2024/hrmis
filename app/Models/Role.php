<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'label', 'description'];
    
    /**
     * ✅ Get the permissions for the role.
     * Correct pivot table name: role_has_permissions
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions');
    }
    
    /**
     * ❌ REMOVE THIS - You don't need it since users have a role column
     * public function users()
     * {
     *     return $this->belongsToMany(User::class, 'role');
     * }
     */
    
    /**
     * ✅ Get all users with this role (using the role column)
     */
    public function getUsers()
    {
        return User::where('role', $this->name)->get();
    }
    
    /**
     * ✅ Get the count of users with this role
     */
    public function getUsersCountAttribute()
    {
        return User::where('role', $this->name)->count();
    }
    
    /**
     * ✅ Check if role has a permission
     */
    public function hasPermission($permissionName)
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }
    
    /**
     * ✅ Assign a permission to the role
     */
    public function assignPermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->firstOrFail();
        }
        
        $this->permissions()->syncWithoutDetaching([$permission->id]);
    }
    
    /**
     * ✅ Remove a permission from the role
     */
    public function removePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->firstOrFail();
        }
        
        $this->permissions()->detach($permission->id);
    }
    
    /**
     * ✅ Sync permissions for the role
     */
    public function syncPermissions(array $permissions)
    {
        $permissionIds = [];
        foreach ($permissions as $perm) {
            if (is_string($perm)) {
                $permission = Permission::where('name', $perm)->first();
                if ($permission) {
                    $permissionIds[] = $permission->id;
                }
            } else {
                $permissionIds[] = $perm;
            }
        }
        
        $this->permissions()->sync($permissionIds);
    }
}