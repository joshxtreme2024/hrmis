<?php

namespace App\Http\Controllers;

use App\Models\PDSBackgroundInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BackgroundInfoController extends Controller
{
    /**
     * Display the background information form.
     */
    public function index()
    {
        $user = Auth::user();
        $backgroundInfo = PDSBackgroundInfo::where('user_id', $user->id)->first();
        
        return view('profile.background.index', compact('backgroundInfo'));
    }

    /**
     * Show the form for creating new background information.
     */
    public function create()
    {
        $user = Auth::user();
        $backgroundInfo = PDSBackgroundInfo::where('user_id', $user->id)->first();
        
        // If already exists, redirect to edit
        if ($backgroundInfo) {
            return redirect()->route('background.edit', $backgroundInfo->id)
                ->with('info', 'You already have background information. You can edit it here.');
        }
        
        return view('profile.background.create');
    }

    /**
     * Store a newly created background information.
     */
    public function store(Request $request)
    {
        $validator = $this->validateBackground($request);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $backgroundInfo = PDSBackgroundInfo::create([
            'user_id' => Auth::id(),
            'q34_a' => $request->q34_a,
            'q34_a_details' => $request->q34_a_details,
            'q34_b' => $request->q34_b,
            'q34_b_details' => $request->q34_b_details,
            'q35_a' => $request->q35_a,
            'q35_a_details' => $request->q35_a_details,
            'q35_b' => $request->q35_b,
            'q35_b_details' => $request->q35_b_details,
            'q36' => $request->q36,
            'q36_details' => $request->q36_details,
            'q37' => $request->q37,
            'q37_details' => $request->q37_details,
            'q38_a' => $request->q38_a,
            'q38_a_details' => $request->q38_a_details,
            'q38_b' => $request->q38_b,
            'q38_b_details' => $request->q38_b_details,
            'q39' => $request->q39,
            'q39_details' => $request->q39_details,
            'q40_a' => $request->q40_a,
            'q40_a_details' => $request->q40_a_details,
            'q40_b' => $request->q40_b,
            'q40_b_details' => $request->q40_b_details,
            'q40_c' => $request->q40_c,
        ]);

        return redirect()->back()->with('success', 'Background information saved successfully.');
    }

    /**
     * Display the specified background information.
     */
    public function show(PDSBackgroundInfo $backgroundInfo)
    {
        // Check if the user owns this record
        if ($backgroundInfo->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $summary = $backgroundInfo->getSummary();
        $yesAnswers = $backgroundInfo->getYesAnswers();
        
        return view('profile.background.show', compact('backgroundInfo', 'summary', 'yesAnswers'));
    }

    /**
     * Show the form for editing background information.
     */
    public function edit(PDSBackgroundInfo $backgroundInfo)
    {
        // Check if the user owns this record
        if ($backgroundInfo->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('profile.background.edit', compact('backgroundInfo'));
    }

    /**
     * Update the specified background information.
     */
    public function update(Request $request, PDSBackgroundInfo $backgroundInfo)
    {
        // Check if the user owns this record
        if ($backgroundInfo->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validator = $this->validateBackground($request);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $backgroundInfo->update([
            'q34_a' => $request->q34_a,
            'q34_a_details' => $request->q34_a_details,
            'q34_b' => $request->q34_b,
            'q34_b_details' => $request->q34_b_details,
            'q35_a' => $request->q35_a,
            'q35_a_details' => $request->q35_a_details,
            'q35_b' => $request->q35_b,
            'q35_b_details' => $request->q35_b_details,
            'q36' => $request->q36,
            'q36_details' => $request->q36_details,
            'q37' => $request->q37,
            'q37_details' => $request->q37_details,
            'q38_a' => $request->q38_a,
            'q38_a_details' => $request->q38_a_details,
            'q38_b' => $request->q38_b,
            'q38_b_details' => $request->q38_b_details,
            'q39' => $request->q39,
            'q39_details' => $request->q39_details,
            'q40_a' => $request->q40_a,
            'q40_a_details' => $request->q40_a_details,
            'q40_b' => $request->q40_b,
            'q40_b_details' => $request->q40_b_details,
            'q40_c' => $request->q40_c,
        ]);

        return redirect()->back()->with('success', 'Background information updated successfully.');
    }

    /**
     * Remove the specified background information.
     */
    public function destroy(PDSBackgroundInfo $backgroundInfo)
    {
        // Check if the user owns this record
        if ($backgroundInfo->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $backgroundInfo->delete();

        return redirect()->route('profile.index')
            ->with('success', 'Background information deleted successfully.');
    }

    /**
     * Validate the background information request.
     */
    private function validateBackground(Request $request)
    {
        return Validator::make($request->all(), [
            // Question 34
            'q34_a' => 'required|in:yes,no',
            'q34_a_details' => 'nullable|string|max:500',
            'q34_b' => 'required|in:yes,no',
            'q34_b_details' => 'nullable|string|max:500',
            
            // Question 35
            'q35_a' => 'required|in:yes,no',
            'q35_a_details' => 'nullable|string|max:1000',
            'q35_b' => 'required|in:yes,no',
            'q35_b_details' => 'nullable|string|max:1000',
            
            // Question 36
            'q36' => 'required|in:yes,no',
            'q36_details' => 'nullable|string|max:1000',
            
            // Question 37
            'q37' => 'required|in:yes,no',
            'q37_details' => 'nullable|string|max:1000',
            
            // Question 38
            'q38_a' => 'required|in:yes,no',
            'q38_a_details' => 'nullable|string|max:500',
            'q38_b' => 'required|in:yes,no',
            'q38_b_details' => 'nullable|string|max:500',
            
            // Question 39
            'q39' => 'required|in:yes,no',
            'q39_details' => 'nullable|string|max:500',
            
            // Question 40
            'q40_a' => 'required|in:yes,no',
            'q40_a_details' => 'nullable|string|max:500',
            'q40_b' => 'required|in:yes,no',
            'q40_b_details' => 'nullable|string|max:500',
            'q40_c' => 'required|in:yes,no',
        ], [
            '*.required' => 'Please answer this question.',
            '*.in' => 'Please select either Yes or No.',
            '*.max' => 'The details field must not exceed :max characters.',
        ]);
    }

    /**
     * Get the background information for the authenticated user.
     */
    public function myBackground()
    {
        $backgroundInfo = PDSBackgroundInfo::where('user_id', Auth::id())->first();
        
        if (!$backgroundInfo) {
            return redirect()->route('background.create')
                ->with('info', 'Please complete your background information.');
        }

        return redirect()->route('background.show', $backgroundInfo->id);
    }

    /**
     * Get the background information for a specific user (admin only).
     */
    public function userBackground($userId)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $backgroundInfo = PDSBackgroundInfo::where('user_id', $userId)->first();
        
        if (!$backgroundInfo) {
            return response()->json([
                'success' => false,
                'message' => 'No background information found for this user.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $backgroundInfo,
            'summary' => $backgroundInfo->getSummary(),
            'completion_percentage' => $backgroundInfo->getCompletionPercentage(),
        ]);
    }

    /**
     * Get completion statistics (admin only).
     */
    public function statistics()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $total = PDSBackgroundInfo::count();
        $complete = PDSBackgroundInfo::complete()->count();
        $incomplete = PDSBackgroundInfo::incomplete()->count();
        $completionRate = $total > 0 ? round(($complete / $total) * 100) : 0;

        return view('admin.background.statistics', compact(
            'total',
            'complete',
            'incomplete',
            'completionRate'
        ));
    }
}