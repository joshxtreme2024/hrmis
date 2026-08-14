<?php

namespace App\Http\Controllers;

use App\Models\PDSReference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReferenceController extends Controller
{
    public function getEditData($id)
    {
        try {
            $reference = PDSReference::where('user_id', Auth::id())->findOrFail($id);
            
            return response()->json([
                'id' => $reference->id,
                'order' => $reference->order,
                'name' => $reference->name,
                'occupation' => $reference->occupation,
                'contact_number' => $reference->contact_number,
                'address' => $reference->address,
                'email' => $reference->email,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Reference record not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order' => 'nullable|integer|min:1',
            'name' => 'required|string|max:255',
            'occupation' => 'required|string|max:255',
            'contact_number' => 'required|string|max:50',
            'address' => 'nullable|string|max:500',
            'email' => 'nullable|email|max:255',
        ]);

        $validated['user_id'] = Auth::id();
        if (!isset($validated['order'])) {
            $validated['order'] = 0;
        }
        $reference = PDSReference::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Reference added successfully',
                'reference' => $reference
            ]);
        }

        return redirect()->back()->with('success', 'Reference added successfully');
    }

    public function update(Request $request, $id)
    {
        $reference = PDSReference::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'order' => 'nullable|integer|min:1',
            'name' => 'required|string|max:255',
            'occupation' => 'required|string|max:255',
            'contact_number' => 'required|string|max:50',
            'address' => 'nullable|string|max:500',
            'email' => 'nullable|email|max:255',
        ]);
        if (!isset($validated['order'])) {
            $validated['order'] = 0;
        }
        $reference->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Reference updated successfully',
                'reference' => $reference
            ]);
        }

        return redirect()->back()->with('success', 'Reference updated successfully');
    }

    public function destroy($id)
    {
        $reference = PDSReference::where('user_id', Auth::id())->findOrFail($id);
        $reference->delete();

        return redirect()->back()->with('success', 'Reference deleted successfully');
    }
}