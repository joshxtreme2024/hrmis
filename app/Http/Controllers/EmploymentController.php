<?php

namespace App\Http\Controllers;

use App\Models\PDSEmployment;
use App\Models\Positions;
use App\Models\Departments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmploymentController extends Controller
{
    public function getEditData($id)
    {
        try {
            $employee = PDSEmployment::where('user_id', Auth::id())->findOrFail($id);

            return response()->json([
                'id' => $employee->id,
                'employee_id' => $employee->employee_id,
                'position_id' => $employee->position_id,
                'department_id' => $employee->department_id,
                'hired_at' => $employee->hired_at?->format('Y-m-d'),
                'status' => $employee->status,
                'employment_type' => $employee->employment_type,
                'date_of_original_appointment' => $employee->date_of_original_appointment?->format('Y-m-d'),
                'date_of_last_promotion' => $employee->date_of_last_promotion?->format('Y-m-d'),
                'salary' => $employee->salary,
                'salary_grade' => $employee->salary_grade,
                'step_increment' => $employee->step_increment,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Employment record not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'nullable|string|max:50|unique:pds_employment_info,employee_id',
            'position_id' => 'required|exists:positions,id',
            'department_id' => 'required|exists:departments,id',
            'hired_at' => 'required|date',
            'status' => 'required|string|in:active,inactive,resigned,retired,on_leave,suspended,terminated',
            'employment_type' => 'required|string|in:permanent,temporary,contractual,casual,job_order,consultant,co_term',
            'date_of_original_appointment' => 'nullable|date|before_or_equal:today',
            'date_of_last_promotion' => 'nullable|date|after:date_of_original_appointment|before_or_equal:today',
            'salary' => 'nullable|numeric|min:0',
            'step_increment' => 'nullable|integer|min:1|max:8',
        ]);

        $validated['user_id'] = Auth::id();

        $employee = PDSEmployment::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Employment record added successfully',
                'employee' => $employee->load(['position', 'department'])
            ]);
        }

        return redirect()->route('myprofile.employment')
            ->with('success', 'Employment record added successfully');
    }

    public function update(Request $request, $id)
    {
        $employee = PDSEmployment::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'employee_id' => 'nullable|string|max:50|unique:pds_employment_info,employee_id,' . $id,
            'position_id' => 'required|exists:positions,id',
            'department_id' => 'required|exists:departments,id',
            'hired_at' => 'required|date',
            'status' => 'required|string|in:active,inactive,resigned,retired,on_leave,suspended,terminated',
            'employment_type' => 'required|string|in:permanent,temporary,contractual,casual,job_order,consultant,co_term',
            'date_of_original_appointment' => 'nullable|date|before_or_equal:today',
            'date_of_last_promotion' => 'nullable|date|after:date_of_original_appointment|before_or_equal:today',
            'salary' => 'nullable|numeric|min:0',
            'step_increment' => 'nullable|integer|min:1|max:8',
        ]);

        $employee->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Employment record updated successfully',
                'employee' => $employee->load(['position', 'department'])
            ]);
        }

        return redirect()->route('myprofile.employment')
            ->with('success', 'Employment record updated successfully');
    }
}