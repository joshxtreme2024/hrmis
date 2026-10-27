<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Personal Data Sheet (PDS)</title>
    @include('pds._styles')
</head>
<body>
@php
    $p = $user->personalData; $govId = $user->governmentID; $family = $user->familyBackground;
    $na   = fn ($v) => filled($v) ? $v : 'N/A';
    $date = fn ($v) => $v ? date('d/m/Y', strtotime($v)) : 'N/A';
    // Period of attendance is stated in school years (e.g. 2018); accepts a plain year or a full date
    $yr   = fn ($v) => $v ? (preg_match('/^\d{4}$/', (string) $v) ? $v : date('Y', strtotime($v))) : null;

    $sex = strtolower($p->sex ?? '');
    $civil = strtolower($p->civil_status ?? '');
    $citizenship = strtolower($p->citizenship ?? '');
    $residential = $user->residentialAddress; $permanent = $user->permanentAddress;
    $pageNo = 1;
@endphp
    <div class="pds-container">

        <div class="pds-header">
            <div class="form-no">CS Form No. 212<br>Revised 2026</div>
            <div class="pds-title">Personal Data Sheet</div>
            <div class="pds-warning">
                WARNING: Any misrepresentation made in the Personal Data Sheet and the Work Experience Sheet
                shall cause the filing of administrative/criminal case/s against the person concerned.
            </div>
            <div class="pds-instructions">
                READ THE ATTACHED GUIDE TO FILLING OUT THE PERSONAL DATA SHEET (PDS) BEFORE ACCOMPLISHING THE PDS FORM.<br>
                Tick appropriate boxes (&#9744;) and use separate sheet if necessary. Do not leave blank entries; indicate N/A if not applicable.
                <strong>DO NOT ABBREVIATE.</strong>
            </div>
        </div>

        {{-- ==================== I. PERSONAL INFORMATION ==================== --}}
        <div class="pds-section">
            <div class="pds-section-title">I. PERSONAL INFORMATION</div>

            <div class="pds-grid">
                <div>
                    <div class="pds-field"><span class="pds-label">1. SURNAME</span><span class="pds-value">{{ $na($p->last_name ?? null) }}</span></div>
                    <div class="pds-field">
                        <span class="pds-label">2. FIRST NAME</span><span class="pds-value">{{ $na($p->first_name ?? null) }}</span>
                        <span class="pds-label" style="width:110px; text-align:right; margin-left:5px;">NAME EXTENSION (JR., SR)</span>
                        <span class="pds-value" style="flex:0 0 60px;">{{ $na($p->ext_name ?? null) }}</span>
                    </div>
                    <div class="pds-field"><span class="pds-label">&nbsp;&nbsp;&nbsp;MIDDLE NAME</span><span class="pds-value">{{ $na($p->middle_name ?? null) }}</span></div>
                    <div class="pds-field"><span class="pds-label">3. DATE OF BIRTH (dd/mm/yyyy)</span><span class="pds-value">{{ $date($p->birth_date ?? null) }}</span></div>
                    <div class="pds-field"><span class="pds-label">4. PLACE OF BIRTH</span><span class="pds-value">{{ $na($p->place_of_birth ?? null) }}</span></div>
                    <div class="pds-checkgroup">
                        <strong>5. SEX AT BIRTH</strong>
                        <span class="pds-check {{ $sex === 'male' ? 'checked' : '' }}"></span> Male &nbsp;&nbsp;
                        <span class="pds-check {{ $sex === 'female' ? 'checked' : '' }}"></span> Female
                    </div>
                    <div class="pds-checkgroup">
                        <strong>6. CIVIL STATUS</strong><br>
                        @foreach (['single' => 'Single', 'married' => 'Married', 'widowed' => 'Widowed', 'separated' => 'Separated'] as $k => $label)
                            <span class="pds-check {{ $civil === $k ? 'checked' : '' }}"></span> {{ $label }} &nbsp;
                        @endforeach
                        <span class="pds-check {{ $civil === 'others' ? 'checked' : '' }}"></span> Other/s:
                        <span class="pds-value" style="display:inline-block; width:120px;">{{ $civil === 'others' ? $na($p->civil_status_others ?? null) : '' }}</span>
                    </div>
                    <div class="pds-field"><span class="pds-label">7. HEIGHT (m)</span><span class="pds-value">{{ $na($p->height ?? null) }}</span></div>
                    <div class="pds-field"><span class="pds-label">8. WEIGHT (kg)</span><span class="pds-value">{{ $na($p->weight ?? null) }}</span></div>
                    <div class="pds-field"><span class="pds-label">9. BLOOD TYPE</span><span class="pds-value">{{ $na($p->blood_type ?? null) }}</span></div>
                    <div class="pds-field"><span class="pds-label">10. UMID ID NO.</span><span class="pds-value">{{ $na($govId->umid_number ?? null) }}</span></div>
                    <div class="pds-field"><span class="pds-label">11. PAG-IBIG ID NO.</span><span class="pds-value">{{ $na($govId->pagibig_number ?? null) }}</span></div>
                    <div class="pds-field"><span class="pds-label">12. PHILHEALTH NO.</span><span class="pds-value">{{ $na($govId->philhealth_number ?? null) }}</span></div>
                    <div class="pds-field"><span class="pds-label">13. PhilSys Number (PSN)</span><span class="pds-value">{{ $na($govId->philsys_number ?? null) }}</span></div>
                    <div class="pds-field"><span class="pds-label">14. TIN NO.</span><span class="pds-value">{{ $na($govId->tin_number ?? null) }}</span></div>
                    <div class="pds-field"><span class="pds-label">15. AGENCY EMPLOYEE NO.</span><span class="pds-value">{{ $na(optional($user->employment)->employee_id) }}</span></div>
                </div>

                <div>
                    <div class="pds-checkgroup">
                        <strong>16. CITIZENSHIP</strong><br>
                        <span class="pds-check {{ $citizenship === 'filipino' ? 'checked' : '' }}"></span> Filipino &nbsp;&nbsp;
                        <span class="pds-check {{ str_contains($citizenship, 'dual') ? 'checked' : '' }}"></span> Dual Citizenship<br>
                        <span style="display:inline-block; width:78px;"></span>
                        <span class="pds-check {{ ($p->dual_citizenship_type ?? '') === 'by_birth' ? 'checked' : '' }}"></span> by birth &nbsp;
                        <span class="pds-check {{ ($p->dual_citizenship_type ?? '') === 'by_naturalization' ? 'checked' : '' }}"></span> by naturalization
                        <div style="font-size:8px; font-style:italic; margin-top:2px;">If holder of dual citizenship, pls. indicate country where born/naturalized:</div>
                        <span class="pds-value" style="display:block;">{{ str_contains($citizenship, 'dual') ? $na($p->dual_citizenship_country ?? null) : '' }}</span>
                    </div>

                    @foreach (['17. RESIDENTIAL ADDRESS' => $residential, '18. PERMANENT ADDRESS' => $permanent] as $title => $addr)
                        <div class="pds-field" style="margin-top:5px;"><span class="pds-label">{{ $title }}</span></div>
                        @foreach ([['hbl_number' => 'House/Block/Lot No.', 'street' => 'Street'], ['subdi_village' => 'Subdivision/Village', 'barangay' => 'Barangay'], ['city_municipality' => 'City/Municipality', 'province' => 'Province']] as $pair)
                            <div class="pds-grid" style="margin-bottom:3px;">
                                @foreach ($pair as $col => $label)
                                    <div class="pds-field"><span class="pds-label-sm">{{ $label }}</span><span class="pds-value">{{ $na($addr->$col ?? null) }}</span></div>
                                @endforeach
                            </div>
                        @endforeach
                        <div class="pds-field"><span class="pds-label-sm">Zip Code</span><span class="pds-value">{{ $na($addr->zip_code ?? null) }}</span></div>
                    @endforeach

                    <div class="pds-field" style="margin-top:5px;"><span class="pds-label">19. TELEPHONE NO.</span><span class="pds-value">{{ $na($p->telephone_no ?? null) }}</span></div>
                    <div class="pds-field"><span class="pds-label">20. MOBILE NO.</span><span class="pds-value">{{ $na($p->mobile_no ?? null) }}</span></div>
                    <div class="pds-field"><span class="pds-label">21. E-MAIL ADDRESS (if any)</span><span class="pds-value">{{ $na($user->email ?? null) }}</span></div>
                </div>
            </div>
        </div>

        {{-- ==================== II. FAMILY BACKGROUND ==================== --}}
        <div class="pds-section">
            <div class="pds-section-title">II. FAMILY BACKGROUND</div>

            <div class="pds-grid">
                <div>
                    <div class="pds-field"><span class="pds-label">22. SPOUSE'S SURNAME</span><span class="pds-value">{{ $na($family->spouse_last_name ?? null) }}</span></div>
                    <div class="pds-field">
                        <span class="pds-label">&nbsp;&nbsp;&nbsp;FIRST NAME</span><span class="pds-value">{{ $na($family->spouse_first_name ?? null) }}</span>
                        <span class="pds-label" style="width:110px; text-align:right; margin-left:5px;">NAME EXTENSION (JR., SR)</span>
                        <span class="pds-value" style="flex:0 0 60px;">{{ $na($family->spouse_ext_name ?? null) }}</span>
                    </div>
                    <div class="pds-field"><span class="pds-label">&nbsp;&nbsp;&nbsp;MIDDLE NAME</span><span class="pds-value">{{ $na($family->spouse_middle_name ?? null) }}</span></div>
                    <div class="pds-field"><span class="pds-label">&nbsp;&nbsp;&nbsp;OCCUPATION</span><span class="pds-value">{{ $na($family->spouse_occupation ?? null) }}</span></div>
                    <div class="pds-field"><span class="pds-label">&nbsp;&nbsp;&nbsp;EMPLOYER/BUSINESS NAME</span><span class="pds-value">{{ $na($family->spouse_employer_business ?? null) }}</span></div>
                    <div class="pds-field"><span class="pds-label">&nbsp;&nbsp;&nbsp;BUSINESS ADDRESS</span><span class="pds-value">{{ $na($family->spouse_business_address ?? null) }}</span></div>
                    <div class="pds-field"><span class="pds-label">&nbsp;&nbsp;&nbsp;TELEPHONE NO.</span><span class="pds-value">{{ $na($family->spouse_telephone_no ?? null) }}</span></div>

                    <div class="pds-field" style="margin-top:5px;"><span class="pds-label">24. FATHER'S SURNAME</span><span class="pds-value">{{ $na($family->father_last_name ?? null) }}</span></div>
                    <div class="pds-field">
                        <span class="pds-label">&nbsp;&nbsp;&nbsp;FIRST NAME</span><span class="pds-value">{{ $na($family->father_first_name ?? null) }}</span>
                        <span class="pds-label" style="width:110px; text-align:right; margin-left:5px;">NAME EXTENSION (JR., SR)</span>
                        <span class="pds-value" style="flex:0 0 60px;">{{ $na($family->father_ext_name ?? null) }}</span>
                    </div>
                    <div class="pds-field"><span class="pds-label">&nbsp;&nbsp;&nbsp;MIDDLE NAME</span><span class="pds-value">{{ $na($family->father_middle_name ?? null) }}</span></div>

                    <div class="pds-field" style="margin-top:5px;"><span class="pds-label">25. MOTHER'S MAIDEN NAME</span></div>
                    <div class="pds-field"><span class="pds-label">&nbsp;&nbsp;&nbsp;SURNAME</span><span class="pds-value">{{ $na($family->mother_maiden_last_name ?? null) }}</span></div>
                    <div class="pds-field"><span class="pds-label">&nbsp;&nbsp;&nbsp;FIRST NAME</span><span class="pds-value">{{ $na($family->mother_first_name ?? null) }}</span></div>
                    <div class="pds-field"><span class="pds-label">&nbsp;&nbsp;&nbsp;MIDDLE NAME</span><span class="pds-value">{{ $na($family->mother_middle_name ?? null) }}</span></div>
                </div>

                <div>
                    <div class="pds-field"><span class="pds-label">23. NAME of CHILDREN</span></div>
                    @php $children = collect($user->children ?? []); @endphp
                    <table class="pds-table">
                        <thead>
                            <tr>
                                <th style="width:65%;">(Write full name and list all)</th>
                                <th style="width:35%;">DATE OF BIRTH (dd/mm/yyyy)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($children as $child)
                                <tr><td>{{ $na($child->name ?? null) }}</td><td>{{ $date($child->date_of_birth ?? null) }}</td></tr>
                            @empty
                                <tr><td>N/A</td><td>N/A</td></tr>
                            @endforelse
                            @for ($i = max($children->count(), 1); $i < 12; $i++)
                                <tr><td class="blank">&nbsp;</td><td>&nbsp;</td></tr>
                            @endfor
                        </tbody>
                    </table>
                    <div style="font-size:8px; font-style:italic; margin-top:4px;">(Continue on separate sheet if necessary)</div>
                </div>
            </div>
        </div>

        {{-- ==================== III. EDUCATIONAL BACKGROUND ==================== --}}
        <div class="pds-section">
            <div class="pds-section-title">III. EDUCATIONAL BACKGROUND</div>

            @php
                // Per the form there is one SECONDARY level; JUNIOR / SENIOR HIGH SCHOOL (or HIGH SCHOOL) is written in the course column.
                $levels = [
                    'ELEMENTARY'               => ['elementary'],
                    'SECONDARY'                => ['high_school', 'senior_high_school', 'secondary'],
                    'VOCATIONAL / TRADE COURSE' => ['vocational_trade_course'],
                    'COLLEGE'                  => ['college'],
                    'GRADUATE STUDIES'         => ['post_graduate'],
                ];
                $defaultCourse = ['elementary' => 'PRIMARY EDUCATION', 'high_school' => 'JUNIOR HIGH SCHOOL', 'senior_high_school' => 'SENIOR HIGH SCHOOL'];
                $eduAll = collect($user->education ?? [])->map(function ($e) {
                    $e->level_key = \Illuminate\Support\Str::slug($e->level ?? $e->level_label ?? '', '_');
                    return $e;
                });
            @endphp

            <table class="pds-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width:11%;">26. LEVEL</th>
                        <th rowspan="2" style="width:22%;">NAME OF SCHOOL<br>(Write in full)</th>
                        <th rowspan="2" style="width:20%;">BASIC EDUCATION/DEGREE/COURSE<br>(Write in full)</th>
                        <th colspan="2" style="width:14%;">PERIOD OF ATTENDANCE<br>(school year)</th>
                        <th rowspan="2" style="width:12%;">HIGHEST LEVEL/UNITS EARNED<br>(if not graduated)</th>
                        <th rowspan="2" style="width:9%;">YEAR GRADUATED</th>
                        <th rowspan="2" style="width:12%;">SCHOLARSHIP/ACADEMIC HONORS RECEIVED</th>
                    </tr>
                    <tr><th style="width:7%;">From</th><th style="width:7%;">To</th></tr>
                </thead>
                <tbody>
                    @foreach ($levels as $label => $keys)
                        @php $rows = $eduAll->whereIn('level_key', $keys)->values(); $n = max($rows->count(), 1); @endphp
                        @for ($i = 0; $i < $n; $i++)
                            @php $edu = $rows->get($i); @endphp
                            <tr>
                                @if ($i === 0)<td rowspan="{{ $n }}"><strong>{{ $label }}</strong></td>@endif
                                <td>{{ $na($edu->school_name ?? null) }}</td>
                                <td>{{ $edu ? ($edu->degree_course ?: ($defaultCourse[$edu->level_key] ?? 'N/A')) : 'N/A' }}</td>
                                <td>{{ $na($edu ? $yr($edu->period_from) : null) }}</td>
                                <td>{{ $edu ? ($edu->period_to ? $yr($edu->period_to) : ($edu->period_from ? 'Present' : 'N/A')) : 'N/A' }}</td>
                                <td>{{ $na($edu->highest_level_units_earned ?? null) }}</td>
                                <td>{{ $na($edu->year_graduated ?? null) }}</td>
                                <td>{{ $na($edu->scholarship_honors ?? null) }}</td>
                            </tr>
                        @endfor
                    @endforeach
                </tbody>
            </table>
            <div style="font-size:8px; font-style:italic; margin-top:4px;">(Continue on separate sheet if necessary)</div>
        </div>

        @include('pds._page-footer')
    </div>

    @include('pds._actions')
</body>
</html>