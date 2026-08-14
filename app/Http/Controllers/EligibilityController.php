<?php

namespace App\Http\Controllers;

use App\Models\PDSEligibility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EligibilityController extends Controller
{
    public function getEditData($id)
    {
        try {
            $eligibility = PDSEligibility::where('user_id', Auth::id())->findOrFail($id);
            
            return response()->json([
                'id' => $eligibility->id,
                'order' => $eligibility->order,
                'career_service' => $eligibility->career_service,
                'rating' => $eligibility->rating,
                'examination_date' => $eligibility->examination_date ? $eligibility->examination_date->format('Y-m-d') : null,
                'examination_place' => $eligibility->examination_place,
                'license_number' => $eligibility->license_number,
                'license_date_validity' => $eligibility->license_date_validity ? $eligibility->license_date_validity->format('Y-m-d') : null,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Eligibility record not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order' => 'nullable|integer|min:1',
            'career_service' => 'required|string|max:255',
            'rating' => 'nullable|string|max:50',
            'examination_date' => 'nullable|date',
            'examination_place' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:100',
            'license_date_validity' => 'nullable|date|after_or_equal:examination_date',
        ]);

        $validated['user_id'] = Auth::id();
        if (!isset($validated['order'])) {
            $validated['order'] = 0;
        }

        $eligibility = PDSEligibility::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Eligibility added successfully',
                'eligibility' => $eligibility
            ]);
        }

        return redirect()->back()->with('success', 'Eligibility added successfully');
    }

    public function update(Request $request, $id)
    {
        $eligibility = PDSEligibility::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'order' => 'nullable|integer|min:1',
            'career_service' => 'required|string|max:255',
            'rating' => 'nullable|string|max:50',
            'examination_date' => 'nullable|date',
            'examination_place' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:100',
            'license_date_validity' => 'nullable|date|after_or_equal:examination_date',
        ]);
        if (!isset($validated['order'])) {
            $validated['order'] = 0;
        }
        $eligibility->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Eligibility updated successfully',
                'eligibility' => $eligibility
            ]);
        }

        return redirect()->back()->with('success', 'Eligibility updated successfully');
    }

    public function destroy($id)
    {
        $eligibility = PDSEligibility::where('user_id', Auth::id())->findOrFail($id);
        $eligibility->delete();

        return redirect()->back()->with('success', 'Eligibility deleted successfully');
    }
}