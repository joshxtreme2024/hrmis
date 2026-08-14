<?php

namespace App\Http\Controllers;

use App\Models\PDSEducation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EducationController extends Controller
{
    public function getEditData($id)
    {
        try {
            $education = PDSEducation::where('user_id', Auth::id())->findOrFail($id);
            
            return response()->json([
                'id' => $education->id,
                'level' => $education->level,
                'school_name' => $education->school_name,
                'school_address' => $education->school_address,
                'degree_course' => $education->degree_course,
                'period_from' => $education->period_from,
                'period_to' => $education->period_to,
                'highest_level_earned' => $education->highest_level_earned,
                'year_graduated' => $education->year_graduated,
                'scholarship_honors' => $education->scholarship_honors,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Education record not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'level' => 'required|string|max:60',
            'school_name' => 'required|string|max:255',
            'school_address' => 'nullable|string|max:255',
            'degree_course' => 'nullable|string|max:255',
            'period_from' => 'required|integer|min:1950|max:' . date('Y'),
            'period_to' => 'required|integer|min:1950|max:' . date('Y'),
            'highest_level_earned' => 'nullable|string|max:255',
            'year_graduated' => 'required|integer|min:1950|max:' . date('Y'),
            'scholarship_honors' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = Auth::id();
        
        $education = PDSEducation::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Education record added successfully',
                'education' => $education
            ]);
        }

        return redirect()->back()->with('success', 'Education record added successfully');
    }

    public function update(Request $request, $id)
    {
        $education = PDSEducation::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'level' => 'required|string|max:60',
            'school_name' => 'required|string|max:255',
            'school_address' => 'nullable|string|max:255',
            'degree_course' => 'nullable|string|max:255',
            'period_from' => 'required|integer|min:1950|max:' . date('Y'),
            'period_to' => 'required|integer|min:1950|max:' . date('Y'),
            'highest_level_earned' => 'nullable|string|max:255',
            'year_graduated' => 'required|integer|min:1950|max:' . date('Y'),
            'scholarship_honors' => 'nullable|string|max:255',
        ]);

        $education->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Education record updated successfully',
                'education' => $education
            ]);
        }

        return redirect()->back()->with('success', 'Education record updated successfully');
    }

    public function destroy($id)
    {
        $education = PDSEducation::where('user_id', Auth::id())->findOrFail($id);
        $education->delete();

        return redirect()->back()->with('success', 'Education record deleted successfully');
    }
}