<?php

namespace App\Http\Controllers;

use App\Models\PDSDistinction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DistinctionController extends Controller
{
    public function getEditData($id)
    {
        try {
            $distinction = PDSDistinction::where('user_id', Auth::id())->findOrFail($id);
            
            return response()->json([
                'id' => $distinction->id,
                'distinctions' => $distinction->distinctions,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Distinction record not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'distinctions' => 'required|string|max:500',
        ]);

        $validated['user_id'] = Auth::id();
        $distinction = PDSDistinction::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Distinction added successfully',
                'distinction' => $distinction
            ]);
        }

        return redirect()->back()->with('success', 'Distinction added successfully');
    }

    public function update(Request $request, $id)
    {
        $distinction = PDSDistinction::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'distinctions' => 'required|string|max:500',
        ]);

        $distinction->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Distinction updated successfully',
                'distinction' => $distinction
            ]);
        }

        return redirect()->back()->with('success', 'Distinction updated successfully');
    }

    public function destroy($id)
    {
        $distinction = PDSDistinction::where('user_id', Auth::id())->findOrFail($id);
        $distinction->delete();

        return redirect()->back()->with('success', 'Distinction deleted successfully');
    }
}