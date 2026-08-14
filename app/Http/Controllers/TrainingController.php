<?php

namespace App\Http\Controllers;

use App\Models\PDSTraining;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingController extends Controller
{
    public function getEditData($id)
    {
        try {
            $training = PDSTraining::where('user_id', Auth::id())->findOrFail($id);
            
            return response()->json([
                'id' => $training->id,
                'order' => $training->order,
                'title_of_program' => $training->title_of_program,
                'inclusive_from' => $training->inclusive_from ? $training->inclusive_from->format('Y-m-d') : null,
                'inclusive_to' => $training->inclusive_to ? $training->inclusive_to->format('Y-m-d') : null,
                'number_of_hours' => $training->number_of_hours,
                'type_of_ld' => $training->type_of_ld,
                'conducted_by' => $training->conducted_by,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Training record not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order' => 'nullable|integer|min:1',
            'title_of_program' => 'required|string|max:255',
            'inclusive_from' => 'required|date',
            'inclusive_to' => 'nullable|date|after_or_equal:inclusive_from',
            'number_of_hours' => 'required|numeric|min:0.5',
            'type_of_ld' => 'nullable|string|max:100',
            'conducted_by' => 'required|string|max:255',
        ]);

        $validated['user_id'] = Auth::id();
        if (!isset($validated['order'])) {
            $validated['order'] = 0;
        }
        $training = PDSTraining::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Training added successfully',
                'training' => $training
            ]);
        }

        return redirect()->back()->with('success', 'Training added successfully');
    }

    public function update(Request $request, $id)
    {
        $training = PDSTraining::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'order' => 'nullable|integer|min:1',
            'title_of_program' => 'required|string|max:255',
            'inclusive_from' => 'required|date',
            'inclusive_to' => 'nullable|date|after_or_equal:inclusive_from',
            'number_of_hours' => 'required|numeric|min:0.5',
            'type_of_ld' => 'nullable|string|max:100',
            'conducted_by' => 'required|string|max:255',
        ]);
        
        if (!isset($validated['order'])) {
            $validated['order'] = 0;
        }
        $training->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Training updated successfully',
                'training' => $training
            ]);
        }

        return redirect()->back()->with('success', 'Training updated successfully');
    }

    public function destroy($id)
    {
        $training = PDSTraining::where('user_id', Auth::id())->findOrFail($id);
        $training->delete();

        return redirect()->back()->with('success', 'Training deleted successfully');
    }
}