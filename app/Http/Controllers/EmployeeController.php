<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PDSEmployment;
use App\Models\Departments;
use App\Models\Positions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        // Load users with their relationships
        $query = User::with(['employment', 'personalData']);

        // Search across user fields and related models
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            
            $query->where(function($q) use ($searchTerm) {
                // Search in User model fields
                $q->where('email', 'LIKE', "%{$searchTerm}%");
                
                // Search in PersonalData model (personal information)
                $q->orWhereHas('personalData', function($pd) use ($searchTerm) {
                    $pd->where('first_name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('middle_name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('ext_name', 'LIKE', "%{$searchTerm}%");
                });
                
                // Search in Employment model
                $q->orWhereHas('employment', function($emp) use ($searchTerm) {
                    $emp->where('employee_id', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('position_id', 'LIKE', "%{$searchTerm}%");
                });
            });
        }

        // Employment Filters
        if ($request->filled('department')) {
            $query->whereHas('employment', function($q) use ($request) {
                $q->where('department_id', $request->department);
            });
        }

        if ($request->filled('status')) {
            $query->whereHas('employment', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        if ($request->filled('position')) {
            $query->whereHas('employment', function($q) use ($request) {
                $q->where('position_id', $request->position);
            });
        }

        $employees = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistics
        $stats = [
            'total' => User::count(),
            'active' => User::whereHas('employment', function($q) {
                $q->where('status', 'active');
            })->count(),
            'on_leave' => User::whereHas('employment', function($q) {
                $q->where('status', 'on_leave');
            })->count(),
            'new_this_month' => User::whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->count(),
        ];

        $departments = Departments::all();
        $positions = Positions::all();

        return view('management.employees.index', compact(
            'employees', 'stats', 'departments', 'positions'
        ));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
            // Validate User Data
            $userData = $request->validate([
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'date_of_birth' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other',
                'civil_status' => 'nullable|string|max:50',
                'email' => 'required|email|unique:users,email',
                'contact_number' => 'required|string|max:20',
                'address' => 'nullable|string',
                'profile_picture' => 'nullable|image|max:2048',
                'password' => 'required|string|min:8|confirmed',
            ]);

            // Validate Employment Data
            $employmentData = $request->validate([
                'department_id' => 'required|exists:departments,id',
                'position_id' => 'required|exists:positions,id',
                'status' => 'required|in:active,inactive,resigned,retired,on_leave,suspended,terminated',
                'employment_type' => 'nullable|in:permanent,temporary,contractual,casual,job_order,consultant,co_term',
                'hired_at' => 'nullable|date',
                'date_of_original_appointment' => 'nullable|date',
                'date_of_last_promotion' => 'nullable|date',
                'salary' => 'nullable|numeric|min:0',
                'salary_grade' => 'nullable|string|max:50',
                'step_increment' => 'nullable|integer|min:0',
            ]);

            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                $path = $request->file('profile_picture')->store('employee-profiles', 'public');
                $userData['profile_picture'] = $path;
            }

            // Hash password
            $userData['password'] = Hash::make($userData['password']);
            
            // Generate employee ID
            $userData['employee_id'] = 'EMP-' . str_pad(User::count() + 1, 6, '0', STR_PAD_LEFT);

            // Create User record
            $user = User::create($userData);

            // Create Employment record linked to User
            $employmentData['user_id'] = $user->id;
            $employmentData['employee_id'] = $user->employee_id; // Sync employee_id
            PDSEmployment::create($employmentData);

            DB::commit();

            return redirect()->route('management.employees.index')
                           ->with('success', 'Employee created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                           ->with('error', 'Failed to create employee: ' . $e->getMessage())
                           ->withInput();
        }
    }

    public function show(User $employee)
    {
        // Load employment relationship
        $employee->load('employment.department', 'employment.position');
        
        return view('management.employees.show', compact('employee'));
    }

    public function edit(User $employee)
    {
        // Load employment relationship for editing
        $employee->load('employment');
        
        $departments = Departments::all();
        $positions = Positions::all();
        
        return view('management.employees.edit', compact('employee', 'departments', 'positions'));
    }

    public function update(Request $request, User $employee)
    {
        DB::beginTransaction();
        
        try {
            // Validate User Data
            $userData = $request->validate([
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'date_of_birth' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other',
                'civil_status' => 'nullable|string|max:50',
                'email' => 'required|email|unique:users,email,' . $employee->id,
                'contact_number' => 'required|string|max:20',
                'address' => 'nullable|string',
                'profile_picture' => 'nullable|image|max:2048',
            ]);

            // Validate Employment Data
            $employmentData = $request->validate([
                'department_id' => 'required|exists:departments,id',
                'position_id' => 'required|exists:positions,id',
                'status' => 'required|in:active,inactive,resigned,retired,on_leave,suspended,terminated',
                'employment_type' => 'nullable|in:permanent,temporary,contractual,casual,job_order,consultant,co_term',
                'hired_at' => 'nullable|date',
                'date_of_original_appointment' => 'nullable|date',
                'date_of_last_promotion' => 'nullable|date',
                'salary' => 'nullable|numeric|min:0',
                'salary_grade' => 'nullable|string|max:50',
                'step_increment' => 'nullable|integer|min:0',
            ]);

            // Handle profile picture update
            if ($request->hasFile('profile_picture')) {
                // Delete old profile picture if exists
                if ($employee->profile_picture) {
                    Storage::disk('public')->delete($employee->profile_picture);
                }
                $path = $request->file('profile_picture')->store('employee-profiles', 'public');
                $userData['profile_picture'] = $path;
            }

            // Update User
            $employee->update($userData);

            // Update Employment Data
            if ($employee->employment) {
                $employee->employment->update($employmentData);
            } else {
                // Create employment if it doesn't exist
                $employmentData['user_id'] = $employee->id;
                $employmentData['employee_id'] = $employee->employee_id;
                PDSEmployment::create($employmentData);
            }

            DB::commit();

            return redirect()->route('management.employees.index')
                           ->with('success', 'Employee updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                           ->with('error', 'Failed to update employee: ' . $e->getMessage())
                           ->withInput();
        }
    }

    public function destroy(User $employee)
    {
        DB::beginTransaction();
        
        try {
            // Delete profile picture if exists
            if ($employee->profile_picture) {
                Storage::disk('public')->delete($employee->profile_picture);
            }
            
            // Delete employment record first (if exists)
            if ($employee->employment) {
                $employee->employment->delete();
            }
            
            // Delete user
            $employee->delete();

            DB::commit();

            return redirect()->route('management.employees.index')
                           ->with('success', 'Employee deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                           ->with('error', 'Failed to delete employee: ' . $e->getMessage());
        }
    }

    // Get employee details for AJAX view
    public function getEmployeeDetails($id)
    {
        $employee = User::with(['employment.department', 'employment.position'])
                                  ->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $employee
        ]);
    }

    // Update employment status
    public function updateEmploymentStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,resigned,retired,on_leave,suspended,terminated',
            'remarks' => 'nullable|string'
        ]);

        $employee = User::findOrFail($id);
        
        if ($employee->employment) {
            $employee->employment->update([
                'status' => $request->status,
                'status_remarks' => $request->remarks
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Employment status updated successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Employment record not found'
        ], 404);
    }

    // Bulk actions
    public function bulkAction(Request $request)
    {
        $request->validate([
            'employee_ids' => 'required|array',
            'action' => 'required|in:delete,export,status_update',
            'status' => 'required_if:action,status_update'
        ]);

        $employeeIds = $request->employee_ids;
        
        DB::beginTransaction();
        
        try {
            switch ($request->action) {
                case 'delete':
                    User::whereIn('id', $employeeIds)->delete();
                    break;
                case 'status_update':
                    User::whereIn('id', $employeeIds)->each(function($employee) use ($request) {
                        if ($employee->employment) {
                            $employee->employment->update([
                                'status' => $request->status
                            ]);
                        }
                    });
                    break;
                case 'export':
                    // Handle export logic
                    break;
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Bulk action completed successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to perform bulk action: ' . $e->getMessage()
            ], 500);
        }
    }
}