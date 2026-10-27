<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Personal Data Sheet (PDS)</title>
    @include('pds._styles')
</head>
<body>
@php
    $p = $user->personalData;
    $date = fn ($v) => $v ? date('d/m/Y', strtotime($v)) : 'N/A';
    $na   = fn ($v) => filled($v) ? $v : 'N/A';
    $pageNo = 2;
@endphp
    <div class="pds-container">

        {{-- ==================== IV. CIVIL SERVICE ELIGIBILITY ==================== --}}
        <div class="pds-section">
            <div class="pds-section-title">IV. CIVIL SERVICE ELIGIBILITY</div>

            @php $eligibilities = collect($user->eligibilities ?? []); @endphp
            <table class="pds-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width:30%;">27. CES/CSEE/CAREER SERVICE/RA 1080 (BOARD/BAR)/UNDER SPECIAL LAWS/CATEGORY II/IV ELIGIBILITY and ELIGIBILITIES FOR UNIFORMED PERSONNEL</th>
                        <th rowspan="2" style="width:10%;">RATING<br>(If Applicable)</th>
                        <th rowspan="2" style="width:15%;">DATE OF EXAMINATION / CONFERMENT<br>(dd/mm/yyyy)</th>
                        <th rowspan="2" style="width:20%;">PLACE OF EXAMINATION / CONFERMENT</th>
                        <th colspan="2" style="width:25%;">LICENSE (if applicable)</th>
                    </tr>
                    <tr><th style="width:13%;">NUMBER</th><th style="width:12%;">Valid Until</th></tr>
                </thead>
                <tbody>
                    @forelse ($eligibilities as $e)
                        <tr>
                            <td>{{ $na($e->career_service ?? null) }}</td>
                            <td>{{ $na($e->rating ?? null) }}</td>
                            <td>{{ $date($e->examination_date ?? null) }}</td>
                            <td>{{ $na($e->examination_place ?? null) }}</td>
                            <td>{{ $na($e->license_number ?? null) }}</td>
                            <td>{{ $date($e->license_date_validity ?? null) }}</td>
                        </tr>
                    @empty
                        <tr><td>N/A</td><td>N/A</td><td>N/A</td><td>N/A</td><td>N/A</td><td>N/A</td></tr>
                    @endforelse
                    @for ($i = max($eligibilities->count(), 1); $i < 7; $i++)
                        <tr><td class="blank">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                    @endfor
                </tbody>
            </table>
            <div style="font-size:8px; font-style:italic; margin-top:4px;">(Continue on separate sheet if necessary)</div>
        </div>

        {{-- ==================== V. WORK EXPERIENCE ==================== --}}
        <div class="pds-section">
            <div class="pds-section-title">V. WORK EXPERIENCE</div>
            <div class="pds-note">
                (Include private employment. Start from your recent work.) Description of duties should be indicated
                in the attached Work Experience Sheet.
            </div>

            @php $works = collect($user->workExperiences ?? []); @endphp
            <table class="pds-table">
                <thead>
                    <tr>
                        <th colspan="2" style="width:15%;">28. INCLUSIVE DATES<br>(dd/mm/yyyy)</th>
                        <th rowspan="2" style="width:16%;">POSITION TITLE<br>(Write in full/Do not abbreviate)</th>
                        <th rowspan="2" style="width:19%;">DEPARTMENT/AGENCY/OFFICE/COMPANY<br>(Write in full/Do not abbreviate)</th>
                        <th rowspan="2" style="width:11%;">MONTHLY SALARY</th>
                        <th rowspan="2" style="width:13%;">SALARY/JOB/PAY GRADE (if applicable) &amp; STEP INCREMENT<br>(Format "00-0")</th>
                        <th rowspan="2" style="width:14%;">STATUS OF APPOINTMENT</th>
                        <th rowspan="2" style="width:8%;">GOV'T SERVICE<br>(Y/N)</th>
                    </tr>
                    <tr><th style="width:7.5%;">From</th><th style="width:7.5%;">To</th></tr>
                </thead>
                <tbody>
                    @forelse ($works as $w)
                        <tr>
                            <td>{{ $date($w->inclusive_from ?? null) }}</td>
                            <td>{{ ($w->inclusive_to ?? null) ? date('d/m/Y', strtotime($w->inclusive_to)) : 'Present' }}</td>
                            <td>{{ $na($w->position_title ?? null) }}</td>
                            <td>{{ $na($w->department_agency_office ?? null) }}</td>
                            <td>{{ filled($w->monthly_salary ?? null) ? 'P' . number_format($w->monthly_salary, 2) : 'N/A' }}</td>
                            <td>{{ $na($w->salary_grade ?? null) }}</td>
                            <td>{{ $w->status_of_appointment ? ucwords(str_replace('_', ' ', $w->status_of_appointment)) : 'N/A' }}</td>
                            <td>{{ is_null($w->is_gov ?? null) ? 'N/A' : ($w->is_gov == 1 ? 'Y' : 'N') }}</td>
                        </tr>
                    @empty
                        <tr><td>N/A</td><td>N/A</td><td>N/A</td><td>N/A</td><td>N/A</td><td>N/A</td><td>N/A</td><td>N/A</td></tr>
                    @endforelse
                    @for ($i = max($works->count(), 1); $i < 7; $i++)
                        <tr><td class="blank">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                    @endfor
                </tbody>
            </table>
            <div style="font-size:8px; font-style:italic; margin-top:4px;">(Continue on separate sheet if necessary)</div>
        </div>

        @include('pds._page-footer')
    </div>

    @include('pds._actions')
</body>
</html>