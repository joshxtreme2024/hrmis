<?php

namespace App\Http\Controllers;

use App\Models\PDSOrganization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function getEditData($id)
    {
        try {
            $organization = PDSOrganization::where('user_id', Auth::id())->findOrFail($id);
            
            return response()->json([
                'id' => $organization->id,
                'organization' => $organization->organization,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Organization record not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization' => 'required|string|max:500',
        ]);

        $validated['user_id'] = Auth::id();
        $organization = PDSOrganization::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Organization added successfully',
                'organization' => $organization
            ]);
        }

        return redirect()->back()->with('success', 'Organization added successfully');
    }

    public function update(Request $request, $id)
    {
        $organization = PDSOrganization::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'organization' => 'required|string|max:500',
        ]);

        $organization->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Organization updated successfully',
                'organization' => $organization
            ]);
        }

        return redirect()->back()->with('success', 'Organization updated successfully');
    }

    public function destroy($id)
    {
        $organization = PDSOrganization::where('user_id', Auth::id())->findOrFail($id);
        $organization->delete();

        return redirect()->back()->with('success', 'Organization deleted successfully');
    }
}