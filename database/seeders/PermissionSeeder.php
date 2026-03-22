<?php
// database/seeders/PermissionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions (using firstOrCreate to avoid duplicates)
        $permissions = [
            // Employee Management
            'view-employees',
            'create-employees',
            'edit-employees',
            'delete-employees',
            
            // Attendance
            'view-attendance',
            'create-attendance',
            'edit-attendance',
            'delete-attendance',
            
            // Leave Management
            'view-leave',
            'create-leave',
            'approve-leave',
            'delete-leave',
            
            // Payroll
            'view-payroll',
            'create-payroll',
            'edit-payroll',
            'delete-payroll',
            
            // Reports
            'view-reports',
            'generate-reports',
            'export-reports',
            
            // Settings
            'manage-settings',
            'manage-users',
            'manage-roles',
            'manage-system',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());

        $hrRole = Role::firstOrCreate(['name' => 'hr_manager']);
        $hrRole->syncPermissions([
            'view-employees',
            'create-employees',
            'edit-employees',
            'view-attendance',
            'create-attendance',
            'view-leave',
            'approve-leave',
            'view-reports',
            'manage-system',
        ]);

        $employeeRole = Role::firstOrCreate(['name' => 'employee']);
        $employeeRole->syncPermissions([
            'view-attendance',
            'view-leave',
            'create-leave',
        ]);

        $financeRole = Role::firstOrCreate(['name' => 'finance']);
        $financeRole->syncPermissions([
            'view-payroll',
            'view-reports',
            'export-reports',
        ]);

        // Assign admin role to first user (if exists)
        $user = User::first();
        if ($user) {
            $user->assignRole('admin');
            $this->command->info('Admin role assigned to user: ' . $user->email);
        }
        
        $this->command->info('Permissions and roles synced successfully!');
    }
}