<?php

namespace App\Http\Controllers;

use App\Models\Positions;
use App\Models\Departments;
use App\Models\JobLevels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PositionsController extends Controller
{
    /**
     * Display a listing of the positions.
     */
    public function index()
    {
         $positions = Positions::paginate(10);
        return view('positions.index', compact('positions'));
    }

    /**
     * Show the form for creating a new position.
     */
    public function create()
    {   
        $joblevels = JobLevels::orderBy('sort_order')->get();
        $departments = Departments::orderBy('name')->get();
        $positions = Positions::orderBy('title')->get(); // For reports_to dropdown
        
        return view('positions.create', compact('departments', 'positions', 'joblevels'));
    }

    /**
     * Store a newly created position in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:positions,title',
            'department_id' => 'required|exists:departments,id',
            'job_level_id' => 'required|string|max:50',
            'description' => 'nullable|string',
            'status' => 'required|in:enabled,disabled',
            'salary_grade' => 'nullable|string|max:50',
            'reports_to_id' => 'nullable|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }

        // Create the position
        $position = Positions::create([
            'title' => $request->title,
            'department_id' => $request->department_id,
            'job_level_id' => $request->job_level_id,
            'description' => $request->description,
            'status' => $request->status,
            'salary_grade' => $request->salary_grade,
            'reports_to_id' => $request->reports_to_id,
        ]);

        // Redirect with success message
        return redirect()->route('positions.index')
                        ->with('success', 'Position "' . $position->title . '" created successfully.');
    }

    /**
     * Display the specified position.
     */
    public function show(Positions $position)
    {
        $position->load(['department', 'reportsTo']);
        
        return view('positions.show', compact('position'));
    }

    /**
     * Show the form for editing the specified position.
     */
    public function edit(Positions $position)
    {
        $joblevels = JobLevels::orderBy('sort_order')->get();
        $departments = Departments::orderBy('name')->get();
        $positions = Positions::where('id', '!=', $position->id)
                            ->orderBy('title')
                            ->get();
        
        return view('positions.edit', compact('position', 'departments', 'positions', 'joblevels'));
    }

    /**
     * Update the specified position in storage.
     */
    public function update(Request $request, Positions $position)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:positions,title,' . $position->id,
            'department_id' => 'required|exists:departments,id',
            'job_level_id' => 'required|exists:job_levels,id',
            'description' => 'nullable|string',
            'status' => 'required|in:enabled,disabled',
            'salary_grade' => 'nullable|string|max:50',
            'reports_to_id' => 'nullable|exists:positions,id|not_in:' . $position->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }

        // Update the position
        $position->update([
            'title' => $request->title,
            'department_id' => $request->department_id,
            'job_level_id' => $request->job_level_id,
            'description' => $request->description,
            'status' => $request->status,
            'salary_grade' => $request->salary_grade,
            'reports_to_id' => $request->reports_to_id,
        ]);

        // Redirect with success message
        return redirect()->route('positions.index')
                        ->with('success', 'Position "' . $position->title . '" updated successfully.');
    }

    /**
     * Remove the specified position from storage.
     */
    public function disable(Positions $position)
    {
        // Check if there are any positions reporting to this one
        $hasSubordinates = Positions::where('reports_to_id', $position->id)->exists();
        
        if ($hasSubordinates) {
            return redirect()->route('positions.index')
                            ->with('error', 'Cannot disable this position because other positions report to it.');
        }

        $title = $position->title;
        $position->update([
            'status' => 'disabled',
        ]);

        return redirect()->route('positions.index')
                        ->with('success', 'Position "' . $title . '" disabled successfully.');
    }

    public function enable(Positions $position)
    {
        $title = $position->title;
        $position->update([
            'status' => 'enabled',
        ]);

        return redirect()->route('positions.index')
                        ->with('success', 'Position "' . $title . '" enabled successfully.');
    }
}