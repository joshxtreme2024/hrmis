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
            'status' => 'required|in:active,inactive',
        ]);

        Departments::create($validatedData);

        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    public function edit(Departments $department)
    {
        return view('departments.edit', compact('department'));
    }
}
