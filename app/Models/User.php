<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'password',
        'role',
        'is_approved',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function personalData(){
        return $this->hasOne(PDSPersonalData::class, 'user_id');
    }

    public function family(){
        return $this->hasOne(PDSFamilyBackground::class, 'user_id');
    }

    public function children(){
        return $this->hasMany(PDSChildren::class, 'user_id');
    }

    public function education(){
        return $this->hasMany(PDSEducation::class, 'user_id');
    }
    public function getDisplayNameAttribute()
    {
        if ($this->personalDataSheet) {
            return $this->personalDataSheet->completeName();
        }
        return $this->name;
    }

    public function getDisplayInitialsAttribute()
    {
        if ($this->personalDataSheet) {
            return $this->personalDataSheet->getInitialsAttribute();
        }
        return $this->name;
    }

    /**
     * ✅ Get the role instance for this user
     */
    public function roleInstance()
    {
        return $this->belongsTo(Role::class, 'role', 'name');
    }

    /**
     * ✅ Check if user has a specific role
     */
    public function hasRole($roleName)
    {
        return $this->role === $roleName;
    }

    /**
     * ✅ Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles)
    {
        return in_array($this->role, $roles);
    }

    /**
     * ✅ Check if user has a permission
     */
    public function hasPermission($permissionName)
    {
        // Get the role instance
        $role = Role::where('name', $this->role)->first();
        
        if (!$role) {
            return false;
        }
        
        // Check if the role has the permission
        return $role->hasPermission($permissionName);
    }

    /**
     * ✅ Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions)
    {
        $role = Role::where('name', $this->role)->first();
        
        if (!$role) {
            return false;
        }
        
        $rolePermissions = $role->permissions()->pluck('name')->toArray();
        
        foreach ($permissions as $permission) {
            if (in_array($permission, $rolePermissions)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * ✅ Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * ✅ Check if user is HR
     */
    public function isHr()
    {
        return $this->role === 'hr';
    }

    /**
     * ✅ Check if user is employee
     */
    public function isEmployee()
    {
        return $this->role === 'employee';
    }

    /**
     * ✅ Check if user is approved
     */
    public function isApproved()
    {
        return $this->is_approved === true || $this->is_approved === 1;
    }

    /**
     * ✅ Check if user is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * ✅ Get role label
     */
    public function getRoleLabelAttribute()
    {
        $role = Role::where('name', $this->role)->first();
        return $role ? $role->label : ucfirst($this->role);
    }

    /**
     * ✅ Get status label
     */
    public function getStatusLabelAttribute()
    {
        $statuses = [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'suspended' => 'Suspended',
            'pending' => 'Pending',
        ];

        return $statuses[$this->status] ?? ucfirst($this->status);
    }

    /**
     * ✅ Get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        $colors = [
            'active' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
            'inactive' => 'bg-gray-100 dark:bg-gray-700/30 text-gray-700 dark:text-gray-400',
            'suspended' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
            'pending' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
        ];

        return $colors[$this->status] ?? 'bg-gray-100 dark:bg-gray-700/30 text-gray-700 dark:text-gray-400';
    }
}
