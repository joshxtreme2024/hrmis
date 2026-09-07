<?php

namespace App\Http\Controllers;

use App\Models\PDSAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function getEditData($id)
    {
        try {
            $address = PDSAddress::where('user_id', Auth::id())->findOrFail($id);
            
            return response()->json([
                'id' => $address->id,
                'address_type' => $address->address_type,
                'hbl_number' => $address->hbl_number,
                'street' => $address->street,
                'subdi_village' => $address->subdi_village,
                'barangay' => $address->barangay,
                'city_municipality' => $address->city_municipality,
                'province' => $address->province,
                'zip_code' => $address->zip_code,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Address record not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'address_type' => 'required|string|in:residential,permanent',
            'hbl_number' => 'nullable|string|max:100',
            'street' => 'nullable|string|max:255',
            'subdi_village' => 'nullable|string|max:255',
            'barangay' => 'required|string|max:255',
            'city_municipality' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'zip_code' => 'nullable|string|max:20',
        ]);
        
        if (PDSAddress::where('user_id', Auth::id())->where('address_type', $validated['address_type'])->exists()) {
            return response()->json([
                'error' => 'You already have an address of this type.',
                'message' => 'You already have an address of this type.' // ✅ Add both keys
            ], 400);
        }
        $validated['user_id'] = Auth::id();
        $address = PDSAddress::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Address added successfully',
                'address' => $address
            ]);
        }

        return redirect()->back()->with('success', 'Address added successfully');
    }

    public function update(Request $request, $id)
    {
        $address = PDSAddress::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'address_type' => 'required|string|in:residential,permanent',
            'hbl_number' => 'nullable|string|max:100',
            'street' => 'nullable|string|max:255',
            'subdi_village' => 'nullable|string|max:255',
            'barangay' => 'required|string|max:255',
            'city_municipality' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'zip_code' => 'nullable|string|max:20',
        ]);

        $address->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully',
                'address' => $address
            ]);
        }

        return redirect()->back()->with('success', 'Address updated successfully');
    }

    public function destroy($id)
    {
        $address = PDSAddress::where('user_id', Auth::id())->findOrFail($id);
        $address->delete();

        return redirect()->back()->with('success', 'Address deleted successfully');
    }

    public function togglePermanent(Request $request, $id)
    {
        try {
            $user = auth()->user();
            
            // Find the address
            $address = PDSAddress::where('user_id', $user->id)
                ->where('id', $id)
                ->firstOrFail();
            
            // Only residential addresses can be marked as permanent
            if ($address->address_type !== 'residential') {
                return redirect()->back()->with('error', 'Only residential addresses can be marked as permanent.');
            }
            
            // Determine the new status
            $newStatus = $request->input('is_permanent', $address->is_permanent ? 0 : 1);
            
            // If marking as permanent, unset any existing permanent address
            if ($newStatus == 1) {
                // Check if there's already a permanent address
                $existingPermanent = PDSAddress::where('user_id', $user->id)
                    ->where('address_type', 'permanent')
                    ->first();
                
                // If the permanent address exists and it's not this address
                if ($existingPermanent && $existingPermanent->id != $address->id) {
                    // Remove the permanent status from the existing address
                    $existingPermanent->update(['is_permanent' => 0]);
                }
            }
            
            // Update the address
            $address->update([
                'is_permanent' => $newStatus
            ]);
            
            // If marked as permanent, also create a copy as permanent address type if it doesn't exist
            if ($newStatus == 1) {
                $permanentAddress = PDSAddress::where('user_id', $user->id)
                    ->where('address_type', 'permanent')
                    ->first();
                
                if (!$permanentAddress) {
                    // Create a copy as permanent address
                    PDSAddress::create([
                        'user_id' => $user->id,
                        'address_type' => 'permanent',
                        'hbl_number' => $address->hbl_number,
                        'street' => $address->street,
                        'subdi_village' => $address->subdi_village,
                        'barangay' => $address->barangay,
                        'city_municipality' => $address->city_municipality,
                        'province' => $address->province,
                        'zip_code' => $address->zip_code,
                        'is_permanent' => 1,
                    ]);
                }
            } else {
                // If unmarking as permanent, check if there's a permanent address copy
                $permanentCopy = PDSAddress::where('user_id', $user->id)
                    ->where('address_type', 'permanent')
                    ->where('hbl_number', $address->hbl_number)
                    ->where('street', $address->street)
                    ->where('barangay', $address->barangay)
                    ->where('city_municipality', $address->city_municipality)
                    ->where('province', $address->province)
                    ->first();
                
                // Delete the copy if it exists and is not used elsewhere
                if ($permanentCopy) {
                    $permanentCopy->delete();
                }
            }
            
            // Return response
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $newStatus == 1 
                        ? 'Address marked as permanent successfully.' 
                        : 'Permanent address removed successfully.',
                    'address' => $address->fresh()
                ]);
            }
            
            return redirect()->back()->with('success', 
                $newStatus == 1 
                    ? 'Address marked as permanent successfully.' 
                    : 'Permanent address removed successfully.'
            );
            
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update address: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to update address: ' . $e->getMessage());
        }
    }
}