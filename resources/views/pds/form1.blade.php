<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Personal Data Sheet (PDS)</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            background: #fff;
            padding: 10px;
        }

        .pds-container {
            max-width: 8.5in;
            margin: 0 auto;
            background: #fff;
        }

        .pds-header {
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }

        .pds-header .form-no {
            font-size: 9px;
            font-weight: bold;
            font-style: italic;
        }

        .pds-title {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            margin: 4px 0;
        }

        .pds-warning {
            font-size: 8px;
            font-weight: bold;
            font-style: italic;
            margin-top: 4px;
        }

        .pds-instructions {
            font-size: 8px;
            font-style: italic;
            margin-top: 3px;
        }

        .pds-section {
            margin-bottom: 10px;
            border: 1px solid #000;
            padding: 8px;
            page-break-inside: avoid;
        }

        .pds-section-title {
            background: #999;
            color: #fff;
            font-weight: bold;
            padding: 3px 8px;
            margin: -8px -8px 8px -8px;
            border-bottom: 1px solid #000;
            font-size: 11px;
        }

        .pds-field {
            display: flex;
            margin-bottom: 3px;
            align-items: baseline;
            font-size: 9px;
        }

        .pds-label {
            width: 150px;
            font-weight: bold;
            flex-shrink: 0;
            font-size: 9px;
        }

        .pds-label-sm {
            width: 110px;
        }

        .pds-value {
            border-bottom: 1px solid #000;
            padding: 0 5px;
            flex: 1;
            min-height: 18px;
            font-size: 9px;
        }

        .pds-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5px;
        }

        .pds-grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 5px;
        }

        .pds-checkgroup {
            font-size: 9px;
            margin-bottom: 3px;
        }

        .pds-check {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            margin-right: 3px;
            text-align: center;
            vertical-align: middle;
            line-height: 10px;
            font-size: 8px;
        }

        .pds-check.checked::after {
            content: '✓';
            font-weight: bold;
        }

        .pds-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 3px;
            font-size: 8px;
        }

        .pds-table th,
        .pds-table td {
            border: 1px solid #000;
            padding: 3px 4px;
            text-align: left;
            font-size: 8px;
            vertical-align: top;
        }

        .pds-table th {
            background: #e8e8e8;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        .pds-note {
            font-size: 8px;
            font-style: italic;
            margin-bottom: 4px;
        }

        .pds-q {
            font-size: 9px;
            margin-bottom: 6px;
            padding-bottom: 4px;
            border-bottom: 1px dotted #999;
        }

        .pds-q:last-child {
            border-bottom: none;
        }

        .pds-photo-box {
            width: 90px;
            height: 115px;
            border: 1px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 7px;
            padding: 4px;
        }

        .pds-thumbmark-box {
            width: 90px;
            height: 90px;
            border: 1px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 7px;
            padding: 4px;
        }

        .pds-sign-box {
            border: 1px solid #000;
            min-height: 40px;
            padding: 4px;
            font-size: 9px;
            text-align: center;
            color: #c00;
            font-style: italic;
        }

        .pds-print-btn {
            text-align: center;
            margin: 20px;
            padding: 20px;
        }

        .pds-print-btn button {
            padding: 10px 30px;
            font-size: 14px;
            cursor: pointer;
            background: #0d6efd;
            color: #fff;
            border: none;
            border-radius: 5px;
            margin: 0 5px;
        }

        .pds-print-btn button:hover {
            background: #0b5ed7;
        }

        .pds-print-btn button.btn-secondary {
            background: #6c757d;
        }

        .pds-print-btn button.btn-secondary:hover {
            background: #5c636a;
        }

        .pds-print-btn button.btn-success {
            background: #198754;
        }

        .pds-print-btn button.btn-success:hover {
            background: #157347;
        }

        .page-break {
            page-break-after: always;
        }

        @media print {
            body { padding: 0; margin: 0; background: #fff; }
            .pds-container { padding: 10px; max-width: 100%; }
            .no-print { display: none !important; }
            .pds-section { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="pds-container">

        <!-- ==================== PAGE 1 ==================== -->
        <div class="pds-header">
            <div class="form-no">CS Form No. 212<br>Revised 2026</div>
            <div class="pds-title">Personal Data Sheet</div>
            <div class="pds-warning">
                WARNING: Any misrepresentation made in the Personal Data Sheet and the Work Experience Sheet
                shall cause the filing of administrative/criminal case/s against the person concerned.
            </div>
            <div class="pds-instructions">
                READ THE ATTACHED GUIDE TO FILLING OUT THE PERSONAL DATA SHEET (PDS) BEFORE ACCOMPLISHING THE PDS FORM.<br>
                Print legibly if accomplished through own handwriting. Tick appropriate boxes (&#9744;) and use separate sheet if necessary.
                Indicate N/A if not applicable. <strong>DO NOT ABBREVIATE.</strong>
            </div>
        </div>

        <!-- ==================== I. PERSONAL INFORMATION ==================== -->
        <div class="pds-section">
            <div class="pds-section-title">I. PERSONAL INFORMATION</div>

            @php $p = $user->personalData; $govId = $user->governmentID; @endphp

            <div class="pds-grid">
                <div>
                    <div class="pds-field">
                        <span class="pds-label">1. SURNAME</span>
                        <span class="pds-value">{{ $p->last_name ?? 'N/A' }}</span>
                    </div>
                    <div class="pds-field">
                        <span class="pds-label">2. FIRST NAME</span>
                        <span class="pds-value">{{ $p->first_name ?? '' }}</span>
                        <span class="pds-label" style="width:110px; text-align:right; margin-left:5px;">NAME EXT. (JR., SR)</span>
                        <span class="pds-value" style="flex:0 0 60px;">{{ $p->ext_name ?? 'N/A' }}</span>
                    </div>
                    <div class="pds-field">
                        <span class="pds-label">&nbsp;&nbsp;&nbsp;MIDDLE NAME</span>
                        <span class="pds-value">{{ $p->middle_name ?? 'N/A' }}</span>
                    </div>
                    <div class="pds-field">
                        <span class="pds-label">3. DATE OF BIRTH</span>
                        <span class="pds-value">{{ $p->birth_date ? date('d/m/Y', strtotime($p->birth_date)) : 'N/A' }}</span>
                    </div>
                    <div class="pds-field">
                        <span class="pds-label">4. PLACE OF BIRTH</span>
                        <span class="pds-value">{{ $p->place_of_birth ?? 'N/A' }}</span>
                    </div>
                    <div class="pds-checkgroup">
                        <strong>5. SEX AT BIRTH</strong>
                        <span class="pds-check {{ strtolower($p->sex ?? '') === 'male' ? 'checked' : '' }}"></span> Male
                        &nbsp;&nbsp;
                        <span class="pds-check {{ strtolower($p->sex ?? '') === 'female' ? 'checked' : '' }}"></span> Female
                    </div>
                    <div class="pds-checkgroup">
                        <strong>6. CIVIL STATUS</strong><br>
                        @php $civil = strtolower($p->civil_status ?? ''); @endphp
                        <span class="pds-check {{ $civil === 'single' ? 'checked' : '' }}"></span> Single
                        &nbsp;
                        <span class="pds-check {{ $civil === 'married' ? 'checked' : '' }}"></span> Married
                        &nbsp;
                        <span class="pds-check {{ $civil === 'widowed' ? 'checked' : '' }}"></span> Widowed
                        &nbsp;
                        <span class="pds-check {{ $civil === 'separated' ? 'checked' : '' }}"></span> Separated
                        &nbsp;
                        <span class="pds-check {{ $civil === 'others' ? 'checked' : '' }}"></span> Other/s:
                        <span class="pds-value" style="display:inline-block; width:120px;">{{ $civil === 'others' ? ($p->civil_status_others ?? '') : '' }}</span>
                    </div>
                    <div class="pds-field">
                        <span class="pds-label">7. HEIGHT (m)</span>
                        <span class="pds-value">{{ $p->height ?? 'N/A' }}</span>
                    </div>
                    <div class="pds-field">
                        <span class="pds-label">8. WEIGHT (kg)</span>
                        <span class="pds-value">{{ $p->weight ?? 'N/A' }}</span>
                    </div>
                    <div class="pds-field">
                        <span class="pds-label">9. BLOOD TYPE</span>
                        <span class="pds-value">{{ $p->blood_type ?? 'N/A' }}</span>
                    </div>
                    <div class="pds-field">
                        <span class="pds-label">10. UMID ID NO.</span>
                        <span class="pds-value">{{ $govId->umid_number ?? 'N/A' }}</span>
                    </div>
                    <div class="pds-field">
                        <span class="pds-label">11. PAG-IBIG ID NO.</span>
                        <span class="pds-value">{{ $govId->pagibig_number ?? 'N/A' }}</span>
                    </div>
                    <div class="pds-field">
                        <span class="pds-label">12. PHILHEALTH NO.</span>
                        <span class="pds-value">{{ $govId->philhealth_number ?? 'N/A' }}</span>
                    </div>
                    <div class="pds-field">
                        <span class="pds-label">13. PhilSys Number (PSN)</span>
                        <span class="pds-value">{{ $govId->philsys_number ?? 'N/A' }}</span>
                    </div>
                    <div class="pds-field">
                        <span class="pds-label">14. TIN NO.</span>
                        <span class="pds-value">{{ $govId->tin_number ?? '' }}</span>
                    </div>
                    <div class="pds-field">
                        <span class="pds-label">15. AGENCY EMPLOYEE NO.</span>
                        <span class="pds-value">{{ $p->employment->employee_id ?? 'N/A' }}</span>
                    </div>
                </div>

                <div>
                    @php $citizenship = strtolower($p->citizenship ?? ''); @endphp
                    <div class="pds-checkgroup">
                        <strong>16. CITIZENSHIP</strong><br>
                        <span class="pds-check {{ $citizenship === 'Filipino' ? 'checked' : '' }}"></span> Filipino
                        &nbsp;&nbsp;
                        <span class="pds-check {{ str_contains($citizenship, 'dual') ? 'checked' : '' }}"></span> Dual Citizenship
                        <br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="pds-check {{ ($p->dual_citizenship_type ?? '') === 'by_birth' ? 'checked' : '' }}"></span> by birth
                        &nbsp;
                        <span class="pds-check {{ ($p->dual_citizenship_type ?? '') === 'by_naturalization' ? 'checked' : '' }}"></span> by naturalization
                        <div style="font-size:8px; font-style:italic; margin-top:2px;">If holder of dual citizenship, pls. indicate country:</div>
                        <span class="pds-value" style="display:block;">{{ $p->dual_citizenship_country ?? '' }}</span>
                    </div>

                    @php
                        $residential = $user->residentialAddress;
                        $permanent = $user->permanentAddress;
                    @endphp

                    <div class="pds-field">
                        <span class="pds-label">17. RESIDENTIAL ADDRESS</span>
                    </div>
                    <div class="pds-grid" style="margin-bottom:3px;">
                        <div class="pds-field"><span class="pds-label-sm" style="font-weight:normal; font-style:italic;">House/Block/Lot No.</span><span class="pds-value">{{ $residential->hbl_number ?? 'N/A' }}</span></div>
                        <div class="pds-field"><span class="pds-label-sm" style="font-weight:normal; font-style:italic;">Street</span><span class="pds-value">{{ $residential->street ?? 'N/A' }}</span></div>
                    </div>
                    <div class="pds-grid" style="margin-bottom:3px;">
                        <div class="pds-field"><span class="pds-label-sm" style="font-weight:normal; font-style:italic;">Subdivision/Village</span><span class="pds-value">{{ $residential->subdi_village ?? 'N/A' }}</span></div>
                        <div class="pds-field"><span class="pds-label-sm" style="font-weight:normal; font-style:italic;">Barangay</span><span class="pds-value">{{ $residential->barangay ?? 'N/A' }}</span></div>
                    </div>
                    <div class="pds-grid" style="margin-bottom:3px;">
                        <div class="pds-field"><span class="pds-label-sm" style="font-weight:normal; font-style:italic;">City/Municipality</span><span class="pds-value">{{ $residential->city_municipality ?? 'N/A' }}</span></div>
                        <div class="pds-field"><span class="pds-label-sm" style="font-weight:normal; font-style:italic;">Province</span><span class="pds-value">{{ $residential->province ?? 'N/A' }}</span></div>
                    </div>
                    <div class="pds-field"><span class="pds-label-sm" style="font-weight:normal; font-style:italic;">Zip Code</span><span class="pds-value">{{ $residential->zip_code ?? 'N/A' }}</span></div>

                    <div class="pds-field" style="margin-top:5px;">
                        <span class="pds-label">18. PERMANENT ADDRESS</span>
                    </div>
                    <div class="pds-grid" style="margin-bottom:3px;">
                        <div class="pds-field"><span class="pds-label-sm" style="font-weight:normal; font-style:italic;">House/Block/Lot No.</span><span class="pds-value">{{ $permanent->hbl_number ?? 'N/A' }}</span></div>
                        <div class="pds-field"><span class="pds-label-sm" style="font-weight:normal; font-style:italic;">Street</span><span class="pds-value">{{ $permanent->street ?? 'N/A' }}</span></div>
                    </div>
                    <div class="pds-grid" style="margin-bottom:3px;">
                        <div class="pds-field"><span class="pds-label-sm" style="font-weight:normal; font-style:italic;">Subdivision/Village</span><span class="pds-value">{{ $permanent->subdi_village ?? 'N/A' }}</span></div>
                        <div class="pds-field"><span class="pds-label-sm" style="font-weight:normal; font-style:italic;">Barangay</span><span class="pds-value">{{ $permanent->barangay ?? 'N/A' }}</span></div>
                    </div>
                    <div class="pds-grid" style="margin-bottom:3px;">
                        <div class="pds-field"><span class="pds-label-sm" style="font-weight:normal; font-style:italic;">City/Municipality</span><span class="pds-value">{{ $permanent->city_municipality ?? 'N/A' }}</span></div>
                        <div class="pds-field"><span class="pds-label-sm" style="font-weight:normal; font-style:italic;">Province</span><span class="pds-value">{{ $permanent->province ?? 'N/A' }}</span></div>
                    </div>
                    <div class="pds-field"><span class="pds-label-sm" style="font-weight:normal; font-style:italic;">Zip Code</span><span class="pds-value">{{ $permanent->zip_code ?? 'N/A' }}</span></div>

                    <div class="pds-field" style="margin-top:5px;">
                        <span class="pds-label">19. TELEPHONE NO.</span>
                        <span class="pds-value">{{ $p->telephone_no ?? 'N/A' }}</span>
                    </div>
                    <div class="pds-field">
                        <span class="pds-label">20. MOBILE NO.</span>
                        <span class="pds-value">{{ $p->mobile_no ?? 'N/A' }}</span>
                    </div>
                    <div class="pds-field">
                        <span class="pds-label">21. E-MAIL ADDRESS (if any)</span>
                        <span class="pds-value">{{ $user->email ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== II. FAMILY BACKGROUND ==================== -->
        <div class="pds-section">
            <div class="pds-section-title">II. FAMILY BACKGROUND</div>

            @php $family = $user->familyBackground; @endphp

            <div class="pds-grid">
                <div>
                    <div class="pds-field">
                        <span class="pds-label">22. SPOUSE'S SURNAME</span>
                        <span class="pds-value">{{ $family->spouse_last_name ?? 'N/A' }}</span>
                    </div>
                    <div class="pds-field">
                        <span class="pds-label">&nbsp;&nbsp;&nbsp;FIRST NAME</span>
                        <span class="pds-value">{{ $family->spouse_first_name ?? '' }}</span>
                        <span class="pds-label" style="width:110px; text-align:right; margin-left:5px;">NAME EXT. (JR., SR)</span>
                        <span class="pds-value" style="flex:0 0 60px;">{{ $family->spouse_ext_name ?? 'N/A' }}</span>
                    </div>
                    <div class="pds-field">
                        <span class="pds-label">&nbsp;&nbsp;&nbsp;MIDDLE NAME</span>
                        <span class="pds-value">{{ $family->spouse_middle_name ?? 'N/A' }}</span>
                    </div>
                    <div class="pds-field">
                        <span class="pds-label">&nbsp;&nbsp;&nbsp;OCCUPATION</span>
                        <span class="pds-value">{{ $family->spouse_occupation ?? 'N/A' }}</span>
                    </div>
                    <div class="pds-field">
                        <span class="pds-label">&nbsp;&nbsp;&nbsp;EMPLOYER/BUSINESS NAME</span>
                        <span class="pds-value">{{ $family->spouse_employer_business ?? '' }}</span>
                    </div>
                    <div class="pds-field">
                        <span class="pds-label">&nbsp;&nbsp;&nbsp;BUSINESS ADDRESS</span>
                        <span class="pds-value">{{ $family->spouse_business_address ?? 'N/A' }}</span>
                    </div>
                    <div class="pds-field">
                        <span class="pds-label">&nbsp;&nbsp;&nbsp;TELEPHONE NO.</span>
                        <span class="pds-value">{{ $family->spouse_telephone_no ?? 'N/A' }}</span>
                    </div>

                    <div class="pds-field" style="margin-top:5px;">
                        <span class="pds-label">24. FATHER'S SURNAME</span>
                        <span class="pds-value">{{ $family->father_last_name ?? 'N/A' }}</span>
                    </div>
                    <div class="pds-field">
                        <span class="pds-label">&nbsp;&nbsp;&nbsp;FIRST NAME</span>
                        <span class="pds-value">{{ $family->father_first_name ?? 'N/A' }}</span>
                        <span class="pds-label" style="width:110px; text-align:right; margin-left:5px;">NAME EXT. (JR., SR)</span>
                        <span class="pds-value" style="flex:0 0 60px;">{{ $family->father_ext_name ?? 'N/A' }}</span>
                    </div>
                    <div class="pds-field">
                        <span class="pds-label">&nbsp;&nbsp;&nbsp;MIDDLE NAME</span>
                        <span class="pds-value">{{ $family->father_middle_name ?? 'N/A' }}</span>
                    </div>

                    <div class="pds-field" style="margin-top:5px;">
                        <span class="pds-label">25. MOTHER'S MAIDEN NAME</span>
                    </div>
                    <div class="pds-field">
                        <span class="pds-label">&nbsp;&nbsp;&nbsp;SURNAME</span>
                        <span class="pds-value">{{ $family->mother_maiden_last_name ?? 'N/A' }}</span>
                    </div>
                    <div class="pds-field">
                        <span class="pds-label">&nbsp;&nbsp;&nbsp;FIRST NAME</span>
                        <span class="pds-value">{{ $family->mother_first_name ?? 'N/A' }}</span>
                    </div>
                    <div class="pds-field">
                        <span class="pds-label">&nbsp;&nbsp;&nbsp;MIDDLE NAME</span>
                        <span class="pds-value">{{ $family->mother_middle_name ?? 'N/A' }}</span>
                    </div>
                </div>

                <div>
                    <div class="pds-field">
                        <span class="pds-label">23. NAME OF CHILDREN</span>
                    </div>
                    <table class="pds-table">
                        <thead>
                            <tr>
                                <th style="width:65%;">Name (write full name and list all)</th>
                                <th style="width:35%;">Date of Birth (dd/mm/yyyy)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->children ?? [] as $child)
                                <tr>
                                    <td>{{ $child->name ?? 'N/A' }}</td>
                                    <td>{{ $child->date_of_birth ? date('d/m/Y', strtotime($child->date_of_birth)) : 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr><td>N/A</td><td>N/A</td></tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div style="font-size: 8px; font-style: italic; margin-top: 4px;">(Continue on separate sheet if necessary)</div>
                </div>
            </div>
        </div>

        <!-- ==================== III. EDUCATIONAL BACKGROUND ==================== -->
        <div class="pds-section">
            <div class="pds-section-title">III. EDUCATIONAL BACKGROUND</div>

            @php
                // 26. Fixed levels per the official form — map stored records onto these rows.
                $eduLevels = [
                    'elementary' => 'ELEMENTARY',
                    'high_school' => 'JUNIOR HIGH SCHOOL',
                    'senior_high_school' => 'SENIOR HIGH SCHOOL',
                    'vocational_trade_course' => 'VOCATIONAL / TRADE COURSE',
                    'college' => 'COLLEGE',
                    'post_graduate' => 'GRADUATE STUDIES',
                ];
                $eduByLevel = collect($user->education ?? [])->keyBy(function ($edu) {
                    return \Illuminate\Support\Str::slug($edu->level ?? $edu->level_label ?? '', '_');
                });
            @endphp

            <table class="pds-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width:10%;">26. LEVEL</th>
                        <th rowspan="2" style="width:22%;">NAME OF SCHOOL<br>(Write in full)</th>
                        <th rowspan="2" style="width:20%;">BASIC EDUCATION/DEGREE/COURSE<br>(Write in full)</th>
                        <th colspan="2" style="width:14%;">PERIOD OF ATTENDANCE</th>
                        <th rowspan="2" style="width:12%;">HIGHEST LEVEL/UNITS EARNED<br>(if not graduated)</th>
                        <th rowspan="2" style="width:10%;">YEAR GRADUATED</th>
                        <th rowspan="2" style="width:12%;">SCHOLARSHIP/ACADEMIC HONORS RECEIVED</th>
                    </tr>
                    <tr>
                        <th style="width:7%;">From</th>
                        <th style="width:7%;">To</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($eduLevels as $key => $label)
                        @php $edu = $eduByLevel->get($key); @endphp
                        <tr>
                            <td><strong>{{ $label }}</strong></td>
                            <td>{{ $edu->school_name ?? 'N/A' }}</td>
                            <td>{{ $edu->degree_course ?? 'N/A' }}</td>
                            <td>{{ $edu && $edu->period_from ? date('d/m/Y', strtotime($edu->period_from)) : 'N/A' }}</td>
                            <td>
                                @if($edu && $edu->period_to)
                                    {{ date('d/m/Y', strtotime($edu->period_to)) }}
                                @elseif($edu && $edu->period_from)
                                    Present
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $edu->highest_level_units_earned ?? 'N/A' }}</td>
                            <td>{{ $edu->year_graduated ?? 'N/A' }}</td>
                            <td>{{ $edu->scholarship_honors ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="font-size: 8px; font-style: italic; margin-top: 4px;">(Continue on separate sheet if necessary)</div>
        </div>

        <!-- Page 1 signature -->
        <div class="pds-section" style="border-top: 2px solid #000;">
            <div class="pds-grid">
                <div>
                    <div style="font-weight:bold; font-size:9px; margin-bottom:3px;">SIGNATURE</div>
                    <div class="pds-sign-box">{{ $p->signature ?? '(wet signature/e-signature/digital certificate)' }}</div>
                </div>
                <div>
                    <div style="font-weight:bold; font-size:9px; margin-bottom:3px;">DATE</div>
                    <div class="pds-sign-box" style="color:#000; font-style:normal;">{{ $p->signature_date ? date('d/m/Y', strtotime($p->signature_date)) : '' }}</div>
                </div>
            </div>
            <div style="text-align:right; font-size:7px; margin-top:4px;">CS FORM 212 (Revised 2026), Page 1 of 4</div>
        </div>
    </div>

    <!-- ==================== PRINT BUTTONS ==================== -->
    <div class="pds-print-btn no-print">
        <button onclick="window.print()">🖨️ Print</button>
        <button onclick="window.location.href='{{ route('pds.pdf', $user->id) }}'" class="btn-success">
            📄 Download PDF
        </button>
        <button onclick="window.close()" class="btn-secondary">✖ Close</button>
    </div>

    <script>
        if (window.location.search.includes('print=true')) {
            window.onload = function() {
                window.print();
            }
        }
    </script>
</body>
</html>