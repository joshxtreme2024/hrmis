<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use App\Models\PDSChildren;

class ChildrenController extends Controller
{
    public function create(){
        return view('profile.children.create');
    }

    Public function store(Request $request)
    {
        $validated = $request->validate([
            'children' => ['required', 'array', 'min:1'],
            'children.*.name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\.\']+$/'],
            'children.*.date_of_birth' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'children.*.sex' => ['required', 'string', Rule::in(['male', 'female'])],
            'children.*.order' => ['nullable', 'integer', 'min:1'],
        ]);

        $userId = auth()->id();
        $children = [];

        foreach ($validated['children'] as $childData) {
            $childData['user_id'] = $userId;
            $children[] = PDSChildren::create($childData);
        }

        $count = count($children);

        return redirect()
            ->route('myprofile.show')
            ->with('success', "{$count} child" . ($count > 1 ? 'ren' : '') . " added successfully!");
    }

    public function edit($id)
    {
        $child = PDSChildren::where('user_id', auth()->id())->findOrFail($id);
        return view('children.edit', compact('child'));
    }

    public function destroy($id)
    {
        $child = PDSChildren::where('user_id', auth()->id())->findOrFail($id);
        $child->delete();

        return redirect()
            ->route('myprofile.show')
            ->with('success', 'Child deleted successfully!');
    }

    public function getEditData($id)
    {
        try {
            $child = PDSChildren::findOrFail($id);
            
            return response()->json([
                'id' => $child->id,
                'name' => $child->name,
                'date_of_birth' => $child->date_of_birth ? $child->date_of_birth->format('Y-m-d') : null,
                'sex' => $child->sex,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Child not found'], 404);
        }
    }

    public function update(Request $request, $id)
{
    $child = PDSChildren::findOrFail($id);
    
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'date_of_birth' => 'required|date',
        'sex' => 'required|in:Male,Female',
    ]);
    
    $child->update($validated);
    
    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Child record updated successfully',
            'child' => $child
        ]);
    }
    
    return redirect()->back()->with('success', 'Child record updated successfully');
}
}
