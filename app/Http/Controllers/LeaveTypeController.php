<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeaveTypeController extends Controller
{
    public function index(Request $request)
    {
        $leaveTypes = LeaveType::query()
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%' . $request->q . '%';
                $q->where(fn ($w) => $w->where('code', 'like', $term)
                    ->orWhere('name', 'like', $term)
                    ->orWhere('legal_basis', 'like', $term));
            })
            ->orderByDesc('is_active')
            ->orderBy('id')
            ->get();

        return view('leave-types.index', compact('leaveTypes'));
    }

    public function store(Request $request)
    {
        LeaveType::create($this->validated($request));

        return redirect()->route('leave-types.index')->with('success', 'Leave type added.');
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $leaveType->update($this->validated($request, $leaveType));

        return redirect()->route('leave-types.index')->with('success', 'Leave type updated.');
    }

    public function toggle(LeaveType $leaveType)
    {
        $leaveType->update(['is_active' => ! $leaveType->is_active]);

        return back()->with('success', $leaveType->name . ' is now ' . ($leaveType->is_active ? 'active' : 'inactive') . '.');
    }

    public function destroy(LeaveType $leaveType)
    {
        if ($leaveType->isInUse()) {
            return back()->with('error', 'This leave type is already used by existing records. Deactivate it instead.');
        }

        $leaveType->delete();

        return back()->with('success', 'Leave type deleted.');
    }

    private function validated(Request $request, ?LeaveType $leaveType = null): array
    {
        $data = $request->validate([
            'code'        => ['required', 'string', 'max:20', Rule::unique('leave_types', 'code')->ignore($leaveType?->id)],
            'name'        => ['required', 'string', 'max:255'],
            'legal_basis' => ['nullable', 'string', 'max:255'],
        ]);

        $data['code']            = strtoupper($data['code']);
        $data['is_credit_based'] = $request->boolean('is_credit_based');
        $data['is_active']       = $request->boolean('is_active');

        return $data;
    }
}