<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        $usersWithRoles = User::whereHas('roles')->count();
        
        return view('roles.index', compact('roles', 'permissions', 'usersWithRoles'));
    }

    public function getPermissions($roleId)
    {
        $role = Role::findOrFail($roleId);
        $allPermissions = Permission::all();
        $permissions = $role->permissions;
        
        return response()->json([
            'permissions' => $permissions,
            'allPermissions' => $allPermissions
        ]);
    }

    public function updatePermissions(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);
        
        // Prevent modifying admin role permissions
        if ($role->name === 'Admin') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot modify Admin role permissions'
            ], 403);
        }
        
        // Sync permissions using Spatie's method
        $role->syncPermissions($request->permissions);
        
        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles|max:255'
        ]);
        
        // Create role using Spatie
        Role::create(['name' => $request->name]);
        
        return redirect()->route('roles.index')->with('success', 'Role created successfully');
    }

    public function update(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);
        
        // Prevent modifying admin role
        if ($role->name === 'Admin') {
            return redirect()->back()->with('error', 'Cannot modify Admin role');
        }
        
        $request->validate([
            'name' => 'required|unique:roles,name,' . $roleId . '|max:255'
        ]);
        
        $role->update(['name' => $request->name]);
        
        return redirect()->route('roles.index')->with('success', 'Role updated successfully');
    }

    public function destroy($roleId)
    {
        $role = Role::findOrFail($roleId);
        
        // Prevent deleting admin role
        if ($role->name === 'Admin') {
            return redirect()->back()->with('error', 'Cannot delete Admin role');
        }
        
        // Check if role has users before deleting
        if ($role->users()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete role with assigned users');
        }
        
        $role->delete();
        
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
    }
}