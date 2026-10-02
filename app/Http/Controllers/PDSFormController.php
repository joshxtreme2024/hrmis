<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PDSFormController extends Controller
{
    public function index($userId){
        $user = User::with('personalData')->findOrFail($userId);
        
        return view('pds.index', compact('user'));
    }
    
    public function show($userId, Request $request)
    {
        $page = (int) $request->get('page', 1);

        // Load user with all PDS related data
        $user = User::with([
            'personalData',
            'address',
            'residentialAddress',
            'permanentAddress',
            'familyBackground',
            'children',
            'education',
            'eligibilities',
            'workExperiences',
            'voluntaryWorks',
            'trainings',
            'organizations',
            'skills',
            'distinctions',
            'governmentID',
            'employment'
        ])->findOrFail($userId);

        if ($page === 1) {
            return view('pds.form1', compact('user'));
        } elseif ($page === 2) {
            return view('pds.form2', compact('user'));
        } elseif ($page === 3) {
            return view('pds.form3', compact('user'));
        } else {
            return view('pds.form4', compact('user'));
        }
    }
    
    public function print($userId)
    {
        $user = User::with([
            'personalData',
            'address',
            'residentialAddress',
            'permanentAddress',
            'familyBackground',
            'children',
            'education',
            'eligibilities',
            'workExperiences',
            'voluntaryWorks',
            'trainings',
            'organizations',
            'skills',
            'distinctions',
            'governmentID',
            'employment'
        ])->findOrFail($userId);
        
        return view('pds.form', compact('user'));
    }
    
    public function generatePDF($userId)
    {
        $user = User::with([
            'personalData',
            'address',
            'residentialAddress',
            'permanentAddress',
            'familyBackground',
            'children',
            'education',
            'eligibilities',
            'workExperiences',
            'voluntaryWorks',
            'trainings',
            'organizations',
            'skills',
            'distinctions',
            'governmentID',
            'employment'
        ])->findOrFail($userId);
        
        $pdf = Pdf::loadView('pds.form', compact('user'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('PDS_' . $user->personalData?->surname . '_' . $user->personalData?->first_name . '.pdf');
    }
}