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
        <!-- ==================== PAGE 2 ==================== -->

        <!-- ==================== IV. CIVIL SERVICE ELIGIBILITY ==================== -->
        <div class="pds-section">
             @php $p = $user->personalData; $govId = $user->governmentID; @endphp
            <div class="pds-section-title">IV. CIVIL SERVICE ELIGIBILITY</div>

            <table class="pds-table">
                <thead>
                    <tr>
                        <th style="width:28%;">27. CES/CSEE/CAREER SERVICE/RA 1080 (BOARD/BAR)/UNDER SPECIAL LAWS/CATEGORY II/IV ELIGIBILITY and ELIGIBILITIES FOR UNIFORMED PERSONNEL</th>
                        <th style="width:12%;">RATING<br>(If Applicable)</th>
                        <th style="width:15%;">DATE OF EXAMINATION/CONFERMENT</th>
                        <th style="width:20%;">PLACE OF EXAMINATION/CONFERMENT</th>
                        <th style="width:13%;">LICENSE NUMBER<br>(if applicable)</th>
                        <th style="width:12%;">Valid Until</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($user->eligibilities ?? [] as $eligibility)
                        <tr>
                            <td>{{ $eligibility->career_service ?? '' }}</td>
                            <td>{{ $eligibility->rating ?? '' }}</td>
                            <td>{{ $eligibility->examination_date ? date('d/m/Y', strtotime($eligibility->examination_date)) : '' }}</td>
                            <td>{{ $eligibility->examination_place ?? '' }}</td>
                            <td>{{ $eligibility->license_number ?? '' }}</td>
                            <td>{{ $eligibility->license_date_validity ? date('d/m/Y', strtotime($eligibility->license_date_validity)) : '' }}</td>
                        </tr>
                    @empty
                        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div style="font-size: 8px; font-style: italic; margin-top: 4px;">(Continue on separate sheet if necessary)</div>
        </div>

        <!-- ==================== V. WORK EXPERIENCE ==================== -->
        <div class="pds-section">
            <div class="pds-section-title">V. WORK EXPERIENCE</div>
            <div class="pds-note">
                28. (Include private employment. Start from your recent work.) Description of duties should be indicated
                in the attached Work Experience Sheet.
            </div>

            <table class="pds-table">
                <thead>
                    <tr>
                        <th colspan="2" style="width:14%;">INCLUSIVE DATES<br>(dd/mm/yyyy)</th>
                        <th style="width:16%;">POSITION TITLE<br>(Write in full/Do not abbreviate)</th>
                        <th style="width:18%;">DEPARTMENT/AGENCY/OFFICE/COMPANY<br>(Write in full/Do not abbreviate)</th>
                        <th style="width:11%;">MONTHLY SALARY</th>
                        <th style="width:13%;">SALARY/JOB/PAY GRADE (if applicable) &amp; STEP INCREMENT<br>(Format: "00-0")</th>
                        <th style="width:11%;">STATUS OF APPOINTMENT</th>
                        <th style="width:9%;">GOV'T SERVICE (Y/N)</th>
                    </tr>
                    <tr>
                        <th style="width:7%;">From</th>
                        <th style="width:7%;">To</th>
                        <th colspan="5"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($user->workExperiences ?? [] as $work)
                        <tr>
                            <td>{{ $work->inclusive_from ? date('d/m/Y', strtotime($work->inclusive_from)) : '' }}</td>
                            <td>{{ $work->inclusive_to ? date('d/m/Y', strtotime($work->inclusive_to)) : 'Present' }}</td>
                            <td>{{ $work->position_title ?? '' }}</td>
                            <td>{{ $work->department_agency_office ?? '' }}</td>
                            <td>{{ $work->monthly_salary ? number_format($work->monthly_salary, 2) : '' }}</td>
                            <td>{{ $work->salary_grade ?? '' }}</td>
                            <td>{{ $work->status_of_appointment ? ucwords(str_replace('_', ' ', $work->status_of_appointment)) : '' }}</td>
                            <td>{{ is_null($work->is_gov) ? '' : ($work->is_gov == 1 ? 'Y' : 'N') }}</td>
                        </tr>
                    @empty
                        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div style="font-size: 8px; font-style: italic; margin-top: 4px;">(Continue on separate sheet if necessary)</div>
        </div>

        <!-- Page 2 signature -->
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
            <div style="text-align:right; font-size:7px; margin-top:4px;">CS FORM 212 (Revised 2026), Page 2 of 4</div>
        </div>
    </div>

    <!-- ==================== PRINT BUTTONS ==================== -->
    <div class="pds-print-btn no-print">
        <button onclick="window.print()">🖨️ Print PDS Form</button>
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