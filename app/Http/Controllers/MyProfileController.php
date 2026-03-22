<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PersonalDataSheets;
use App\Models\vw_PersonalDataSheets;
use App\Models\User;

class MyProfileController extends Controller
{
    public function show($userId = null)
    {
        $userId = $userId ?? Auth::id();

        // Fetch employee and user records
        $employee = vw_PersonalDataSheets::where('user_id', $userId)->first();
        $userModel = User::find($userId);

        // Fields to check for profile completeness
        $fields = ['first_name', 'last_name', 'middle_name', 'email', 'phone', 'address', 'birth_date', 'position', 'department'];

        // Count filled fields
        $filledFields = collect($fields)->filter(fn($f) => !empty($employee->$f))->count();

        // Calculate completion percentage
        $totalFields = count($fields);
        $completionPercentage = round(($filledFields / $totalFields) * 100);
        $profileComplete = $completionPercentage >= 80;

        return view('profile.showMyProfile', [
            'employee' => $employee,
            'completionPercentage' => $completionPercentage,
            'profileComplete' => $profileComplete,
            'user' => $userModel,
        ]);
    }
}
