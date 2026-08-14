<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePersonalDataRequest;
use App\Http\Requests\FamilyBackgroundRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\PersonalDataSheets;
use App\Models\PDSPersonalData;
use App\Models\PDSEducation;
use App\Models\PDSFamilyBackground;
use App\Models\PDSChildren;
use App\Models\PDSWorkExperience;
use App\Models\PDSEligibility;
use App\Models\PDSTraining;
use App\Models\PDSVoluntaryWork;
use App\Models\PDSDistinction;
use App\Models\PDSSkill;
use App\Models\PDSOrganization;
use App\Models\PDSReference;
use App\Models\User;
use App\Models\Positions;
use App\Models\Departments;
use App\Models\PDSEmployment;
use App\Models\PDSGovernmentId;
use App\Models\PDSAddress;
use App\Models\PDSBackgroundInfo;

class MyProfileController extends Controller
{

    public function show($userId = null)
    {
        $userId = $userId ?? Auth::id();

        // Fetch employee and user records
        $positions = Positions::orderBy('title')->get();
        $departments = Departments::orderBy('name')->get();
        $personalData = PDSPersonalData::where('user_id', $userId)->first();
        $governmentIds = PDSGovernmentId::where('user_id', $userId)->first();
        $familyBackground = PDSFamilyBackground::where('user_id', $userId)->first();
        $children = PDSChildren::where('user_id', $userId)->get();
        $education = PDSEducation::where('user_id', $userId)->get();
        $workExperiences = PDSWorkExperience::where('user_id', $userId)->orderBy('order', 'ASC')->get();
        $employment = PDSEmployment::where('user_id', $userId)->first();
        $trainings = PDSTraining::where('user_id', $userId)->orderBy('order', 'ASC')->get();
        $eligibilities = PDSEligibility::where('user_id', $userId)->orderBy('order', 'ASC')->get();
        $references = PDSReference::where('user_id', $userId)->orderBy('order', 'ASC')->get();
        $voluntaryWorks = PDSVoluntaryWork::where('user_id', $userId)->orderBy('order', 'ASC')->get();
        $skills = PDSSkill::where('user_id', $userId)->get();
        $distinctions = PDSDistinction::where('user_id', $userId)->get();
        $organizations = PDSOrganization::where('user_id', $userId)->get();
        $addresses = PDSAddress::where('user_id', $userId)->get();
         $backgroundInfo = PDSBackgroundInfo::where('user_id', $userId)->first();
        $userModel = User::find($userId);

        // ============================================
        // PROFILE COMPLETENESS CHECK
        // ============================================
        
        $completion = $this->calculateProfileCompletion(
            $personalData,
            $governmentIds,
            $familyBackground,
            $children,
            $education,
            $workExperiences,
            $employment,
            $trainings,
            $eligibilities,
            $references,
            $voluntaryWorks,
            $skills,
            $distinctions,
            $organizations,
            $addresses
        );

        return view('profile.showMyProfile', [
            'personalData' => $personalData,
            'completionPercentage' => $completion['percentage'],
            'profileComplete' => $completion['complete'],
            'completionDetails' => $completion['details'],
            'completionSections' => $completion['sections'],
            'completionStatus' => $completion['status'],
            'user' => $userModel,
            'governmentIds' => $governmentIds,
            'familyBackground' => $familyBackground,
            'children' => $children,
            'education' => $education,
            'workExperiences' => $workExperiences,
            'employment' => $employment,
            'trainings' => $trainings,
            'eligibilities' => $eligibilities,
            'references' => $references,
            'voluntaryWorks' => $voluntaryWorks,
            'skills' => $skills,
            'distinctions' => $distinctions,
            'organizations' => $organizations,
            'positions' => $positions,
            'departments' => $departments,
            'addresses' => $addresses,
            'backgroundInfo' => $backgroundInfo,
        ]);
    }

    /**
     * Calculate profile completion based on all sections
     */
    private function calculateProfileCompletion(
        $personalData,
        $governmentIds,
        $familyBackground,
        $children,
        $education,
        $workExperiences,
        $employment,
        $trainings,
        $eligibilities,
        $references,
        $voluntaryWorks,
        $skills,
        $distinctions,
        $organizations,
        $addresses
    ): array {
        
        $sections = [];
        $totalWeight = 0;
        $earnedWeight = 0;

        // ============================================
        // 1. PERSONAL INFORMATION (Weight: 20%)
        // ============================================
        $personalFields = [
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'last_name' => 'Last Name',
            'birth_date' => 'Date of Birth',
            'place_of_birth' => 'Place of Birth',
            'sex' => 'Sex',
            'civil_status' => 'Civil Status',
            'nationality' => 'Nationality',
            'religion' => 'Religion',
            'height' => 'Height',
            'weight' => 'Weight',
            'blood_type' => 'Blood Type',
            'telephone_no' => 'Telephone No.',
            'mobile_no' => 'Mobile No.',
        ];
        
        $filledPersonal = 0;
        $personalErrors = [];
        
        foreach ($personalFields as $field => $label) {
            if ($personalData && !empty($personalData->$field)) {
                $filledPersonal++;
            } else {
                $personalErrors[] = $label;
            }
        }
        
        $personalScore = $personalData ? ($filledPersonal / count($personalFields)) : 0;
        $sections['personal'] = [
            'label' => 'Personal Information',
            'weight' => 20,
            'filled' => $filledPersonal,
            'total' => count($personalFields),
            'score' => $personalScore,
            'errors' => $personalErrors,
            'icon' => 'bi-person',
            'color' => 'blue'
        ];
        
        $totalWeight += 20;
        $earnedWeight += $personalScore * 20;

        // ============================================
        // 2. GOVERNMENT IDs (Weight: 15%)
        // ============================================
        $govFields = [
            'gsis_number' => 'GSIS No.',
            'umid_number' => 'UMID ID No.',
            'pagibig_number' => 'PAG-IBIG ID No.',
            'philhealth_number' => 'PhilHealth No.',
            'philsys_number' => 'PhilSys Number (PSN)',
            'tin_number' => 'TIN No.',
            'sss_number' => 'SSS No.',
            'dl_number' => "Driver's License No.",
            'passport_number' => "Passport No.",
        ];
        
        $filledGov = 0;
        $govErrors = [];
        
        if ($governmentIds) {
            foreach ($govFields as $field => $label) {
                if (!empty($governmentIds->$field)) {
                    $filledGov++;
                } else {
                    $govErrors[] = $label;
                }
            }
        } else {
            $govErrors = array_values($govFields);
        }
        
        $govScore = $governmentIds ? ($filledGov / count($govFields)) : 0;
        $sections['government'] = [
            'label' => 'Government IDs',
            'weight' => 15,
            'filled' => $filledGov,
            'total' => count($govFields),
            'score' => $govScore,
            'errors' => $govErrors,
            'icon' => 'bi-person-vcard',
            'color' => 'purple'
        ];
        
        $totalWeight += 15;
        $earnedWeight += $govScore * 15;

        // ============================================
        // 3. FAMILY BACKGROUND (Weight: 10%)
        // ============================================
        $familyFields = [
            'spouse_first_name' => 'Spouse First Name',
            'spouse_last_name' => 'Spouse Last Name',
            'father_first_name' => 'Father First Name',
            'father_last_name' => 'Father Last Name',
            'mother_first_name' => 'Mother First Name',
            'mother_last_name' => 'Mother Last Name',
        ];
        
        $filledFamily = 0;
        $familyErrors = [];
        
        if ($familyBackground) {
            foreach ($familyFields as $field => $label) {
                if (!empty($familyBackground->$field)) {
                    $filledFamily++;
                } else {
                    $familyErrors[] = $label;
                }
            }
        } else {
            $familyErrors = array_values($familyFields);
        }
        
        $familyScore = $familyBackground ? ($filledFamily / count($familyFields)) : 0;
        $sections['family'] = [
            'label' => 'Family Background',
            'weight' => 10,
            'filled' => $filledFamily,
            'total' => count($familyFields),
            'score' => $familyScore,
            'errors' => $familyErrors,
            'icon' => 'bi-people',
            'color' => 'green'
        ];
        
        $totalWeight += 10;
        $earnedWeight += $familyScore * 10;

        // ============================================
        // 4. CHILDREN (Weight: 5%)
        // ============================================
        $hasChildren = $children && $children->count() > 0;
        $childrenScore = $hasChildren ? 1 : 0;
        
        $sections['children'] = [
            'label' => 'Children Records',
            'weight' => 5,
            'filled' => $hasChildren ? $children->count() : 0,
            'total' => '≥ 1',
            'score' => $childrenScore,
            'errors' => $hasChildren ? [] : ['No children records added'],
            'icon' => 'bi-person-fill',
            'color' => 'pink'
        ];
        
        // $totalWeight += 5;
        // $earnedWeight += $childrenScore * 5;

        // ============================================
        // 5. EDUCATION (Weight: 10%)
        // ============================================
        $hasEducation = $education && $education->count() > 0;
        $educationScore = $hasEducation ? 1 : 0;
        
        // Check if all education levels are covered
        $requiredLevels = ['elementary', 'high_school', 'college'];
        $filledLevels = [];
        if ($hasEducation) {
            foreach ($education as $edu) {
                $filledLevels[] = $edu->level;
            }
        }
        
        $missingLevels = array_diff($requiredLevels, $filledLevels);
        $educationErrors = [];
        if (!$hasEducation) {
            $educationErrors[] = 'No education records added';
        }
        foreach ($missingLevels as $level) {
            $educationErrors[] = ucfirst(str_replace('_', ' ', $level)) . ' not added';
        }
        
        $sections['education'] = [
            'label' => 'Educational Background',
            'weight' => 10,
            'filled' => $hasEducation ? $education->count() : 0,
            'total' => count($requiredLevels),
            'score' => $hasEducation ? (1 - (count($missingLevels) / count($requiredLevels))) : 0,
            'errors' => $educationErrors,
            'icon' => 'bi-mortarboard',
            'color' => 'amber'
        ];
        
        $totalWeight += 10;
        $earnedWeight += ($hasEducation ? (1 - (count($missingLevels) / count($requiredLevels))) : 0) * 10;

        // ============================================
        // 6. WORK EXPERIENCE (Weight: 10%)
        // ============================================
        $hasWork = $workExperiences && $workExperiences->count() > 0;
        $workScore = $hasWork ? 1 : 0;
        
        $sections['work'] = [
            'label' => 'Work Experience',
            'weight' => 10,
            'filled' => $hasWork ? $workExperiences->count() : 0,
            'total' => '≥ 1',
            'score' => $workScore,
            'errors' => $hasWork ? [] : ['No work experience added'],
            'icon' => 'bi-briefcase',
            'color' => 'cyan'
        ];
        
        $totalWeight += 10;
        $earnedWeight += $workScore * 10;

        // ============================================
        // 7. EMPLOYMENT (Weight: 5%)
        // ============================================
        $hasEmployment = $employment && !empty($employment->position_id);
        $employmentScore = $hasEmployment ? 1 : 0;
        
        $employmentErrors = [];
        if (!$hasEmployment) {
            $employmentErrors[] = 'Employment details not added';
        } else {
            if (empty($employment->position_id)) {
                $employmentErrors[] = 'Position not set';
            }
            if (empty($employment->department_id)) {
                $employmentErrors[] = 'Department not set';
            }
            if (empty($employment->hired_at)) {
                $employmentErrors[] = 'Hire date not set';
            }
        }
        
        $sections['employment'] = [
            'label' => 'Employment Details',
            'weight' => 5,
            'filled' => $hasEmployment ? 1 : 0,
            'total' => 1,
            'score' => $employmentScore,
            'errors' => $employmentErrors,
            'icon' => 'bi-building',
            'color' => 'indigo'
        ];
        
        $totalWeight += 5;
        $earnedWeight += $employmentScore * 5;

        // ============================================
        // 8. TRAININGS (Weight: 5%)
        // ============================================
        $hasTrainings = $trainings && $trainings->count() > 0;
        $trainingsScore = $hasTrainings ? 1 : 0;
        
        $sections['trainings'] = [
            'label' => 'Trainings',
            'weight' => 5,
            'filled' => $hasTrainings ? $trainings->count() : 0,
            'total' => '≥ 1',
            'score' => $trainingsScore,
            'errors' => $hasTrainings ? [] : ['No trainings added'],
            'icon' => 'fas fa-dumbbell',
            'color' => 'teal'
        ];
        
        $totalWeight += 5;
        $earnedWeight += $trainingsScore * 5;

        // ============================================
        // 9. ELIGIBILITIES (Weight: 5%)
        // ============================================
        $hasEligibilities = $eligibilities && $eligibilities->count() > 0;
        $eligibilitiesScore = $hasEligibilities ? 1 : 0;
        
        $sections['eligibilities'] = [
            'label' => 'Eligibilities',
            'weight' => 5,
            'filled' => $hasEligibilities ? $eligibilities->count() : 0,
            'total' => '≥ 1',
            'score' => $eligibilitiesScore,
            'errors' => $hasEligibilities ? [] : ['No eligibilities added'],
            'icon' => 'bi-award',
            'color' => 'yellow'
        ];
        
        // $totalWeight += 5;
        // $earnedWeight += $eligibilitiesScore * 5;

        // ============================================
        // 10. VOLUNTARY WORKS (Weight: 3%)
        // ============================================
        $hasVoluntary = $voluntaryWorks && $voluntaryWorks->count() > 0;
        $voluntaryScore = $hasVoluntary ? 1 : 0;
        
        $sections['voluntary'] = [
            'label' => 'Voluntary Works',
            'weight' => 3,
            'filled' => $hasVoluntary ? $voluntaryWorks->count() : 0,
            'total' => '≥ 1',
            'score' => $voluntaryScore,
            'errors' => $hasVoluntary ? [] : ['No voluntary works added'],
            'icon' => 'bi-heart',
            'color' => 'violet'
        ];
        
        // $totalWeight += 3;
        // $earnedWeight += $voluntaryScore * 3;

        // ============================================
        // 11. REFERENCES (Weight: 5%)
        // ============================================
        $hasReferences = $references && $references->count() >= 2;
        $referencesScore = $hasReferences ? 1 : 0;
        
        $referenceErrors = [];
        if (!$hasReferences) {
            if (!$references || $references->count() == 0) {
                $referenceErrors[] = 'No references added (minimum 2 required)';
            } elseif ($references->count() == 1) {
                $referenceErrors[] = 'Only 1 reference added (minimum 2 required)';
            }
        }
        
        $sections['references'] = [
            'label' => 'References',
            'weight' => 5,
            'filled' => $references ? $references->count() : 0,
            'total' => 2,
            'score' => $hasReferences ? 1 : 0,
            'errors' => $referenceErrors,
            'icon' => 'bi-person-check',
            'color' => 'red'
        ];
        
        $totalWeight += 5;
        $earnedWeight += $referencesScore * 5;

        // ============================================
        // 12. SKILLS (Weight: 2%)
        // ============================================
        $hasSkills = $skills && $skills->count() > 0;
        $skillsScore = $hasSkills ? 1 : 0;
        
        $sections['skills'] = [
            'label' => 'Skills & Hobbies',
            'weight' => 2,
            'filled' => $hasSkills ? $skills->count() : 0,
            'total' => '≥ 1',
            'score' => $skillsScore,
            'errors' => $hasSkills ? [] : ['No skills added'],
            'icon' => 'bi-star',
            'color' => 'rose'
        ];
        
        $totalWeight += 2;
        $earnedWeight += $skillsScore * 2;

        // ============================================
        // 13. DISTINCTIONS (Weight: 2%)
        // ============================================
        $hasDistinctions = $distinctions && $distinctions->count() > 0;
        $distinctionsScore = $hasDistinctions ? 1 : 0;
        
        $sections['distinctions'] = [
            'label' => 'Academic Distinctions',
            'weight' => 2,
            'filled' => $hasDistinctions ? $distinctions->count() : 0,
            'total' => '≥ 1',
            'score' => $distinctionsScore,
            'errors' => $hasDistinctions ? [] : ['No distinctions added'],
            'icon' => 'fa-solid fa-trophy',
            'color' => 'teal'
        ];
        
        $totalWeight += 2;
        $earnedWeight += $distinctionsScore * 2;

        // ============================================
        // 14. ORGANIZATIONS (Weight: 3%)
        // ============================================
        $hasOrganizations = $organizations && $organizations->count() > 0;
        $organizationsScore = $hasOrganizations ? 1 : 0;
        
        $sections['organizations'] = [
            'label' => 'Organizations',
            'weight' => 3,
            'filled' => $hasOrganizations ? $organizations->count() : 0,
            'total' => '≥ 1',
            'score' => $organizationsScore,
            'errors' => $hasOrganizations ? [] : ['No organizations added'],
            'icon' => 'fa-solid fa-users',
            'color' => 'purple'
        ];
        
        $totalWeight += 3;
        $earnedWeight += $organizationsScore * 3;

        // ============================================
        // 15. ADDRESSES (Weight: 3%)
        // ============================================
        $hasAddresses = $addresses && $addresses->count() > 0;

        // Check for both residential and permanent addresses
        $hasResidential = false;
        $hasPermanent = false;

        if ($hasAddresses) {
            foreach ($addresses as $address) {
                if ($address->address_type === 'residential' || $address->address_type === 'present') {
                    $hasResidential = true;
                }
                if ($address->address_type === 'permanent') {
                    $hasPermanent = true;
                }
            }
        }

        // ✅ Calculate score proportionally (50% per address type)
        $filledCount = ($hasResidential ? 1 : 0) + ($hasPermanent ? 1 : 0);
        $totalRequired = 2;
        $addressScore = $filledCount / $totalRequired; // 0, 0.5, or 1

        $addressErrors = [];

        if (!$hasResidential) {
            $addressErrors[] = 'Residential address not added';
        }
        if (!$hasPermanent) {
            $addressErrors[] = 'Permanent address not added';
        }

        $sections['addresses'] = [
            'label' => 'Addresses',
            'weight' => 3,
            'filled' => $filledCount,
            'total' => $totalRequired,
            'score' => $addressScore, // Now returns 0, 0.5, or 1
            'errors' => $addressErrors,
            'icon' => 'bi-geo-alt',
            'color' => 'red'
        ];

        $totalWeight += 3;
        $earnedWeight += $addressScore * 3;

        // ============================================
        // CALCULATE FINAL PERCENTAGE
        // ============================================
        
        $percentage = $totalWeight > 0 ? round(($earnedWeight / $totalWeight) * 100) : 0;
        $complete = $percentage >= 80;

         // ✅ Determine status
            $status = 'incomplete';
            if ($percentage >= 90) {
                $status = 'excellent';
            } elseif ($percentage >= 80) {
                $status = 'complete';
            } elseif ($percentage >= 50) {
                $status = 'partial';
            }

        return [
            'percentage' => $percentage,
            'complete' => $complete,
            'status' => $status,
            'details' => [
                'total_weight' => $totalWeight,
                'earned_weight' => $earnedWeight,
                'filled_fields' => $this->countFilledFields($personalData),
                'total_required_fields' => $this->getTotalRequiredFields(),
            ],
            'sections' => $sections
        ];
    }

    /**
     * Count filled fields in personal data
     */
    private function countFilledFields($personalData): int
    {
        if (!$personalData) {
            return 0;
        }
        
        $fields = [
            'first_name', 'middle_name', 'last_name', 'ext_name',
            'date_of_birth', 'place_of_birth', 'sex', 'civil_status',
            'height_m', 'weight_kg', 'blood_type',
            'telephone_no', 'mobile_no'
        ];
        
        $filled = 0;
        foreach ($fields as $field) {
            if (!empty($personalData->$field)) {
                $filled++;
            }
        }
        
        return $filled;
    }

    /**
     * Get total required fields count
     */
    private function getTotalRequiredFields(): int
    {
        return 13; // Total fields in personal data
    }

        public function editPersonalData(){

            $personalData = PDSPersonalData::where('user_id', Auth::user()->id)->first();
            return view('profile.editPersonalData', compact('personalData'));
        }

        public function updatePersonalData(UpdatePersonalDataRequest $request)
        {
            $personalData = auth()->user()->personalData;

            // Get validated data
            $validated = $request->validatedData();

            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($personalData->photo_path) {
                    Storage::disk('public')->delete($personalData->photo_path);
                }
                
                $path = $request->file('photo')->store('profile-photos', 'public');
                $validated['photo_path'] = $path;
            }

            // Update personal data
            $personalData->update($validated);

            return redirect()
                ->route('myprofile.show')
                ->with('success', 'Personal information updated successfully!');
        }

        public function createFamilyDetails(){
            return view('profile.family.create');
        }

        public function storeFamilyDetails(FamilyBackgroundRequest $request)
        {
            $validated = $request->validatedData();
            $userId = auth()->id();

            // Check if family background already exists for this user
            $familyBackground = PDSFamilyBackground::where('user_id', $userId)->first();

            if ($familyBackground) {
                // Update existing record
                $familyBackground->update($validated);
                $message = 'Family background updated successfully!';
            } else {
                // Create new record
                $validated['user_id'] = $userId;
                $familyBackground = PDSFamilyBackground::create($validated);
                $message = 'Family background added successfully!';
            }

            return redirect()
                ->route('myprofile.show')
                ->with('success', $message);
        }

        public function editFamilyDetails()
        {
            $familyBackground = PDSFamilyBackground::where('user_id', auth()->id())->first();

            if (!$familyBackground) {
                return redirect()
                    ->route('family-background.create')
                    ->with('info', 'Please add your family background first.');
            }

            return view('profile.family.edit', compact('familyBackground'));
        }

        public function updateFamilyDetails(FamilyBackgroundRequest $request){
            $familyBackground = PDSFamilyBackground::where('user_id', auth()->id())->first();
        
            // Ensure user owns this record
            if ($familyBackground->user_id !== auth()->id()) {
                abort(403, 'Unauthorized action.');
            }
            
            $validated = $request->validatedData();
            $familyBackground->update($validated);
            
            return redirect()
                ->route('myprofile.show')
                ->with('success', 'Family background updated successfully!');
        }
    }
