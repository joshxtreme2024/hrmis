<?php

namespace App\Http\Controllers;

use App\Models\PDSWorkExperience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkExperienceController extends Controller
{
    public function getEditData($id)
    {
        try {
            $work = PDSWorkExperience::where('user_id', Auth::id())->findOrFail($id);
            
            return response()->json([
                'id' => $work->id,
                'order' => $work->order,
                'inclusive_from' => $work->inclusive_from ? $work->inclusive_from->format('Y-m-d') : null,
                'inclusive_to' => $work->inclusive_to ? $work->inclusive_to->format('Y-m-d') : null,
                'position_title' => $work->position_title,
                'department_agency_office' => $work->department_agency_office,
                'monthly_salary' => $work->monthly_salary,
                'salary_grade' => $work->salary_grade,
                'status_of_appointment' => $work->status_of_appointment,
                'is_gov' => $work->is_gov,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Work experience record not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order' => 'nullable|integer|min:1',
            'inclusive_from' => 'required|date',
            'inclusive_to' => 'nullable|date|after:inclusive_from',
            'position_title' => 'required|string|max:255',
            'department_agency_office' => 'required|string|max:255',
            'monthly_salary' => 'nullable|numeric|min:0',
            'salary_grade' => 'nullable|string|max:50',
            'status_of_appointment' => 'nullable|string|max:50',
            'is_gov' => 'required|string|max:3',
        ]);
        if (!isset($validated['order'])) {
            $validated['order'] = 0;
        }
        $validated['user_id'] = Auth::id();
        
        $work = PDSWorkExperience::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Work experience added successfully',
                'work' => $work
            ]);
        }

        return redirect()->back()->with('success', 'Work experience added successfully');
    }

    public function update(Request $request, $id)
    {
        $work = PDSWorkExperience::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'order' => 'nullable|integer|min:1',
            'inclusive_from' => 'required|date',
            'inclusive_to' => 'nullable|date|after:inclusive_from',
            'position_title' => 'required|string|max:255',
            'department_agency_office' => 'required|string|max:255',
            'monthly_salary' => 'nullable|numeric|min:0',
            'salary_grade' => 'nullable|string|max:50',
            'status_of_appointment' => 'nullable|string|max:50',
            'is_gov' => 'required|string|max:3',
        ]);
        if (!isset($validated['order'])) {
            $validated['order'] = 0;
        }
        $work->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Work experience updated successfully',
                'work' => $work
            ]);
        }

        return redirect()->back()->with('success', 'Work experience updated successfully');
    }

    public function destroy($id)
    {
        $work = PDSWorkExperience::where('user_id', Auth::id())->findOrFail($id);
        $work->delete();

        return redirect()->back()->with('success', 'Work experience deleted successfully');
    }
}