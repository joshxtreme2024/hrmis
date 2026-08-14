<?php

namespace App\Http\Controllers;

use App\Models\PDSSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillController extends Controller
{
    public function getEditData($id)
    {
        try {
            $skill = PDSSkill::where('user_id', Auth::id())->findOrFail($id);
            
            return response()->json([
                'id' => $skill->id,
                'skill_hobby' => $skill->skill_hobby,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Skill record not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'skill_hobby' => 'required|string|max:500',
        ]);

        $validated['user_id'] = Auth::id();
        $skill = PDSSkill::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Skill added successfully',
                'skill' => $skill
            ]);
        }

        return redirect()->back()->with('success', 'Skill added successfully');
    }

    public function update(Request $request, $id)
    {
        $skill = PDSSkill::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'skill_hobby' => 'required|string|max:500',
        ]);

        $skill->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Skill updated successfully',
                'skill' => $skill
            ]);
        }

        return redirect()->back()->with('success', 'Skill updated successfully');
    }

    public function destroy($id)
    {
        $skill = PDSSkill::where('user_id', Auth::id())->findOrFail($id);
        $skill->delete();

        return redirect()->back()->with('success', 'Skill deleted successfully');
    }
}