<?php

namespace App\Http\Controllers;

use App\Models\PDSAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}