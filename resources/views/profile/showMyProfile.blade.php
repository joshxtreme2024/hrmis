@extends('layouts.app')

@section('title', 'Personal Data Sheet')
@section('header', 'Personal Data Sheet')
@section('subheader', 'Keep your records accurate and up to date')

@php
    use Illuminate\Support\Arr;
    use Illuminate\Support\HtmlString;
    use Illuminate\Support\Facades\Blade;
    use Carbon\Carbon;

    /* ---------- small helpers ---------- */
    $date  = fn ($d, $f = 'M d, Y') => $d ? Carbon::parse($d)->format($f) : null;
    $sub   = fn ($t) => $t ? '<span class="block text-xs text-gray-500 dark:text-gray-400">' . e($t) . '</span>' : '';
    $chip  = fn ($t, $c = 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200') =>
                '<span class="inline-block rounded-full px-2 py-0.5 text-xs font-medium ' . $c . '">' . e($t) . '</span>';
    $period = fn ($from, $to, $fmt = 'M d, Y') => e($date($from, $fmt) ?? '—') . ' – ' . e($to ? $date($to, $fmt) : 'Present');
    $f     = fn ($name, $label, $type = 'text', $o = []) => ['name' => $name, 'label' => $label, 'type' => $type] + $o;
    $opts  = fn (array $a) => collect($a)->map(fn ($l, $v) => [(string) $v, $l])->values()->all();
    $dl    = function (array $rows) {
        $h = '<dl class="grid gap-x-6 gap-y-4 sm:grid-cols-2 lg:grid-cols-3">';
        foreach ($rows as $k => $v) {
            $h .= '<div><dt class="text-xs text-gray-500 dark:text-gray-400">' . e($k) . '</dt><dd class="mt-0.5 text-sm font-medium text-gray-900 dark:text-white">' . e(filled($v) ? $v : '—') . '</dd></div>';
        }
        return new HtmlString($h . '</dl>');
    };
    $fullName = fn ($o, $p) => trim(implode(' ', array_filter([$o->{$p.'_first_name'} ?? null, $o->{$p.'_middle_name'} ?? null, $o->{$p.'_last_name'} ?? null, $o->{$p.'_name_extension'} ?? null])));

    /* ---------- header data ---------- */
    $name     = $personalData ? $personalData->completeName() : 'Not set';
    $initials = $personalData ? strtoupper(substr($personalData->first_name ?? '?', 0, 1) . substr($personalData->last_name ?? '?', 0, 1)) : '?';
    $pct      = (int) ($completionPercentage ?? 0);
    $status   = $completionStatus ?? 'incomplete';
    $statusStyles = [
        'excellent'  => 'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:ring-emerald-800',
        'complete'   => 'bg-blue-50 text-blue-700 ring-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:ring-blue-800',
        'partial'    => 'bg-amber-50 text-amber-700 ring-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:ring-amber-800',
        'incomplete' => 'bg-red-50 text-red-700 ring-red-200 dark:bg-red-900/30 dark:text-red-300 dark:ring-red-800',
    ];
    $barColor = $pct >= 80 ? 'bg-emerald-500' : ($pct >= 50 ? 'bg-amber-500' : 'bg-red-500');
    $needsAttention = collect($completionSections ?? [])->filter(fn ($s) => ($s['score'] ?? 0) < 0.8);

    /* ---------- list-type sections: one config drives table + modal ---------- */
    $crud = [
        'children' => [
            'title' => 'Children', 'noun' => 'child record', 'help' => 'List all your children as required by the PDS.',
            'items' => $children, 'base' => '/myprofile/children', 'addUrl' => route('myprofile.createChild'),
            'cols' => [
                'Name' => fn ($r) => '<span class="font-medium">' . e($r->name) . '</span>',
                'Date of birth' => fn ($r) => e($date($r->date_of_birth, 'F d, Y') ?? '—'),
                'Sex' => fn ($r) => e($r->sex ?: '—'),
            ],
            'fields' => [
                $f('name', 'Full name', 'text', ['required' => 1, 'full' => 1]),
                $f('date_of_birth', 'Date of birth', 'date', ['required' => 1]),
                $f('sex', 'Sex', 'select', ['required' => 1, 'options' => $opts(['Male' => 'Male', 'Female' => 'Female'])]),
            ],
        ],
        'education' => [
            'title' => 'Educational background', 'noun' => 'education record', 'help' => 'Elementary up to post-graduate, including vocational.',
            'items' => $education, 'base' => '/myprofile/education', 'store' => route('myprofile.education.store'),
            'cols' => [
                'Level' => fn ($r) => Blade::render('<x-education-badge :level="$l" />', ['l' => $r->level]),
                'School' => fn ($r) => '<span class="font-medium">' . e($r->school_name) . '</span>',
                'Course / honors' => fn ($r) => e($r->degree_course ?? '—') . $sub($r->highest_level_earned) . ($r->scholarship_honors ? '<span class="mt-1 block">' . $chip($r->scholarship_honors, 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300') . '</span>' : ''),
                'Years' => fn ($r) => e($r->getYearRangeAttribute()),
            ],
            'fields' => [
                $f('level', 'Education level', 'select', ['required' => 1, 'options' => $opts(['elementary' => 'Elementary', 'high_school' => 'High school', 'senior_high_school' => 'Senior high school', 'college' => 'College', 'post_graduate' => 'Post-graduate', 'vocational' => 'Vocational'])]),
                $f('school_name', 'School name', 'text', ['required' => 1]),
                $f('degree_course', 'Course / degree', 'text', ['full' => 1]),
                $f('period_from', 'Year from', 'number', ['required' => 1, 'ph' => 'YYYY', 'attrs' => 'min=1950 max=' . date('Y')]),
                $f('period_to', 'Year to', 'number', ['ph' => 'YYYY', 'attrs' => 'min=1950 max=' . date('Y')]),
                $f('year_graduated', 'Year graduated', 'number', ['ph' => 'YYYY', 'attrs' => 'min=1950 max=' . date('Y')]),
                $f('highest_level_earned', 'Highest level / units earned', 'text', ['ph' => 'e.g. Graduate, 30 units']),
                $f('scholarship_honors', 'Honors received (optional)', 'text', ['ph' => 'e.g. Cum Laude', 'full' => 1]),
                $f('order', 'Display order', 'number', ['attrs' => 'min=1', 'hint' => 'Controls the order in the PDS form.']),
            ],
        ],
        'eligibilities' => [
            'title' => 'Civil service eligibility', 'noun' => 'eligibility', 'help' => 'Career service, board and bar exams, and similar.',
            'items' => $eligibilities, 'base' => '/myprofile/eligibility', 'store' => route('myprofile.eligibility.store'),
            'cols' => [
                'Eligibility' => fn ($r) => '<span class="font-medium">' . e($r->career_service) . '</span>',
                'Rating' => fn ($r) => e($r->rating ?: '—'),
                'Examination' => fn ($r) => e($date($r->examination_date) ?? '—') . $sub($r->examination_place),
                'License' => fn ($r) => e($r->license_number ?: '—') . $sub($r->license_date_validity ? 'Valid until ' . $date($r->license_date_validity) : null),
            ],
            'fields' => [
                $f('career_service', 'Eligibility', 'text', ['required' => 1, 'ph' => 'e.g. Civil Service Professional', 'full' => 1]),
                $f('rating', 'Rating', 'text', ['ph' => 'e.g. 85.50']),
                $f('license_number', 'License number', 'text'),
                $f('examination_date', 'Examination date', 'date'),
                $f('examination_place', 'Examination place', 'text'),
                $f('license_date_validity', 'License valid until', 'date'),
                $f('order', 'Display order', 'number', ['attrs' => 'min=1']),
            ],
        ],
        'work' => [
            'title' => 'Work experience', 'noun' => 'work experience', 'help' => 'Start with your most recent position.',
            'items' => $workExperiences, 'base' => '/myprofile/work', 'store' => route('myprofile.work.store'),
            'cols' => [
                'Position' => fn ($r) => '<span class="font-medium">' . e($r->position_title) . '</span>',
                'Agency / company' => fn ($r) => e($r->department_agency_office),
                'Period' => fn ($r) => $period($r->inclusive_from, $r->inclusive_to, 'M Y'),
                'Salary' => fn ($r) => $r->monthly_salary ? '₱' . number_format($r->monthly_salary, 2) . $sub($r->salary_grade ? 'SG ' . $r->salary_grade : null) : '—',
                'Type' => fn ($r) => $chip($r->is_gov == 'Yes' ? 'Government' : 'Private', $r->is_gov == 'Yes' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200') . ($r->status_of_appointment ? ' ' . $chip(ucfirst(str_replace('_', ' ', $r->status_of_appointment))) : ''),
            ],
            'fields' => [
                $f('position_title', 'Position title', 'text', ['required' => 1, 'ph' => 'e.g. Administrative Aide']),
                $f('department_agency_office', 'Department / agency / office', 'text', ['required' => 1]),
                $f('inclusive_from', 'Date from', 'date', ['required' => 1]),
                $f('inclusive_to', 'Date to', 'date', ['hint' => 'Leave blank if this is your current job.']),
                $f('monthly_salary', 'Monthly salary (₱)', 'number', ['attrs' => 'min=0 step=0.01']),
                $f('salary_grade', 'Salary grade', 'text', ['ph' => 'e.g. SG-12']),
                $f('status_of_appointment', 'Status of appointment', 'select', ['options' => $opts(['permanent' => 'Permanent', 'coterminus' => 'Coterminous', 'temporary' => 'Temporary', 'contractual' => 'Contractual', 'job_order' => 'Job order', 'casual' => 'Casual', 'probationary' => 'Probationary'])]),
                $f('is_gov', 'Government service?', 'select', ['blank' => false, 'options' => $opts(['Yes' => 'Yes', 'No' => 'No'])]),
                $f('order', 'Display order', 'number', ['attrs' => 'min=1']),
            ],
        ],
        'volwork' => [
            'title' => 'Voluntary work', 'noun' => 'voluntary work', 'help' => 'Civic, non-government, people or voluntary organizations.',
            'items' => $voluntaryWorks, 'base' => '/myprofile/voluntary-work', 'store' => route('myprofile.voluntary-work.store'),
            'cols' => [
                'Organization' => fn ($r) => '<span class="font-medium">' . e($r->organization_name) . '</span>' . $sub($r->organization_address),
                'Position' => fn ($r) => e($r->position_nature_of_work ?: '—'),
                'Period' => fn ($r) => $period($r->inclusive_from, $r->inclusive_to),
                'Hours' => fn ($r) => $r->number_of_hours ? e($r->number_of_hours) . ' hrs' : '—',
            ],
            'fields' => [
                $f('organization_name', 'Organization name', 'text', ['required' => 1, 'full' => 1]),
                $f('organization_address', 'Organization address', 'text', ['full' => 1]),
                $f('position_nature_of_work', 'Position / nature of work', 'text', ['required' => 1, 'full' => 1]),
                $f('inclusive_from', 'Date from', 'date', ['required' => 1]),
                $f('inclusive_to', 'Date to', 'date', ['hint' => 'Leave blank if ongoing.']),
                $f('number_of_hours', 'Number of hours', 'number', ['attrs' => 'min=0 step=0.5']),
                $f('order', 'Display order', 'number', ['attrs' => 'min=1']),
            ],
        ],
        'trainings' => [
            'title' => 'Learning and development', 'noun' => 'training', 'help' => 'Seminars, courses and workshops you completed.',
            'items' => $trainings, 'base' => '/myprofile/training', 'store' => route('myprofile.training.store'),
            'cols' => [
                'Program' => fn ($r) => '<span class="font-medium">' . e($r->title_of_program) . '</span>' . ($r->has_type ? '<span class="mt-1 block">' . $chip($r->formatted_type) . '</span>' : ''),
                'Conducted by' => fn ($r) => e($r->conducted_by ?: '—'),
                'Period' => fn ($r) => $period($r->inclusive_from, $r->inclusive_to ?: $r->inclusive_from),
                'Hours' => fn ($r) => $r->number_of_hours ? e($r->number_of_hours) . ' hrs' : '—',
            ],
            'fields' => [
                $f('title_of_program', 'Title of program', 'text', ['required' => 1, 'full' => 1]),
                $f('conducted_by', 'Conducted / sponsored by', 'text', ['required' => 1]),
                $f('type_of_ld', 'Type of training', 'select', ['options' => $opts(['managerial' => 'Managerial', 'supervisory' => 'Supervisory', 'technical' => 'Technical', 'behavioral' => 'Behavioral', 'leadership' => 'Leadership', 'functional' => 'Functional', 'other' => 'Other'])]),
                $f('inclusive_from', 'Date from', 'date', ['required' => 1]),
                $f('inclusive_to', 'Date to', 'date', ['hint' => 'Leave blank if it lasted one day.']),
                $f('number_of_hours', 'Number of hours', 'number', ['required' => 1, 'attrs' => 'min=0.5 step=0.5']),
                $f('order', 'Display order', 'number', ['attrs' => 'min=1']),
            ],
        ],
        'skills' => [
            'title' => 'Special skills and hobbies', 'noun' => 'skill or hobby', 'help' => 'Add one entry per line or separate with commas.',
            'items' => $skills, 'base' => '/myprofile/skill', 'store' => route('myprofile.skill.store'),
            'cols' => ['Skill / hobby' => fn ($r) => e($r->skill_hobby)],
            'fields' => [$f('skill_hobby', 'Skill or hobby', 'textarea', ['required' => 1, 'full' => 1, 'ph' => 'e.g. Web design, Photography', 'hint' => 'You can list several, separated by commas.'])],
        ],
        'distinctions' => [
            'title' => 'Non-academic distinctions and recognition', 'noun' => 'distinction', 'help' => 'Awards and recognition you have received.',
            'items' => $distinctions, 'base' => '/myprofile/distinction', 'store' => route('myprofile.distinction.store'),
            'cols' => ['Distinction / award' => fn ($r) => e($r->distinctions)],
            'fields' => [$f('distinctions', 'Distinction or award', 'textarea', ['required' => 1, 'full' => 1, 'ph' => 'e.g. Employee of the Year 2023', 'hint' => 'Include the year and the granting body if you can.'])],
        ],
        'organizations' => [
            'title' => 'Membership in associations', 'noun' => 'organization', 'help' => 'Associations and organizations you belong to.',
            'items' => $organizations, 'base' => '/myprofile/organization', 'store' => route('myprofile.organization.store'),
            'cols' => ['Organization' => fn ($r) => e($r->organization)],
            'fields' => [$f('organization', 'Organization name', 'textarea', ['required' => 1, 'full' => 1, 'ph' => 'e.g. Rotary Club – Member', 'hint' => 'Include your role if relevant.'])],
        ],
        'references' => [
            'title' => 'References', 'noun' => 'reference', 'help' => 'Three people who are not related to you and know you well.',
            'items' => $references, 'base' => '/myprofile/reference', 'store' => route('myprofile.reference.store'),
            'cols' => [
                'Name' => fn ($r) => '<span class="font-medium">' . e($r->name) . '</span>',
                'Occupation' => fn ($r) => e($r->occupation ?: '—'),
                'Contact' => fn ($r) => e($r->contact_number ?: '—') . $sub($r->email),
            ],
            'fields' => [
                $f('name', 'Full name', 'text', ['required' => 1]),
                $f('occupation', 'Occupation', 'text', ['required' => 1]),
                $f('contact_number', 'Contact number', 'text', ['required' => 1, 'ph' => 'e.g. 09171234567']),
                $f('email', 'Email address', 'email'),
                $f('address', 'Address', 'textarea', ['full' => 1]),
                $f('order', 'Display order', 'number', ['attrs' => 'min=1']),
            ],
        ],
    ];

    /* ---------- single-record sections ---------- */
    $emp = $employment ?? null;
    $gov = $governmentIds ?? null;
    $singles = [
        'employment' => [
            'title' => 'Employment information', 'noun' => 'employment record', 'model' => $emp,
            'help' => 'Your current position in the LGU. HR normally maintains this.',
            'base' => '/myprofile/employment', 'store' => route('myprofile.employment.store'),
            'rows' => $emp ? [
                'Employee ID' => $emp->employee_id, 'Status' => $emp->status_label, 'Position' => $emp->position->title ?? null,
                'Department' => $emp->department->name ?? null, 'Employment type' => $emp->employment_type_label,
                'Monthly salary' => $emp->formatted_salary,
                'Salary grade / step' => trim(($emp->position->salary_grade ?? '') . ($emp->step_increment ? ' / Step ' . $emp->step_increment : ''), ' /'),
                'Date hired' => $emp->formatted_hired_date, 'Original appointment' => $emp->formatted_original_appointment,
                'Last promotion' => $emp->formatted_last_promotion, 'Length of service' => $emp->service_duration,
            ] : [],
            'fields' => [
                $f('employee_id', 'Employee ID', 'text', ['ph' => 'e.g. LGU-2024-001']),
                $f('status', 'Status', 'select', ['required' => 1, 'blank' => false, 'options' => $opts(['active' => 'Active', 'inactive' => 'Inactive', 'resigned' => 'Resigned', 'retired' => 'Retired', 'on_leave' => 'On leave', 'suspended' => 'Suspended', 'terminated' => 'Terminated'])]),
                $f('position_id', 'Position', 'select', ['required' => 1, 'options' => collect($positions ?? [])->map(fn ($p) => [(string) $p->id, $p->title])->values()->all()]),
                $f('department_id', 'Department', 'select', ['required' => 1, 'options' => collect($departments ?? [])->map(fn ($d) => [(string) $d->id, $d->name])->values()->all()]),
                $f('employment_type', 'Employment type', 'select', ['required' => 1, 'blank' => false, 'options' => $opts(['permanent' => 'Permanent', 'temporary' => 'Temporary', 'contractual' => 'Contractual', 'casual' => 'Casual', 'job_order' => 'Job order', 'consultant' => 'Consultant', 'co_term' => 'Co-terminous'])]),
                $f('step_increment', 'Step increment', 'select', ['options' => $opts(array_combine(range(1, 8), array_map(fn ($i) => "Step $i", range(1, 8))))]),
                $f('salary', 'Monthly salary (₱)', 'number', ['attrs' => 'min=0 step=0.01']),
                $f('hired_at', 'Date hired', 'date', ['required' => 1]),
                $f('date_of_original_appointment', 'Original appointment date', 'date'),
                $f('date_of_last_promotion', 'Last promotion date', 'date'),
            ],
        ],
        'government' => [
            'title' => 'Government IDs', 'noun' => 'government IDs', 'model' => $gov,
            'help' => 'Enter numbers exactly as printed on your IDs. Dashes and spaces are fine.',
            'base' => '/myprofile/government-id', 'store' => route('myprofile.government-id.store'),
            'rows' => $gov ? [
                'UMID' => $gov->umid_number, 'Pag-IBIG' => $gov->pagibig_number, 'PhilHealth' => $gov->philhealth_number,
                'PhilSys (PSN)' => $gov->philsys_number, 'TIN' => $gov->tin_number, 'SSS' => $gov->sss_number,
                'GSIS' => $gov->gsis_number, "Driver's license" => $gov->dl_number, 'Passport' => $gov->passport_number, 'PRC' => $gov->prc_number,
            ] : [],
            'fields' => [
                $f('umid_number', 'UMID', 'text', ['ph' => 'XXXX-XXXXXXX-X']), $f('pagibig_number', 'Pag-IBIG', 'text', ['ph' => 'XXXX-XXXX-XXXX']),
                $f('philhealth_number', 'PhilHealth', 'text', ['ph' => 'XX-XXXXXXXXX-X']), $f('philsys_number', 'PhilSys number (PSN)', 'text', ['ph' => 'XXXX-XXXXXXX-X']),
                $f('tin_number', 'TIN', 'text', ['ph' => 'XXX-XXX-XXX-XXX']), $f('sss_number', 'SSS', 'text', ['ph' => 'XX-XXXXXXX-X']),
                $f('gsis_number', 'GSIS (optional)', 'text'), $f('dl_number', "Driver's license (optional)", 'text'),
                $f('passport_number', 'Passport (optional)', 'text'), $f('prc_number', 'PRC ID (optional)', 'text'),
            ],
        ],
    ];

    /* ---------- addresses (card list + same modal) ---------- */
    $addressModal = [
        'noun' => 'address', 'base' => '/myprofile/address', 'store' => route('myprofile.address.store'),
        'fields' => [
            $f('address_type', 'Address type', 'select', ['required' => 1, 'lock' => 1, 'full' => 1, 'options' => $opts(['residential' => 'Residential', 'permanent' => 'Permanent'])]),
            $f('hbl_number', 'House / block / lot no.', 'text', ['ph' => 'e.g. Block 5, Lot 8']),
            $f('street', 'Street', 'text'),
            $f('subdi_village', 'Subdivision / village', 'text', ['full' => 1]),
            $f('barangay', 'Barangay', 'text', ['required' => 1]),
            $f('city_municipality', 'City / municipality', 'text', ['required' => 1]),
            $f('province', 'Province', 'text', ['required' => 1]),
            $f('zip_code', 'Zip code', 'text'),
        ],
    ];

    /* ---------- Background information questions ---------- */
    $bg = [
        ['Are you related by consanguinity or affinity to the appointing or recommending authority, the chief of bureau or office, or the person who has immediate supervision over you in the office, bureau or department where you will be appointed?', [
            ['q34_a', 'Within the third degree?', 'Specify the relationship', 'text'],
            ['q34_b', 'Within the fourth degree (for LGU career employees)?', 'Specify the relationship', 'text'],
        ]],
        [null, [
            ['q35_a', 'Have you ever been found guilty of any administrative offense?', 'Give details', 'area'],
            ['q35_b', 'Have you been criminally charged before any court?', 'Give details', 'area'],
        ]],
        [null, [['q36', 'Have you ever been convicted of any crime or violation of any law, decree, ordinance or regulation by any court or tribunal?', 'Give details', 'area']]],
        [null, [['q37', 'Have you ever been separated from the service by resignation, retirement, dropped from the rolls, dismissal, termination, end of term, finished contract or phased out (abolition), in the public or private sector?', 'Give details', 'area']]],
        [null, [
            ['q38_a', 'Have you been a candidate in a national or local election held within the last year (except Barangay election)?', 'Give details', 'text'],
            ['q38_b', 'Have you resigned from the government service during the three (3)-month period before the last election to promote or actively campaign for a national or local candidate?', 'Give details', 'text'],
        ]],
        [null, [['q39', 'Have you acquired the status of an immigrant or permanent resident of another country?', 'Specify the country', 'text']]],
        ['Pursuant to RA 8371 (Indigenous Peoples’ Rights Act), RA 7277 as amended (Magna Carta for Disabled Persons) and RA 11861 (Expanded Solo Parents Welfare Act), please answer the following:', [
            ['q40_a', 'Are you a member of any indigenous group?', 'Specify the group', 'text'],
            ['q40_b', 'Are you a person with disability?', 'Specify the type of disability', 'text'],
            ['q40_c', 'Are you a solo parent?', null, null],
        ]],
    ];

    /* ---------- tabs ---------- */
    $tabs = [
        'personal'      => ['Personal',         'bi bi-person',            null],
        'family'        => ['Family',           'bi bi-people',            null],
        'education'     => ['Education',        'bi bi-mortarboard',       $education->count()],
        'eligibilities' => ['Eligibility',      'bi bi-patch-check',       $eligibilities->count()],
        'work'          => ['Work experience',  'bi bi-briefcase',         $workExperiences->count()],
        'volwork'       => ['Voluntary work',   'bi bi-heart',             $voluntaryWorks->count()],
        'trainings'     => ['Training',         'bi bi-journal-check',     $trainings->count()],
        'skills'        => ['Skills & hobbies', 'bi bi-stars',             $skills->count()],
        'distinctions'  => ['Distinctions',     'bi bi-trophy',            $distinctions->count()],
        'organizations' => ['Organizations',    'bi bi-diagram-2',         $organizations->count()],
        'background'    => ['Background',       'bi bi-clipboard-check',   null],
        'references'    => ['References',       'bi bi-person-lines-fill', $references->count()],
        'employment'    => ['Employment',       'bi bi-building',          null],
        'government'    => ['Government IDs',   'bi bi-person-vcard',      null],
    ];

    $modalCfg = collect($crud)->merge($singles)->put('address', $addressModal)
        ->map(fn ($c) => Arr::only($c, ['noun', 'base', 'store', 'fields']))->all();

    $btnPrimary = 'inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700';
    $btnGhost   = 'inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700';
@endphp

@section('header-actions')
    <button type="button" onclick="history.back()" class="{{ $btnGhost }}"><i class="bi bi-arrow-left"></i> Back</button>
@endsection

@section('breadcrumbs')
    <li class="flex items-center"><i class="bi bi-chevron-right mx-2 text-xs text-gray-300 dark:text-gray-600"></i><span class="font-medium text-gray-800 dark:text-gray-200">PDS</span></li>
    <li class="flex items-center"><i class="bi bi-chevron-right mx-2 text-xs text-gray-300 dark:text-gray-600"></i><span class="font-medium text-gray-800 dark:text-gray-200">My PDS</span></li>
@endsection

@section('content')
<style>
    .pds-tabs { scrollbar-width: thin; }
    .pds-tab[aria-selected="true"] { color: #4338ca; border-color: #4338ca; background: rgb(238 242 255 / .6); }
    .dark .pds-tab[aria-selected="true"] { color: #a5b4fc; border-color: #a5b4fc; background: rgb(49 46 129 / .25); }
    :is(.pds-tab, a, button, input, select, textarea, summary):focus-visible { outline: 2px solid #6366f1; outline-offset: 2px; }
    #crudModal::backdrop { background: rgb(17 24 39 / .6); }
    @media (prefers-reduced-motion: reduce) { * { transition: none !important; } }
</style>

<div class="space-y-6">

    {{-- ===== Profile summary ===== --}}
    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800" aria-label="Profile summary">
        <div class="flex flex-col gap-6 p-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-5">
                <div class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-full bg-indigo-50 shadow ring-4 ring-white dark:bg-indigo-900/40 dark:ring-gray-800">
                    @if($personalData && $personalData->photo_path)
                        <img src="{{ asset('storage/'.$personalData->photo_path) }}" alt="Photo of {{ $name }}" class="h-full w-full object-cover">
                    @else
                        <span class="text-2xl font-semibold text-indigo-700 dark:text-indigo-300" aria-hidden="true">{{ $initials }}</span>
                    @endif
                </div>
                <div class="min-w-0">
                    <h1 class="truncate text-2xl font-semibold text-gray-900 dark:text-white">{{ $name }}</h1>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach([$personalData->sex ?? null, $personalData->civil_status ?? null, $personalData->nationality ?? null] as $c)
                            @if($c){!! $chip(ucfirst($c)) !!}@endif
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('mydocuments.index') }}" class="{{ $btnGhost }}"><i class="bi bi-folder2-open"></i> My 201 file</a>
                <a href="{{ route('pds.index', $user->id) }}" class="{{ $btnPrimary }}"><i class="bi bi-file-earmark-text"></i> Generate PDS form</a>
            </div>
        </div>

        <div class="border-t border-gray-200 bg-gray-50 px-6 py-5 dark:border-gray-700 dark:bg-gray-900/30">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ ($profileComplete ?? false) ? 'Your PDS is complete' : "Your PDS is {$pct}% complete" }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ ($profileComplete ?? false) ? 'You can generate your PDS form now.' : 'Finish the sections below before generating your PDS form.' }}</p>
                </div>
                <span class="rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset {{ $statusStyles[$status] ?? $statusStyles['incomplete'] }}">{{ ucfirst($status) }}</span>
            </div>
            <div class="mt-3 h-2 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700" role="progressbar" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100" aria-label="Profile completion">
                <div class="h-2 rounded-full {{ $barColor }} transition-all duration-500" style="width: {{ $pct }}%"></div>
            </div>
            @if($needsAttention->isNotEmpty())
                <details class="group mt-4" {{ $needsAttention->count() <= 3 ? 'open' : '' }}>
                    <summary class="flex cursor-pointer list-none items-center gap-2 text-sm font-medium text-amber-700 dark:text-amber-400">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $needsAttention->count() }} {{ \Illuminate\Support\Str::plural('section', $needsAttention->count()) }} need your attention
                        <i class="bi bi-chevron-down text-xs transition group-open:rotate-180"></i>
                    </summary>
                    <ul class="mt-3 grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($needsAttention as $section)
                            @php $score = (int) round(($section['score'] ?? 0) * 100); $missing = $section['errors'] ?? []; @endphp
                            <li class="rounded-lg border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-gray-800">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $section['label'] ?? 'Section' }}</span>
                                    <span class="tabular-nums text-gray-600 dark:text-gray-400">{{ $score }}%</span>
                                </div>
                                <div class="mt-2 h-1 w-full rounded-full bg-gray-200 dark:bg-gray-700"><div class="h-1 rounded-full bg-amber-500" style="width: {{ $score }}%"></div></div>
                                @if(count($missing))
                                    <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">Missing: {{ implode(', ', array_slice($missing, 0, 2)) }}@if(count($missing) > 2) and {{ count($missing) - 2 }} more @endif</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </details>
            @endif
        </div>
    </section>

    {{-- ===== Tabs ===== --}}
    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="pds-tabs flex overflow-x-auto" role="tablist" aria-label="PDS sections">
                @foreach($tabs as $key => [$label, $icon, $count])
                    <button type="button" role="tab" data-tab="{{ $key }}" id="tabbtn-{{ $key }}" aria-controls="tab-{{ $key }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                            class="pds-tab -mb-px inline-flex shrink-0 items-center gap-2 border-b-2 border-transparent px-4 py-3.5 text-sm font-medium text-gray-600 hover:border-gray-300 hover:text-gray-900 dark:text-gray-400 dark:hover:border-gray-600 dark:hover:text-white">
                        <i class="{{ $icon }}" aria-hidden="true"></i>{{ $label }}
                        @if(!is_null($count))<span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs tabular-nums text-gray-600 dark:bg-gray-700 dark:text-gray-300">{{ $count }}</span>@endif
                    </button>
                @endforeach
            </nav>
        </div>

        <div class="p-6">

            {{-- PERSONAL --}}
            <div class="tab-content hidden" id="tab-personal" role="tabpanel" aria-labelledby="tabbtn-personal">
                @if($personalData)
                    @php
                        $p = $personalData;
                        $groups = [
                            'Name' => ['First name' => $p->first_name, 'Middle name' => $p->middle_name, 'Last name' => $p->last_name, 'Name extension' => $p->ext_name],
                            'Birth and demographics' => ['Sex' => ucfirst((string) $p->sex), 'Birth date' => $date($p->birth_date, 'F d, Y'), 'Place of birth' => $p->place_of_birth, 'Civil status' => ucfirst((string) $p->civil_status), 'Nationality' => $p->nationality, 'Religion' => $p->religion],
                            'Physical details' => ['Height (cm)' => $p->height, 'Weight (kg)' => $p->weight, 'Blood type' => $p->blood_type],
                            'Contact' => ['Telephone' => $p->telephone_no, 'Mobile' => $p->mobile_no, 'Email' => $p->user->email ?? null],
                        ];
                    @endphp
                    <div class="mb-6 flex items-start justify-between gap-3">
                        <div><h2 class="text-base font-semibold text-gray-900 dark:text-white">Personal information</h2><p class="text-sm text-gray-500 dark:text-gray-400">Basic details that appear on your PDS.</p></div>
                        <a href="{{ route('myprofile.editPersonalData') }}" class="{{ $btnPrimary }}"><i class="bi bi-pencil-square"></i> Edit details</a>
                    </div>
                    <div class="space-y-6">
                        @foreach($groups as $title => $rows)
                            <div><h3 class="mb-3 border-b border-gray-200 pb-2 text-sm font-semibold text-gray-800 dark:border-gray-700 dark:text-gray-200">{{ $title }}</h3>{{ $dl($rows) }}</div>
                        @endforeach

                        <div>
                            <div class="mb-3 flex items-center justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200">Addresses</h3>
                                <button type="button" onclick="openCrud('address')" class="{{ $btnGhost }} !py-1.5"><i class="bi bi-plus-lg"></i> Add address</button>
                            </div>
                            @if($addresses && $addresses->count())
                                <div class="grid gap-4 md:grid-cols-2">
                                    @foreach($addresses as $a)
                                        <article class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                                            <div class="flex items-start justify-between gap-2">
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ ucfirst($a->address_type) }} address</p>
                                                <div class="flex gap-1">
                                                    <button type="button" onclick="openCrud('address', {{ $a->id }})" class="rounded p-1.5 text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-900/30" aria-label="Edit {{ $a->address_type }} address"><i class="bi bi-pencil-square"></i></button>
                                                    <button type="button" onclick="deleteCrud('address', {{ $a->id }})" class="rounded p-1.5 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/30" aria-label="Delete {{ $a->address_type }} address"><i class="bi bi-trash"></i></button>
                                                </div>
                                            </div>
                                            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">{{ $a->full_address ?? '—' }}</p>
                                            @if($a->address_type == 'residential' && $a->is_permanent == 0)
                                                <form method="POST" action="{{ route('myprofile.address.toggle-permanent', $a->id) }}" onsubmit="return confirm('Mark this as your permanent address too?')" class="mt-3">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="is_permanent" value="1">
                                                    <button class="text-sm font-medium text-indigo-600 hover:underline dark:text-indigo-400">This is also my permanent address</button>
                                                </form>
                                            @elseif($a->is_permanent)
                                                <p class="mt-3">{!! $chip('Also your permanent address', 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300') !!}</p>
                                            @endif
                                        </article>
                                    @endforeach
                                </div>
                            @else
                                <p class="rounded-lg bg-gray-50 p-4 text-sm text-gray-600 dark:bg-gray-900/30 dark:text-gray-400">No addresses yet. Add your residential and permanent addresses.</p>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="py-10 text-center">
                        <p class="font-medium text-gray-900 dark:text-white">No personal data yet</p>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Start with your name, birth date and contact details.</p>
                        <a href="{{ route('myprofile.editPersonalData') }}" class="{{ $btnPrimary }} mt-4">Add personal data</a>
                    </div>
                @endif
            </div>

            {{-- FAMILY --}}
            <div class="tab-content hidden" id="tab-family" role="tabpanel" aria-labelledby="tabbtn-family">
                <div class="mb-6 flex items-start justify-between gap-3">
                    <div><h2 class="text-base font-semibold text-gray-900 dark:text-white">Family background</h2><p class="text-sm text-gray-500 dark:text-gray-400">Spouse, parents and children.</p></div>
                    @if($familyBackground)
                        <a href="{{ route('myprofile.editFamilyDetails', $familyBackground->id) }}" class="{{ $btnPrimary }}"><i class="bi bi-pencil-square"></i> Edit family details</a>
                    @else
                        <a href="{{ route('myprofile.createFamilyDetails') }}" class="{{ $btnPrimary }}"><i class="bi bi-plus-lg"></i> Add family details</a>
                    @endif
                </div>
                @if($familyBackground)
                    @php $fb = $familyBackground; @endphp
                    <div class="space-y-6">
                        <div><h3 class="mb-3 border-b border-gray-200 pb-2 text-sm font-semibold text-gray-800 dark:border-gray-700 dark:text-gray-200">Spouse</h3>
                            {{ $dl(['Full name' => $fullName($fb, 'spouse'), 'Occupation' => $fb->spouse_occupation, 'Employer / business' => $fb->spouse_employer_business, 'Business address' => $fb->spouse_business_address, 'Telephone' => $fb->spouse_telephone_no]) }}</div>
                        <div><h3 class="mb-3 border-b border-gray-200 pb-2 text-sm font-semibold text-gray-800 dark:border-gray-700 dark:text-gray-200">Father</h3>
                            {{ $dl(['Full name' => $fullName($fb, 'father')]) }}</div>
                        <div><h3 class="mb-3 border-b border-gray-200 pb-2 text-sm font-semibold text-gray-800 dark:border-gray-700 dark:text-gray-200">Mother</h3>
                            {{ $dl(['Full name' => $fullName($fb, 'mother'), 'Maiden last name' => $fb->mother_maiden_last_name]) }}</div>
                    </div>
                @endif
                <div class="mt-8 border-t border-gray-200 pt-6 dark:border-gray-700">
                    @include('profile._records', ['key' => 'children', 'c' => $crud['children'], 'heading' => 'h3'])
                </div>
            </div>

            {{-- LIST SECTIONS --}}
            @foreach(['education','eligibilities','work','volwork','trainings','skills','distinctions','organizations','references'] as $key)
                <div class="tab-content hidden" id="tab-{{ $key }}" role="tabpanel" aria-labelledby="tabbtn-{{ $key }}">
                    @include('profile._records', ['key' => $key, 'c' => $crud[$key], 'heading' => 'h2'])
                </div>
            @endforeach

            {{-- BACKGROUND --}}
            <div class="tab-content hidden" id="tab-background" role="tabpanel" aria-labelledby="tabbtn-background">
                <div class="mb-6"><h2 class="text-base font-semibold text-gray-900 dark:text-white">Background information</h2><p class="text-sm text-gray-500 dark:text-gray-400">Answer every question truthfully. Choose “Yes” to give details.</p></div>
                <form id="backgroundForm" method="POST" action="{{ $backgroundInfo ? route('myprofile.background.update', $backgroundInfo->id) : route('myprofile.background.store') }}" class="space-y-4">
                    @csrf @if($backgroundInfo) @method('PUT') @endif
                    @foreach($bg as [$intro, $questions])
                        <fieldset class="rounded-lg border border-gray-200 p-5 dark:border-gray-700">
                            @if($intro)<legend class="px-1 text-sm font-medium text-gray-900 dark:text-white">{{ $intro }}</legend>@endif
                            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($questions as [$q, $text, $ph, $kind])
                                    @php $val = old($q, $backgroundInfo->$q ?? ''); @endphp
                                    <div class="py-4 first:pt-2 last:pb-0">
                                        <p class="text-sm text-gray-800 dark:text-gray-200" id="lbl-{{ $q }}">{{ $text }}</p>
                                        <div class="mt-2 flex gap-6" role="radiogroup" aria-labelledby="lbl-{{ $q }}">
                                            @foreach(['yes' => 'Yes', 'no' => 'No'] as $v => $l)
                                                <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                                    <input type="radio" name="{{ $q }}" value="{{ $v }}" required data-detail="{{ $kind ? 'detail-'.$q : '' }}" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500" {{ $val === $v ? 'checked' : '' }}> {{ $l }}
                                                </label>
                                            @endforeach
                                        </div>
                                        @if($kind)
                                            <div id="detail-{{ $q }}" class="mt-3 {{ $val === 'yes' ? '' : 'hidden' }}">
                                                <label for="in-{{ $q }}" class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">{{ $ph }}</label>
                                                @if($kind === 'area')
                                                    <textarea id="in-{{ $q }}" name="{{ $q }}_details" rows="2" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old($q.'_details', $backgroundInfo->{$q.'_details'} ?? '') }}</textarea>
                                                @else
                                                    <input id="in-{{ $q }}" type="text" name="{{ $q }}_details" value="{{ old($q.'_details', $backgroundInfo->{$q.'_details'} ?? '') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </fieldset>
                    @endforeach
                    <div class="flex justify-end"><button type="submit" class="{{ $btnPrimary }}"><i class="bi bi-check2"></i> {{ $backgroundInfo ? 'Save changes' : 'Save answers' }}</button></div>
                </form>
            </div>

            {{-- SINGLE-RECORD SECTIONS --}}
            @foreach($singles as $key => $c)
                <div class="tab-content hidden" id="tab-{{ $key }}" role="tabpanel" aria-labelledby="tabbtn-{{ $key }}">
                    <div class="mb-6 flex items-start justify-between gap-3">
                        <div><h2 class="text-base font-semibold text-gray-900 dark:text-white">{{ $c['title'] }}</h2><p class="text-sm text-gray-500 dark:text-gray-400">{{ $c['help'] }}</p></div>
                        <button type="button" onclick="openCrud('{{ $key }}'{{ $c['model'] ? ', '.$c['model']->id : '' }})" class="{{ $btnPrimary }}">
                            <i class="bi {{ $c['model'] ? 'bi-pencil-square' : 'bi-plus-lg' }}"></i> {{ $c['model'] ? 'Edit' : 'Add' }}
                        </button>
                    </div>
                    @if($c['model'])
                        {{ $dl($c['rows']) }}
                        @if($key === 'government')
                            @php $req = ['umid_number','pagibig_number','philhealth_number','philsys_number','tin_number','sss_number']; $done = collect($req)->filter(fn ($k) => !empty($gov->$k))->count(); @endphp
                            <p class="mt-6 text-sm text-gray-600 dark:text-gray-400">{{ $done }} of {{ count($req) }} required IDs provided.</p>
                        @endif
                    @else
                        <p class="rounded-lg bg-gray-50 p-6 text-center text-sm text-gray-600 dark:bg-gray-900/30 dark:text-gray-400">No {{ $c['noun'] }} on file yet.</p>
                    @endif
                </div>
            @endforeach
        </div>
    </section>
</div>

{{-- ===== Shared add/edit dialog ===== --}}
<dialog id="crudModal" class="m-auto w-full max-w-2xl rounded-xl bg-white p-0 text-gray-900 shadow-2xl dark:bg-gray-800 dark:text-white" aria-labelledby="crudTitle">
    <form id="crudForm" method="POST">
        @csrf
        <input type="hidden" name="_method" value="POST">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
            <h2 id="crudTitle" class="text-lg font-semibold"></h2>
            <button type="button" onclick="crudModal.close()" class="rounded p-1 text-gray-500 hover:text-gray-900 dark:hover:text-white" aria-label="Close"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="max-h-[65vh] overflow-y-auto px-6 py-5">
            <ul id="crudErrors" class="mb-4 hidden list-inside list-disc rounded-lg bg-red-50 p-3 text-sm text-red-700 dark:bg-red-900/30 dark:text-red-300" role="alert"></ul>
            <div id="crudBody" class="grid gap-4 sm:grid-cols-2"></div>
        </div>
        <div class="flex justify-end gap-3 border-t border-gray-200 px-6 py-4 dark:border-gray-700">
            <button type="button" onclick="crudModal.close()" class="{{ $btnGhost }}">Cancel</button>
            <button type="submit" id="crudSubmit" class="{{ $btnPrimary }}">Save</button>
        </div>
    </form>
</dialog>
<div id="toast" class="fixed bottom-6 right-6 z-50 hidden rounded-lg px-4 py-3 text-sm font-medium text-white shadow-lg" role="status" aria-live="polite"></div>
@endsection

@push('scripts')
<script>
const CFG = @json($modalCfg);
const CSRF = '{{ csrf_token() }}';
const crudModal = document.getElementById('crudModal'), crudForm = document.getElementById('crudForm'),
      crudBody = document.getElementById('crudBody'), crudErrors = document.getElementById('crudErrors');
const FIELD = 'w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white';

function toast(msg, ok = true) {
    const t = document.getElementById('toast');
    t.textContent = msg; t.className = 'fixed bottom-6 right-6 z-50 rounded-lg px-4 py-3 text-sm font-medium text-white shadow-lg ' + (ok ? 'bg-emerald-600' : 'bg-red-600');
    setTimeout(() => t.classList.add('hidden'), 3500);
}
const ask = msg => window.Swal
    ? Swal.fire({ title: 'Delete this record?', text: msg, icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', confirmButtonText: 'Delete', cancelButtonText: 'Cancel' }).then(r => r.isConfirmed)
    : Promise.resolve(confirm(msg));

function fieldHtml(f) {
    const id = 'f_' + f.name, req = f.required ? ' required' : '';
    const label = `<label for="${id}" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">${f.label}${f.required ? ' <span class="text-red-600" aria-hidden="true">*</span>' : ''}</label>`;
    let input;
    if (f.type === 'select') {
        const blank = f.blank === false ? '' : '<option value="">Select…</option>';
        input = `<select id="${id}" name="${f.name}" class="${FIELD}"${req}>${blank}${f.options.map(([v, l]) => `<option value="${v}">${l}</option>`).join('')}</select>`;
    } else if (f.type === 'textarea') {
        input = `<textarea id="${id}" name="${f.name}" rows="3" placeholder="${f.ph || ''}" class="${FIELD}"${req}></textarea>`;
    } else {
        input = `<input id="${id}" name="${f.name}" type="${f.type}" placeholder="${f.ph || ''}" ${f.attrs || ''} class="${FIELD}"${req}>`;
    }
    const hint = f.hint ? `<p class="mt-1 text-xs text-gray-500 dark:text-gray-400">${f.hint}</p>` : '';
    return `<div class="${f.full ? 'sm:col-span-2' : ''}">${label}${input}${hint}</div>`;
}

async function openCrud(key, id = null) {
    const c = CFG[key];
    document.getElementById('crudTitle').textContent = (id ? 'Edit ' : 'Add ') + c.noun;
    document.getElementById('crudSubmit').textContent = id ? 'Save changes' : 'Save';
    crudErrors.classList.add('hidden');
    crudBody.innerHTML = c.fields.map(fieldHtml).join('');
    crudForm.action = id ? `${c.base}/${id}` : c.store;
    crudForm.querySelector('[name=_method]').value = id ? 'PUT' : 'POST';

    if (id) {
        try {
            const r = await fetch(`${c.base}/${id}/edit-data`, { headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' } });
            if (!r.ok) throw new Error();
            const d = await r.json();
            c.fields.forEach(f => {
                const el = document.getElementById('f_' + f.name); if (!el) return;
                let v = d[f.name] ?? '';
                if (f.type === 'date' && v) v = String(v).slice(0, 10);
                el.value = v;
                if (f.lock) {                       // value can't change once saved; send it via a hidden field
                    el.disabled = true;
                    crudBody.insertAdjacentHTML('beforeend', `<input type="hidden" name="${f.name}_hidden" value="${v}">`);
                }
            });
        } catch { return toast('We couldn’t load this record. Please try again.', false); }
    }
    crudModal.showModal();
}

crudForm.addEventListener('submit', async e => {
    e.preventDefault();
    const btn = document.getElementById('crudSubmit'); btn.disabled = true;
    try {
        const r = await fetch(crudForm.action, { method: 'POST', body: new FormData(crudForm),
            headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json', 'X-CSRF-TOKEN': CSRF } });
        if (r.ok) { crudModal.close(); toast('Saved'); return setTimeout(() => location.reload(), 700); }
        const j = await r.json().catch(() => ({}));
        const msgs = j.errors ? Object.values(j.errors).flat() : [j.message || 'Something went wrong. Please try again.'];
        crudErrors.replaceChildren(...msgs.map(m => Object.assign(document.createElement('li'), { textContent: m })));
        crudErrors.classList.remove('hidden');
    } catch { toast('Network problem. Check your connection and try again.', false); }
    finally { btn.disabled = false; }
});
crudModal.addEventListener('click', e => { if (e.target === crudModal) crudModal.close(); });   // click outside to close

async function deleteCrud(key, id) {
    if (!await ask(`This ${CFG[key].noun} will be removed permanently.`)) return;
    const f = document.createElement('form');
    f.method = 'POST'; f.action = `${CFG[key].base}/${id}`;
    f.innerHTML = `<input type="hidden" name="_token" value="${CSRF}"><input type="hidden" name="_method" value="DELETE">`;
    document.body.appendChild(f); f.submit();
}

/* Tabs: keyboard support, remembers your place across refreshes and redirects */
document.addEventListener('DOMContentLoaded', () => {
    const tabs = [...document.querySelectorAll('.pds-tab')], panels = [...document.querySelectorAll('.tab-content')];
    const activate = (key, focus) => {
        const target = document.getElementById('tab-' + key); if (!target) return;
        tabs.forEach(t => t.setAttribute('aria-selected', t.dataset.tab === key));
        panels.forEach(p => p.classList.toggle('hidden', p !== target));
        const btn = document.getElementById('tabbtn-' + key);
        btn.scrollIntoView({ inline: 'center', block: 'nearest' }); if (focus) btn.focus();
        history.replaceState(null, '', '#' + key); sessionStorage.setItem('pdsTab', key);
    };
    tabs.forEach((t, i) => {
        t.addEventListener('click', () => activate(t.dataset.tab));
        t.addEventListener('keydown', e => {
            if (e.key === 'ArrowRight' || e.key === 'ArrowLeft')
                activate(tabs[(i + (e.key === 'ArrowRight' ? 1 : tabs.length - 1)) % tabs.length].dataset.tab, true);
        });
    });
    const want = location.hash.slice(1) || sessionStorage.getItem('pdsTab');
    activate(tabs.some(t => t.dataset.tab === want) ? want : tabs[0].dataset.tab);

    /* Background questions: show the details box only when the answer is Yes */
    document.querySelectorAll('input[type=radio][data-detail]').forEach(r => r.addEventListener('change', () => {
        if (r.dataset.detail) document.getElementById(r.dataset.detail).classList.toggle('hidden', r.value !== 'yes');
    }));
});
</script>
@endpush