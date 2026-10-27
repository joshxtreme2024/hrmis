@extends('layouts.app')

@section('title', 'Employee Profile - ' . ($employee->display_name ?? ''))
@section('header', 'Employee profile')
@section('subheader', 'Personal data sheet, employment record and government IDs')

@php
    use Illuminate\Support\HtmlString;
    use Carbon\Carbon;

    $p   = $employee->personalData;
    $emp = $employee->employment;
    $fb  = $employee->familyBackground;
    $bi  = $employee->backgroundInfo;
    $latest      = $employee->latestTermination;
    $terminated  = $emp?->status === 'terminated';
    $terminations = $employee->terminationDetail ?? collect();

    $btnPrimary = 'inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700';
    $btnGhost   = 'inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700';
    $field = 'w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white';
    $lbl   = 'mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200';
    $neutral = 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200';

    /* ---- helpers ---- */
    $x    = fn ($v) => e(filled($v) ? $v : '—');
    $date = fn ($d, $f = 'M d, Y') => $d ? Carbon::parse($d)->format($f) : null;
    $chip = fn ($t, $c = null) => '<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium ' . ($c ?: $neutral) . '">' . e($t) . '</span>';
    $sub  = fn ($t) => $t ? '<span class="block text-xs text-gray-500 dark:text-gray-400">' . e($t) . '</span>' : '';
    $label = fn ($v) => ucfirst(str_replace('_', ' ', (string) $v));
    $dl = function (array $rows) {
        $h = '<dl class="grid gap-x-6 gap-y-4 sm:grid-cols-2 lg:grid-cols-3">';
        foreach ($rows as $k => $v) {
            $h .= '<div><dt class="text-xs text-gray-500 dark:text-gray-400">' . e($k) . '</dt><dd class="mt-0.5 text-sm font-medium text-gray-900 dark:text-white">' . e(filled($v) ? $v : '—') . '</dd></div>';
        }
        return new HtmlString($h . '</dl>');
    };
    $tbl = function (array $cols, $rows, string $empty) {
        if (!$rows || $rows->count() === 0) {
            return new HtmlString('<p class="rounded-lg border border-dashed border-gray-300 p-8 text-center text-sm text-gray-600 dark:border-gray-600 dark:text-gray-400">' . e($empty) . '</p>');
        }
        $h = '<div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700"><table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700"><thead class="bg-gray-50 dark:bg-gray-900/30"><tr class="text-left text-gray-600 dark:text-gray-400">';
        foreach (array_keys($cols) as $l) { $h .= '<th scope="col" class="px-4 py-3 font-medium">' . e($l) . '</th>'; }
        $h .= '</tr></thead><tbody class="divide-y divide-gray-200 dark:divide-gray-700">';
        foreach ($rows as $i => $r) {
            $h .= '<tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">';
            foreach ($cols as $fn) { $h .= '<td class="px-4 py-3 align-top text-gray-700 dark:text-gray-300">' . $fn($r, $i) . '</td>'; }
            $h .= '</tr>';
        }
        return new HtmlString($h . '</tbody></table></div>');
    };
    $mask = fn ($v) => filled($v) ? str_repeat('•', max(strlen($v) - 4, 0)) . substr($v, -4) : '—';

    $contractDays = null;
    $contractState = null;   // 'expired' | 'critical' | 'soon' | 'ok' | null
    if ($emp?->contract_end) {
        $contractDays = (int) Carbon::now()->startOfDay()
            ->diffInDays(Carbon::parse($emp->contract_end)->startOfDay(), false);

        $contractState = match (true) {
            $contractDays < 0   => 'expired',
            $contractDays <= 7  => 'critical',
            $contractDays <= 30 => 'soon',
            default             => 'ok',
        };
    }

    $contractBadge = function () use ($contractDays, $contractState, $chip, $date, $emp) {
        if (!$emp?->contract_end || $contractState === 'ok') return null;

        $text = match ($contractState) {
            'expired'  => 'Contract expired ' . abs($contractDays) . ' day' . (abs($contractDays) === 1 ? '' : 's') . ' ago',
            'critical' => $contractDays === 0
                            ? 'Contract ends today'
                            : 'Contract ends in ' . $contractDays . ' day' . ($contractDays === 1 ? '' : 's'),
            'soon'     => 'Contract ends in ' . $contractDays . ' days',
        };

        $colors = match ($contractState) {
            'expired'  => 'bg-red-100 text-red-800 ring-1 ring-inset ring-red-200 dark:bg-red-900/40 dark:text-red-300 dark:ring-red-800',
            'critical' => 'bg-orange-100 text-orange-800 ring-1 ring-inset ring-orange-200 dark:bg-orange-900/40 dark:text-orange-300 dark:ring-orange-800',
            'soon'     => 'bg-amber-100 text-amber-800 ring-1 ring-inset ring-amber-200 dark:bg-amber-900/40 dark:text-amber-300 dark:ring-amber-800',
        };

        $icon = match ($contractState) {
            'expired'  => 'bi-exclamation-octagon',
            'critical' => 'bi-exclamation-triangle',
            'soon'     => 'bi-hourglass-split',
        };

        return '<span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium ' . $colors . '" title="Contract end: ' . e($date($emp->contract_end, 'M d, Y')) . '">'
            . '<i class="bi ' . $icon . '" aria-hidden="true"></i>' . e($text) . '</span>';
    };

    $initials = strtoupper(substr($p?->first_name ?? '', 0, 1) . substr($p?->last_name ?? '', 0, 1)) ?: '?';
    $sorted = fn ($rel) => $rel ? $rel->sortBy('order') : collect();

    $bg = [
        ['q34_a', 'Related by consanguinity or affinity to the appointing or recommending authority, chief of office, or immediate supervisor: within the third degree'],
        ['q34_b', 'Same, within the fourth degree (LGU career employees)'],
        ['q35_a', 'Found guilty of any administrative offense'],
        ['q35_b', 'Criminally charged before any court'],
        ['q36', 'Convicted of any crime or violation of any law, decree, ordinance or regulation'],
        ['q37', 'Separated from the service (resignation, retirement, dropped from the rolls, dismissal, termination, end of term, finished contract or phased out)'],
        ['q38_a', 'Candidate in a national or local election within the last year (except Barangay)'],
        ['q38_b', 'Resigned from government service within 3 months before the last election to campaign for a candidate'],
        ['q39', 'Immigrant or permanent resident of another country'],
        ['q40_a', 'Member of an indigenous group'],
        ['q40_b', 'Person with disability'],
        ['q40_c', 'Solo parent'],
    ];
    $disclosures = collect($bg)->filter(fn ($q) => ($bi->{$q[0]} ?? null) === 'yes')->count();

    $govIds = ['GSIS' => 'gsis_number', 'SSS' => 'sss_number', 'Pag-IBIG' => 'pagibig_number', 'PhilHealth' => 'philhealth_number', 'TIN' => 'tin_number', "Driver's license" => 'dl_number', 'UMID' => 'umid_number', 'PRC' => 'prc_number'];

    $tabs = [
        'personal'      => ['Personal', 'bi bi-person', null],
        'family'        => ['Family', 'bi bi-people', null],
        'education'     => ['Education', 'bi bi-mortarboard', $employee->education?->count() ?? 0],
        'eligibility'   => ['Eligibility', 'bi bi-patch-check', $employee->eligibilities?->count() ?? 0],
        'work'          => ['Work experience', 'bi bi-briefcase', $employee->workExperiences?->count() ?? 0],
        'voluntary'     => ['Voluntary work', 'bi bi-heart', $employee->voluntaryWorks?->count() ?? 0],
        'trainings'     => ['Training', 'bi bi-journal-check', $employee->trainings?->count() ?? 0],
        'skills'        => ['Skills', 'bi bi-stars', $employee->skills?->count() ?? 0],
        'distinctions'  => ['Distinctions', 'bi bi-trophy', $employee->distinctions?->count() ?? 0],
        'organizations' => ['Organizations', 'bi bi-diagram-2', $employee->organizations?->count() ?? 0],
        'background'    => ['Background', 'bi bi-clipboard-check', $disclosures ?: null],
        'references'    => ['References', 'bi bi-person-lines-fill', $employee->references?->count() ?? 0],
        'employment'    => ['Employment', 'bi bi-building', null],
        'government'    => ['Government IDs', 'bi bi-person-vcard', null],
    ];
@endphp

@section('header-actions')
    <button type="button" onclick="history.back()" class="{{ $btnGhost }}"><i class="bi bi-arrow-left"></i> Back</button>
    @if($emp?->user_id)
    <a href="{{ route('management.employment.edit', $emp ? : 0) }}" class="{{ $btnGhost }}"><i class="bi bi-pencil-square"></i> Edit profile</a>
    @endif
    <button type="button" onclick="window.print()" class="{{ $btnGhost }}"><i class="bi bi-printer"></i> Print</button>
@endsection

@section('breadcrumbs')
    <li class="flex items-center"><i class="bi bi-chevron-right mx-2 text-xs text-gray-300 dark:text-gray-600"></i>
        <a href="{{ route('employees.index') }}" class="font-medium text-gray-800 hover:text-indigo-600 dark:text-gray-200 dark:hover:text-indigo-400">Employee management</a></li>
    <li class="flex items-center"><i class="bi bi-chevron-right mx-2 text-xs text-gray-300 dark:text-gray-600"></i>
        <span class="font-medium text-gray-800 dark:text-gray-200">Employee profile</span></li>
@endsection

@section('content')
<style>
    .pds-tabs { scrollbar-width: thin; }
    .pds-tab[aria-selected="true"] { color: #4338ca; border-color: #4338ca; background: rgb(238 242 255 / .6); }
    .dark .pds-tab[aria-selected="true"] { color: #a5b4fc; border-color: #a5b4fc; background: rgb(49 46 129 / .25); }
    :is(.pds-tab, a, button, input, select, textarea, summary):focus-visible { outline: 2px solid #6366f1; outline-offset: 2px; }
    dialog::backdrop { background: rgb(17 24 39 / .6); }
    @media print { .pds-tabs { display: none; } .tab-content { display: block !important; break-inside: avoid; } }
    @media (prefers-reduced-motion: reduce) { * { transition: none !important; } }
</style>

<div class="space-y-6 p-6">

    {{-- ===== Termination notice ===== --}}
    @if($terminated && $latest)
        <section class="rounded-xl border border-red-200 bg-red-50 p-5 dark:border-red-800 dark:bg-red-900/20" role="alert" aria-label="Termination notice">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <p class="font-semibold text-red-900 dark:text-red-200"><i class="bi bi-person-x mr-1" aria-hidden="true"></i> Employment ended on {{ $date($latest->termination_date, 'F d, Y') }}</p>
                    <p class="mt-1 text-sm text-red-800 dark:text-red-300">Reason: {{ $label($latest->termination_reason ?? 'N/A') }}@if($latest->remarks) · {{ $latest->remarks }}@endif</p>
                    @if($latest->termination_details)<p class="mt-2 max-w-3xl text-sm text-red-800 dark:text-red-300">{{ $latest->termination_details }}</p>@endif
                </div>
                @if($terminations->count())
                    <button type="button" onclick="historyModal.showModal()" class="{{ $btnGhost }} print:hidden"><i class="bi bi-clock-history"></i> View all ({{ $terminations->count() }})</button>
                @endif
            </div>
        </section>
    @endif

    {{-- ===== Profile summary ===== --}}
    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800" aria-label="Profile summary">
        <div class="flex flex-col gap-6 p-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-5">
                <div class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-full bg-indigo-50 shadow ring-4 ring-white dark:bg-indigo-900/40 dark:ring-gray-800">
                    @if($employee->profile_picture)
                        <img src="{{ asset('storage/' . $employee->profile_picture) }}" alt="Photo of {{ $employee->display_name }}" class="h-full w-full object-cover">
                    @else
                        <span class="text-2xl font-semibold text-indigo-700 dark:text-indigo-300" aria-hidden="true">{{ $initials }}</span>
                    @endif
                </div>
                <div class="min-w-0">
                    <h1 class="truncate text-2xl font-semibold text-gray-900 dark:text-white">{{ $employee->display_name }}</h1>
                    <p class="mt-1 flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-600 dark:text-gray-400">
                        <span><i class="bi bi-envelope mr-1" aria-hidden="true"></i>{{ $employee->email }}</span>
                        <span><i class="bi bi-phone mr-1" aria-hidden="true"></i>{{ $p?->mobile_no ?? $p?->contact_number ?? 'No contact number' }}</span>
                    </p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        {!! $chip($emp ? $emp->status_label : 'No employment record', $emp?->status_badge) !!}
                        @if($p?->sex){!! $chip(ucfirst($p->sex)) !!}@endif
                        {!! $chip('Account created ' . $employee->created_at->format('M d, Y')) !!}
                        @if($badge = $contractBadge())
                            {!! $badge !!}
                        @endif
                    </div>
                </div>
            </div>
            <div class="print:hidden">
                @if($emp)
                    @if(!$terminated)
                        <button type="button" onclick="terminateModal.showModal()" class="inline-flex items-center gap-2 rounded-lg border border-red-300 bg-white px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-50 dark:border-red-800 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-red-900/20"><i class="bi bi-person-x"></i> Terminate employment</button>
                    @else
                        <button type="button" onclick="confirmReactivate()" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700"><i class="bi bi-arrow-counterclockwise"></i> Reactivate employment</button>
                        <form id="reactivateForm" action="{{ route('employees.termination.cancel', $employee) }}" method="POST" class="hidden">@csrf @method('PATCH')</form>
                    @endif
                @endif
            </div>
        </div>
        <div class="border-t border-gray-200 bg-gray-50 px-6 py-5 dark:border-gray-700 dark:bg-gray-900/30">
            {{ $dl(['Employee ID' => $emp?->employee_id, 'Department' => $emp?->department?->name, 'Position' => $emp?->position?->title, 'Employment type' => $emp?->employment_type_label, 'Date hired' => $date($emp?->hired_at), 'Monthly salary' => $emp?->formatted_salary, 'End of Contract' => $date($emp?->contract_end) ? : 'N/A' ]) }}
            @if($emp?->contract_end)
                <p class="mt-4 text-sm {{ $contractState === 'ok' ? 'text-gray-600 dark:text-gray-400' : 'font-medium text-amber-700 dark:text-amber-400' }}">
                    <i class="bi bi-calendar-event mr-1" aria-hidden="true"></i>
                    Contract ends {{ $date($emp->contract_end, 'F d, Y') }}
                    @if($contractDays >= 0)
                        · {{ $contractDays }} day{{ $contractDays === 1 ? '' : 's' }} remaining
                    @else
                        · expired {{ abs($contractDays) }} day{{ abs($contractDays) === 1 ? '' : 's' }} ago
                    @endif
                </p>
            @else
                @if(!$emp)
                    <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                        <i class="bi bi-info-circle mr-1" aria-hidden="true"></i>
                        This employee has no employment information yet.
                    </p>
                @elseif($emp->employment_type === 'permanent' && !$emp->contract_end)
                    <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                        <i class="bi bi-info-circle mr-1" aria-hidden="true"></i>
                        This employee has permanent (regular) employment status.
                    </p>
                @endif
            @endif
        </div>
    </section>

    {{-- ===== Tabs ===== --}}
    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="pds-tabs flex overflow-x-auto" role="tablist" aria-label="Employee record sections">
                @foreach($tabs as $key => [$tl, $icon, $count])
                    <button type="button" role="tab" data-tab="{{ $key }}" id="tabbtn-{{ $key }}" aria-controls="tab-{{ $key }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                            class="pds-tab -mb-px inline-flex shrink-0 items-center gap-2 border-b-2 border-transparent px-4 py-3.5 text-sm font-medium text-gray-600 hover:border-gray-300 hover:text-gray-900 dark:text-gray-400 dark:hover:border-gray-600 dark:hover:text-white">
                        <i class="{{ $icon }}" aria-hidden="true"></i>{{ $tl }}
                        @if($count)<span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs tabular-nums text-gray-600 dark:bg-gray-700 dark:text-gray-300">{{ $count }}</span>@endif
                    </button>
                @endforeach
            </nav>
        </div>

        <div class="p-6">
            @php $h2 = 'mb-4 text-base font-semibold text-gray-900 dark:text-white'; $h3 = 'mb-3 border-b border-gray-200 pb-2 text-sm font-semibold text-gray-800 dark:border-gray-700 dark:text-gray-200'; @endphp

            <div class="tab-content" id="tab-personal" role="tabpanel" aria-labelledby="tabbtn-personal">
                <h2 class="{{ $h2 }}">Personal information</h2>
                <div class="space-y-6">
                    <div><h3 class="{{ $h3 }}">Name</h3>{{ $dl(['First name' => $p?->first_name, 'Middle name' => $p?->middle_name, 'Last name' => $p?->last_name, 'Name extension' => $p?->ext_name]) }}</div>
                    <div><h3 class="{{ $h3 }}">Birth and demographics</h3>{{ $dl(['Sex' => ucfirst((string) $p?->sex), 'Birth date' => $date($p?->birth_date, 'F d, Y'), 'Place of birth' => $p?->place_of_birth, 'Civil status' => ucfirst((string) $p?->civil_status), 'Nationality' => $p?->nationality, 'Religion' => $p?->religion]) }}</div>
                    <div><h3 class="{{ $h3 }}">Physical details</h3>{{ $dl(['Height (cm)' => $p?->height, 'Weight (kg)' => $p?->weight, 'Blood type' => $p?->blood_type]) }}</div>
                    <div><h3 class="{{ $h3 }}">Contact and addresses</h3>{{ $dl(['Mobile' => $p?->mobile_no, 'Telephone' => $p?->telephone_no, 'Email' => $employee->email, 'Residential address' => $employee->residentialAddress?->full_address, 'Permanent address' => $employee->permanentAddress?->full_address]) }}</div>
                </div>
            </div>

            <div class="tab-content hidden" id="tab-family" role="tabpanel" aria-labelledby="tabbtn-family">
                <h2 class="{{ $h2 }}">Family background</h2>
                @if($fb)
                    <div class="space-y-6">
                        @if($fb->has_spouse)
                            <div><h3 class="{{ $h3 }}">Spouse</h3>{{ $dl(['Full name' => $fb->spouse_full_name, 'Occupation' => $fb->spouse_occupation, 'Employer / business' => $fb->spouse_employer_business, 'Business address' => $fb->spouse_business_address, 'Telephone' => $fb->spouse_telephone_no]) }}</div>
                        @endif
                        <div><h3 class="{{ $h3 }}">Father</h3>{{ $dl(['Full name' => $fb->father_full_name, 'Initials' => $fb->father_initials]) }}</div>
                        <div><h3 class="{{ $h3 }}">Mother</h3>{{ $dl(['Full name' => $fb->mother_full_name, 'Maiden name' => $fb->mother_maiden_name]) }}</div>
                    </div>
                @else
                    {{ $tbl([], collect(), 'No family background on file.') }}
                @endif
            </div>

            <div class="tab-content hidden" id="tab-education" role="tabpanel" aria-labelledby="tabbtn-education">
                <h2 class="{{ $h2 }}">Educational background</h2>
                {{ $tbl([
                    'Level' => fn ($r) => $chip($r->level_label, $r->level_badge_color),
                    'School' => fn ($r) => '<span class="font-medium text-gray-900 dark:text-white">' . e($r->school_name) . '</span>',
                    'Course / degree' => fn ($r) => $x($r->degree_course),
                    'Period' => fn ($r) => e($r->getPeriodFormattedAttribute()),
                    'Honors' => fn ($r) => $x($r->scholarship_honors),
                ], $sorted($employee->education), 'No educational background on file.') }}
            </div>

            <div class="tab-content hidden" id="tab-eligibility" role="tabpanel" aria-labelledby="tabbtn-eligibility">
                <h2 class="{{ $h2 }}">Civil service eligibility</h2>
                {{ $tbl([
                    'Eligibility' => fn ($r) => '<span class="font-medium text-gray-900 dark:text-white">' . e($r->career_service) . '</span>',
                    'Rating' => fn ($r) => $x($r->formatted_rating),
                    'Examination' => fn ($r) => $x($date($r->examination_date)) . $sub($r->examination_place),
                    'License' => fn ($r) => $r->license_number ? e($r->license_number) . $sub('Valid until ' . ($date($r->license_date_validity) ?? 'N/A')) . '<span class="mt-1 block">' . $chip($r->license_status_label ?? 'No license', $r->license_status_badge ?? null) . '</span>' : '—',
                ], $sorted($employee->eligibilities), 'No eligibility records on file.') }}
            </div>

            <div class="tab-content hidden" id="tab-work" role="tabpanel" aria-labelledby="tabbtn-work">
                <h2 class="{{ $h2 }}">Work experience</h2>
                {{ $tbl([
                    'Position' => fn ($r) => '<span class="font-medium text-gray-900 dark:text-white">' . e($r->position_title) . '</span>' . $sub($r->department_agency_office),
                    'Period' => fn ($r) => e($r->period_range) . $sub($r->duration),
                    'Sector' => fn ($r) => $chip($r->sector_label, $r->sector_badge_color),
                    'Appointment' => fn ($r) => $chip($r->status_of_appointment_label, $r->status_badge_color),
                    'Salary' => fn ($r) => $x($r->formatted_salary),
                ], $sorted($employee->workExperiences), 'No work experience on file.') }}
            </div>

            <div class="tab-content hidden" id="tab-voluntary" role="tabpanel" aria-labelledby="tabbtn-voluntary">
                <h2 class="{{ $h2 }}">Voluntary work</h2>
                {{ $tbl([
                    'Organization' => fn ($r) => '<span class="font-medium text-gray-900 dark:text-white">' . e($r->organization_name) . '</span>' . $sub($r->organization_address),
                    'Position' => fn ($r) => $x($r->position_nature_of_work),
                    'Period' => fn ($r) => e($date($r->inclusive_from) ?? '—') . ' – ' . e($r->inclusive_to ? $date($r->inclusive_to) : 'Present'),
                    'Hours' => fn ($r) => $r->number_of_hours ? e($r->number_of_hours) . ' hrs' : '—',
                ], $employee->voluntaryWorks, 'No voluntary work on file.') }}
            </div>

            <div class="tab-content hidden" id="tab-trainings" role="tabpanel" aria-labelledby="tabbtn-trainings">
                <h2 class="{{ $h2 }}">Learning and development</h2>
                {{ $tbl([
                    'Program' => fn ($r) => '<span class="font-medium text-gray-900 dark:text-white">' . e($r->title_of_program) . '</span>' . ($r->has_type ? '<span class="mt-1 block">' . $chip($r->formatted_type, $r->type_badge_color) . '</span>' : ''),
                    'Conducted by' => fn ($r) => $x($r->conducted_by),
                    'Period' => fn ($r) => e($date($r->inclusive_from) ?? '—') . ($r->inclusive_to ? ' – ' . e($date($r->inclusive_to)) : ''),
                    'Hours' => fn ($r) => $r->number_of_hours ? e($r->number_of_hours) . ' hrs' : '—',
                ], $employee->trainings, 'No trainings on file.') }}
            </div>

            <div class="tab-content hidden" id="tab-skills" role="tabpanel" aria-labelledby="tabbtn-skills">
                <h2 class="{{ $h2 }}">Special skills and hobbies</h2>
                {{ $tbl(['Skill / hobby' => fn ($r) => $x($r->skill_hobby)], $employee->skills, 'No skills or hobbies on file.') }}
            </div>

            <div class="tab-content hidden" id="tab-distinctions" role="tabpanel" aria-labelledby="tabbtn-distinctions">
                <h2 class="{{ $h2 }}">Distinctions and recognition</h2>
                {{ $tbl(['Distinction / award' => fn ($r) => $x($r->distinctions)], $employee->distinctions, 'No distinctions on file.') }}
            </div>

            <div class="tab-content hidden" id="tab-organizations" role="tabpanel" aria-labelledby="tabbtn-organizations">
                <h2 class="{{ $h2 }}">Membership in associations</h2>
                {{ $tbl(['Organization' => fn ($r) => $x($r->organization)], $employee->organizations, 'No organizations on file.') }}
            </div>

            <div class="tab-content hidden" id="tab-background" role="tabpanel" aria-labelledby="tabbtn-background">
                <h2 class="{{ $h2 }}">Background information</h2>
                @if(!$bi)
                    {{ $tbl([], collect(), 'The employee has not answered the background questions yet.') }}
                @else
                    <p class="mb-4 text-sm {{ $disclosures ? 'font-medium text-amber-700 dark:text-amber-400' : 'text-gray-600 dark:text-gray-400' }}">
                        {{ $disclosures ? $disclosures . ' ' . \Illuminate\Support\Str::plural('question', $disclosures) . ' answered “Yes”. Review the details below.' : 'No “Yes” answers.' }}
                    </p>
                    <ul class="divide-y divide-gray-200 rounded-lg border border-gray-200 dark:divide-gray-700 dark:border-gray-700">
                        @foreach($bg as [$k, $text])
                            @php $ans = $bi->$k ?? null; $detail = $bi->{$k . '_details'} ?? null; @endphp
                            <li class="flex flex-col gap-2 p-4 sm:flex-row sm:items-start sm:justify-between {{ $ans === 'yes' ? 'bg-amber-50/60 dark:bg-amber-900/10' : '' }}">
                                <div class="max-w-3xl">
                                    <p class="text-sm text-gray-800 dark:text-gray-200">{{ $text }}</p>
                                    @if($detail)<p class="mt-1 text-sm text-gray-600 dark:text-gray-400"><span class="font-medium">Details:</span> {{ $detail }}</p>@endif
                                </div>
                                <div class="shrink-0">{!! $ans === 'yes' ? $chip('Yes', 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300') : ($ans === 'no' ? $chip('No') : $chip('Not answered', 'bg-gray-50 text-gray-500 ring-1 ring-inset ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-600')) !!}</div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="tab-content hidden" id="tab-references" role="tabpanel" aria-labelledby="tabbtn-references">
                <h2 class="{{ $h2 }}">References</h2>
                {{ $tbl([
                    'Name' => fn ($r) => '<span class="font-medium text-gray-900 dark:text-white">' . e($r->name) . '</span>',
                    'Occupation' => fn ($r) => $x($r->occupation),
                    'Contact' => fn ($r) => $x($r->contact_number),
                ], $employee->references, 'No references on file.') }}
            </div>

            <div class="tab-content hidden" id="tab-employment" role="tabpanel" aria-labelledby="tabbtn-employment">
                <h2 class="{{ $h2 }}">Employment record</h2>
                @if($emp)
                    {{ $dl(['Employee ID' => $emp->employee_id, 'Department' => $emp->department?->name, 'Position' => $emp->position?->title, 'Employment type' => $emp->employment_type_label, 'Status' => $emp->status_label, 'Date hired' => $emp->formatted_hire_date, 'Monthly salary' => $emp->formatted_salary, 'End of contract' => $date($emp->contract_end, 'F d, Y')]) }}
                @else
                    {{ $tbl([], collect(), 'No employment information on file.') }}
                @endif
            </div>

            <div class="tab-content hidden" id="tab-government" role="tabpanel" aria-labelledby="tabbtn-government">
                <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
                    <div><h2 class="text-base font-semibold text-gray-900 dark:text-white">Government IDs</h2><p class="text-sm text-gray-600 dark:text-gray-400">Confidential. Numbers are hidden until you choose to show them.</p></div>
                    <button type="button" id="govToggle" class="{{ $btnGhost }} print:hidden" aria-pressed="false"><i class="bi bi-eye" aria-hidden="true"></i> <span>Show numbers</span></button>
                </div>
                <dl class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($govIds as $gl => $gk)
                        @php $v = $sensitiveData[$gk] ?? null; @endphp
                        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                            <dt class="text-xs text-gray-500 dark:text-gray-400">{{ $gl }}</dt>
                            <dd class="mt-1 font-mono text-sm font-medium text-gray-900 dark:text-white">
                                <span class="gov-masked">{{ $mask($v) }}</span><span class="gov-full hidden">{{ $v ?: '—' }}</span>
                            </dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </div>
    </section>
</div>

{{-- ===== Terminate dialog ===== --}}
<dialog id="terminateModal" class="m-auto w-full max-w-md rounded-xl bg-white p-0 text-gray-900 shadow-2xl dark:bg-gray-800 dark:text-white" aria-labelledby="terminateTitle">
    <form action="{{ route('employees.terminate', $employee) }}" method="POST" id="terminateForm">
        @csrf
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
            <h2 id="terminateTitle" class="text-lg font-semibold">Terminate employment</h2>
            <button type="button" onclick="terminateModal.close()" class="rounded p-1 text-gray-500 hover:text-gray-900 dark:hover:text-white" aria-label="Close"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="space-y-4 px-6 py-5">
            <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-300" role="alert">
                You are ending the employment of <strong>{{ $employee->display_name }}</strong>. Their account will lose access to the system. You can reactivate it later if this was a mistake.
            </div>
            <div><label for="termination_date" class="{{ $lbl }}">Termination date <span class="text-red-600" aria-hidden="true">*</span></label>
                <input type="date" id="termination_date" name="termination_date" required class="{{ $field }}"></div>
            <div><label for="termination_reason" class="{{ $lbl }}">Reason <span class="text-red-600" aria-hidden="true">*</span></label>
                <select id="termination_reason" name="termination_reason" required class="{{ $field }}">
                    <option value="">Select a reason</option>
                    @foreach(['resignation' => 'Resignation', 'retirement' => 'Retirement', 'death' => 'Death', 'dismissal' => 'Dismissal', 'drop_from_roll' => 'Dropped from the rolls', 'end_of_term' => 'End of term', 'laid_off' => 'Laid off', 'separation' => 'Separation', 'termination' => 'Termination', 'other' => 'Other'] as $v => $t)
                        <option value="{{ $v }}">{{ $t }}</option>
                    @endforeach
                </select></div>
            <div><label for="termination_details" class="{{ $lbl }}">Details <span id="detailsReq" class="hidden text-red-600" aria-hidden="true">*</span></label>
                <textarea id="termination_details" name="termination_details" rows="3" class="{{ $field }}" placeholder="Add any notes for the record"></textarea>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Required when the reason is “Other”.</p></div>
        </div>
        <div class="flex justify-end gap-3 border-t border-gray-200 px-6 py-4 dark:border-gray-700">
            <button type="button" onclick="terminateModal.close()" class="{{ $btnGhost }}">Cancel</button>
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700"><i class="bi bi-person-x"></i> Terminate employment</button>
        </div>
    </form>
</dialog>

{{-- ===== Termination history dialog ===== --}}
<dialog id="historyModal" class="m-auto w-full max-w-2xl rounded-xl bg-white p-0 text-gray-900 shadow-2xl dark:bg-gray-800 dark:text-white" aria-labelledby="historyTitle">
    <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
        <h2 id="historyTitle" class="text-lg font-semibold">Termination history · {{ $terminations->count() }}</h2>
        <button type="button" onclick="historyModal.close()" class="rounded p-1 text-gray-500 hover:text-gray-900 dark:hover:text-white" aria-label="Close"><i class="bi bi-x-lg"></i></button>
    </div>
    <ol class="max-h-[65vh] space-y-3 overflow-y-auto px-6 py-5">
        @forelse($terminations->sortByDesc('termination_date') as $t)
            <li class="rounded-lg border p-4 {{ $loop->first ? 'border-red-300 dark:border-red-800' : 'border-gray-200 dark:border-gray-700' }}">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <p class="font-medium">{{ $date($t->termination_date, 'F d, Y') }} @if($loop->first){!! $chip('Most recent', 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-300') !!}@endif</p>
                    {!! $chip($label($t->termination_reason ?? 'N/A')) !!}
                </div>
                @if($t->remarks)<p class="mt-2 text-sm text-gray-700 dark:text-gray-300"><span class="font-medium">Remarks:</span> {{ $t->remarks }}</p>@endif
                @if($t->termination_details)<p class="mt-1 text-sm text-gray-700 dark:text-gray-300"><span class="font-medium">Details:</span> {{ $t->termination_details }}</p>@endif
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Recorded by {{ $t->createdBy->email ?? 'System' }} on {{ $t->created_at->format('M d, Y h:i A') }}@if($t->updated_at != $t->created_at) · updated {{ $t->updated_at->format('M d, Y h:i A') }}@endif</p>
            </li>
        @empty
            <li class="py-8 text-center text-sm text-gray-600 dark:text-gray-400">No termination records.</li>
        @endforelse
    </ol>
    <div class="flex justify-end border-t border-gray-200 px-6 py-4 dark:border-gray-700"><button type="button" onclick="historyModal.close()" class="{{ $btnGhost }}">Close</button></div>
</dialog>
@endsection

@push('scripts')
<script>
const terminateModal = document.getElementById('terminateModal'), historyModal = document.getElementById('historyModal');
[terminateModal, historyModal].forEach(d => d.addEventListener('click', e => { if (e.target === d) d.close(); }));

/* Terminate form */
const reasonSel = document.getElementById('termination_reason'), detailsBox = document.getElementById('termination_details');
terminateModal.addEventListener('close', () => { document.getElementById('terminateForm').reset(); document.getElementById('detailsReq').classList.add('hidden'); });
reasonSel.addEventListener('change', () => {
    const other = reasonSel.value === 'other';
    detailsBox.required = other; document.getElementById('detailsReq').classList.toggle('hidden', !other);
});
document.getElementById('terminateForm').addEventListener('submit', e => {
    if (!document.getElementById('termination_date').value) e.preventDefault();
});
document.getElementById('termination_date').valueAsDate = new Date();

/* Reactivate */
function confirmReactivate() {
    const text = 'The account will be reactivated and the employment status set back to active.';
    const go = () => document.getElementById('reactivateForm').submit();
    if (window.Swal) {
        Swal.fire({ title: 'Reactivate employment?', text, icon: 'question', showCancelButton: true,
            confirmButtonColor: '#059669', confirmButtonText: 'Reactivate', cancelButtonText: 'Cancel' }).then(r => r.isConfirmed && go());
    } else if (confirm(text)) { go(); }
}

/* Government IDs: masked by default */
const govToggle = document.getElementById('govToggle');
govToggle.addEventListener('click', () => {
    const show = govToggle.getAttribute('aria-pressed') !== 'true';
    govToggle.setAttribute('aria-pressed', show);
    govToggle.querySelector('span').textContent = show ? 'Hide numbers' : 'Show numbers';
    govToggle.querySelector('i').className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
    document.querySelectorAll('.gov-masked').forEach(el => el.classList.toggle('hidden', show));
    document.querySelectorAll('.gov-full').forEach(el => el.classList.toggle('hidden', !show));
});

/* Tabs: keyboard support, remembers the open section */
document.addEventListener('DOMContentLoaded', () => {
    const tabs = [...document.querySelectorAll('.pds-tab')], panels = [...document.querySelectorAll('.tab-content')];
    const activate = (key, focus) => {
        const target = document.getElementById('tab-' + key); if (!target) return;
        tabs.forEach(t => t.setAttribute('aria-selected', t.dataset.tab === key));
        panels.forEach(p => p.classList.toggle('hidden', p !== target));
        const btn = document.getElementById('tabbtn-' + key);
        btn.scrollIntoView({ inline: 'center', block: 'nearest' }); if (focus) btn.focus();
        history.replaceState(null, '', '#' + key);
    };
    tabs.forEach((t, i) => {
        t.addEventListener('click', () => activate(t.dataset.tab));
        t.addEventListener('keydown', e => {
            if (e.key === 'ArrowRight' || e.key === 'ArrowLeft')
                activate(tabs[(i + (e.key === 'ArrowRight' ? 1 : tabs.length - 1)) % tabs.length].dataset.tab, true);
        });
    });
    const want = location.hash.slice(1);
    activate(tabs.some(t => t.dataset.tab === want) ? want : tabs[0].dataset.tab);
});
</script>
@endpush