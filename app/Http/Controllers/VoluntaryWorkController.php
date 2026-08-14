<?php

namespace App\Http\Controllers;

use App\Models\PDSVoluntaryWork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoluntaryWorkController extends Controller
{
    public function getEditData($id)
    {
        try {
            $work = PDSVoluntaryWork::where('user_id', Auth::id())->findOrFail($id);
            
            return response()->json([
                'id' => $work->id,
                'order' => $work->order,
                'organization_name' => $work->organization_name,
                'organization_address' => $work->organization_address,
                'inclusive_from' => $work->inclusive_from ? $work->inclusive_from->format('Y-m-d') : null,
                'inclusive_to' => $work->inclusive_to ? $work->inclusive_to->format('Y-m-d') : null,
                'number_of_hours' => $work->number_of_hours,
                'position_nature_of_work' => $work->position_nature_of_work,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Voluntary work record not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order' => 'nullable|integer|min:1',
            'organization_name' => 'required|string|max:255',
            'organization_address' => 'nullable|string|max:255',
            'inclusive_from' => 'required|date',
            'inclusive_to' => 'nullable|date|after_or_equal:inclusive_from',
            'number_of_hours' => 'nullable|numeric|min:0',
            'position_nature_of_work' => 'required|string|max:255',
        ]);

        // ✅ If inclusive_to is null or empty, set it to inclusive_from
        // This prevents database error if column is NOT NULL
        if (empty($validated['inclusive_to'])) {
            $validated['inclusive_to'] = $validated['inclusive_from'];
        }

        $validated['user_id'] = Auth::id();
        if (!isset($validated['order'])) {
            $validated['order'] = 0;
        }
        $work = PDSVoluntaryWork::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Voluntary work added successfully',
                'voluntary_work' => $work
            ]);
        }

        return redirect()->back()->with('success', 'Voluntary work added successfully');
    }

    public function update(Request $request, $id)
    {
        $work = PDSVoluntaryWork::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'order' => 'nullable|integer|min:1',
            'organization_name' => 'required|string|max:255',
            'organization_address' => 'nullable|string|max:255',
            'inclusive_from' => 'required|date',
            'inclusive_to' => 'nullable|date|after_or_equal:inclusive_from',
            'number_of_hours' => 'nullable|numeric|min:0',
            'position_nature_of_work' => 'required|string|max:255',
        ]);

        // ✅ If inclusive_to is null or empty, set it to inclusive_from
        if (empty($validated['inclusive_to'])) {
            $validated['inclusive_to'] = $validated['inclusive_from'];
        }
        if (!isset($validated['order'])) {
            $validated['order'] = 0;
        }
        $work->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Voluntary work updated successfully',
                'voluntary_work' => $work
            ]);
        }

        return redirect()->back()->with('success', 'Voluntary work updated successfully');
    }

    public function destroy($id)
    {
        $work = PDSVoluntaryWork::where('user_id', Auth::id())->findOrFail($id);
        $work->delete();

        return redirect()->back()->with('success', 'Voluntary work deleted successfully');
    }
}