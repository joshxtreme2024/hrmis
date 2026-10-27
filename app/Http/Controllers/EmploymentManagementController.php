<?php

namespace App\Http\Controllers;

use App\Models\PDSEmployment;
use App\Models\Departments;
use App\Models\Positions;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmploymentManagementController extends Controller
{
    public function edit(PDSEmployment $employee)
    {
        abort_if(!$employee, 404, 'No employment record to edit.');
        
        $departments = Departments::orderBy('name')->get(['id', 'name']);
        $positions   = Positions::orderBy('title')->get(['id', 'title']);
        return view('management.employees.edit', compact('employee', 'departments', 'positions'));
    }

    public function update(Request $request, PDSEmployment $employee)
    {
        abort_if(!$employee, 404);
        abort_if($employee->status === 'terminated', 403, 'Reactivate employment before editing.');

        $data = $request->validate([
            'employee_id'     => ['required', 'string', 'max:50', Rule::unique('pds_employment_info', 'employee_id')->ignore($employee->id)],
            'employment_type' => ['required', Rule::in(['permanent','temporary','contractual','casual','job_order','co_terminus','consultant'])],
            'department_id'   => ['nullable', 'exists:departments,id'],
            'position_id'     => ['nullable', 'exists:positions,id'],
            'hired_at'        => ['required', 'date'],
            'contract_end'    => ['nullable', 'date', 'after_or_equal:hired_at'],
            'salary'          => ['nullable', 'numeric', 'min:0'],
        ]);

        $employee->update($data);

        return redirect()
            ->route('employees.showProfile', $employee->user_id)
            ->with('success', 'Employment record updated successfully.');
    }
}
