<?php

namespace App\Http\Controllers;

use App\Models\PDSGovernmentId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GovernmentIdController extends Controller
{
    public function getEditData($id)
    {
        try {
            $governmentId = PDSGovernmentId::where('user_id', Auth::id())->findOrFail($id);
            
            return response()->json([
                'id' => $governmentId->id,
                'umid_number' => $governmentId->umid_number,
                'pagibig_number' => $governmentId->pagibig_number,
                'philhealth_number' => $governmentId->philhealth_number,
                'philsys_number' => $governmentId->philsys_number,
                'tin_number' => $governmentId->tin_number,
                'sss_number' => $governmentId->sss_number,
                'gsis_number' => $governmentId->gsis_number,
                'dl_number' => $governmentId->dl_number,
                'passport_number' => $governmentId->passport_number,
                'prc_number' => $governmentId->prc_number,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Government ID record not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'umid_number' => 'nullable|string|max:50',
            'pagibig_number' => 'nullable|string|max:50',
            'philhealth_number' => 'nullable|string|max:50',
            'philsys_number' => 'nullable|string|max:50',
            'tin_number' => 'nullable|string|max:50',
            'sss_number' => 'nullable|string|max:50',
            'gsis_number' => 'nullable|string|max:50',
            'dl_number' => 'nullable|string|max:50',
            'passport_number' => 'nullable|string|max:50',
            'prc_number' => 'nullable|string|max:50',
        ]);

        $validated['user_id'] = Auth::id();
        $governmentId = PDSGovernmentId::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Government IDs added successfully',
                'government_id' => $governmentId
            ]);
        }

        return redirect()->back()->with('success', 'Government IDs added successfully');
    }

    public function update(Request $request, $id)
    {
        $governmentId = PDSGovernmentId::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'umid_number' => 'nullable|string|max:50',
            'pagibig_number' => 'nullable|string|max:50',
            'philhealth_number' => 'nullable|string|max:50',
            'philsys_number' => 'nullable|string|max:50',
            'tin_number' => 'nullable|string|max:50',
            'sss_number' => 'nullable|string|max:50',
            'gsis_number' => 'nullable|string|max:50',
            'dl_number' => 'nullable|string|max:50',
            'passport_number' => 'nullable|string|max:50',
            'prc_number' => 'nullable|string|max:50',
        ]);

        $governmentId->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Government IDs updated successfully',
                'government_id' => $governmentId
            ]);
        }

        return redirect()->back()->with('success', 'Government IDs updated successfully');
    }
}