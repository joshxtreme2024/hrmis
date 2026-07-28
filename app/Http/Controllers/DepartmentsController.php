<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departments;

class DepartmentsController extends Controller
{
    public function index()
    {
         $departments = Departments::paginate(10);
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|max:10|unique:departments,code',
            'status' => 'required|in:enabled,disabled',
        ]);

        Departments::create($validatedData);

        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    public function edit(Departments $department)
    {
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Departments $department)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|max:10|unique:departments,code,' . $department->id,
            'status' => 'required|in:enabled,disabled',
        ]);

        $department->update($validatedData);

        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(Departments $department)
    {
        try {
            $department->delete();
            return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Failed to delete department: ' . $e->getMessage());
            return redirect()->route('departments.index')->with('error', 'Failed to delete department. It may be associated with other records.');
        }
    }

    /**
     * Enable a department.
     */
    public function enable(Departments $department)
    {
        try {
            $department->update(['status' => 'enabled']);
            
            // Flash success message
            session()->flash('success', 'Department enabled successfully.');
            
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('Failed to enable department: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Failed to enable department.');
        }
    }

    /**
     * Disable a department.
     */
    public function disable(Departments $department)
    {
        try {
            $department->update(['status' => 'disabled']);
            
            // Flash success message
            session()->flash('success', 'Department disabled successfully.');
            
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('Failed to disable department: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Failed to disable department.');
        }
    }
}
