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
        <!-- ==================== PAGE 3 ==================== -->

        <!-- ==================== VI. VOLUNTARY WORK ==================== -->
        <div class="pds-section">
            @php $p = $user->personalData; $govId = $user->governmentID; @endphp
            <div class="pds-section-title">VI. VOLUNTARY WORK OR INVOLVEMENT IN CIVIC / NON-GOVERNMENT / PEOPLE / VOLUNTARY ORGANIZATION/S</div>

            <table class="pds-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width:34%;">29. NAME &amp; ADDRESS OF ORGANIZATION<br>(Write in full)</th>
                        <th rowspan="2" style="width:24%;">POSITION/NATURE OF WORK</th>
                        <th colspan="2" style="width:24%;">INCLUSIVE DATES<br>(dd/mm/yyyy)</th>
                        <th rowspan="2" style="width:18%;">NUMBER OF HOURS</th>
                    </tr>
                    <tr>
                        <th style="width:12%;">From</th>
                        <th style="width:12%;">To</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($user->voluntaryWorks ?? [] as $voluntary)
                        <tr>
                            <td>{{ $voluntary->organization_name ?? '' }}
                                @if(!empty($voluntary->organization_address))<br><small>{{ $voluntary->organization_address }}</small>@endif
                            </td>
                            <td>{{ $voluntary->position_nature_of_work ?? '' }}</td>
                            <td>{{ $voluntary->inclusive_from ? date('d/m/Y', strtotime($voluntary->inclusive_from)) : '' }}</td>
                            <td>{{ $voluntary->inclusive_to ? date('d/m/Y', strtotime($voluntary->inclusive_to)) : '' }}</td>
                            <td>{{ $voluntary->number_of_hours ?? '' }}</td>
                        </tr>
                    @empty
                        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div style="font-size: 8px; font-style: italic; margin-top: 4px;">(Continue on separate sheet if necessary)</div>
        </div>

        <!-- ==================== VII. LEARNING AND DEVELOPMENT ==================== -->
        <div class="pds-section">
            <div class="pds-section-title">VII. LEARNING AND DEVELOPMENT (L&amp;D) INTERVENTIONS/TRAINING PROGRAMS ATTENDED</div>

            <table class="pds-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width:26%;">30. TITLE OF LEARNING AND DEVELOPMENT INTERVENTIONS/TRAINING PROGRAMS<br>(Write in full)</th>
                        <th rowspan="2" style="width:22%;">CONDUCTED/SPONSORED BY<br>(Write in full)</th>
                        <th colspan="2" style="width:22%;">INCLUSIVE DATES OF ATTENDANCE<br>(dd/mm/yyyy)</th>
                        <th rowspan="2" style="width:14%;">NUMBER OF HOURS</th>
                        <th rowspan="2" style="width:16%;">Type of L&amp;D<br>(Managerial/Supervisory/Technical/etc)</th>
                    </tr>
                    <tr>
                        <th style="width:11%;">From</th>
                        <th style="width:11%;">To</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($user->trainings ?? [] as $training)
                        <tr>
                            <td>{{ $training->title_of_program ?? '' }}</td>
                            <td>{{ $training->conducted_by ?? '' }}</td>
                            <td>{{ $training->inclusive_from ? date('d/m/Y', strtotime($training->inclusive_from)) : '' }}</td>
                            <td>{{ $training->inclusive_to ? date('d/m/Y', strtotime($training->inclusive_to)) : '' }}</td>
                            <td>{{ $training->number_of_hours ?? '' }}</td>
                            <td>{{ $training->type_of_ld ? ucwords(str_replace('_', ' ', $training->type_of_ld)) : '' }}</td>
                        </tr>
                    @empty
                        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div style="font-size: 8px; font-style: italic; margin-top: 4px;">(Continue on separate sheet if necessary)</div>
        </div>

        <!-- ==================== VIII. OTHER INFORMATION ==================== -->
        <div class="pds-section">
            <div class="pds-section-title">VIII. OTHER INFORMATION</div>

            <div class="pds-grid-3">
                <div>
                    <div style="font-weight:bold; font-size:9px; margin-bottom:3px;">31. SPECIAL SKILLS and HOBBIES</div>
                    <div class="pds-value" style="min-height:60px; display:block;">
                        @forelse($user->skills ?? [] as $skill)
                            {{ $skill->skill_hobby }}{{ !$loop->last ? ', ' : '' }}
                        @empty
                        @endforelse
                    </div>
                </div>
                <div>
                    <div style="font-weight:bold; font-size:9px; margin-bottom:3px;">32. NON-ACADEMIC DISTINCTIONS/RECOGNITION (Write in full)</div>
                    <div class="pds-value" style="min-height:60px; display:block;">
                        @forelse($user->distinctions ?? [] as $distinction)
                            {{ $distinction->distinctions }}{{ !$loop->last ? ', ' : '' }}
                        @empty
                        @endforelse
                    </div>
                </div>
                <div>
                    <div style="font-weight:bold; font-size:9px; margin-bottom:3px;">33. MEMBERSHIP IN ASSOCIATION/ORGANIZATION (Write in full)</div>
                    <div class="pds-value" style="min-height:60px; display:block;">
                        @forelse($user->organizations ?? [] as $org)
                            {{ $org->organization }}{{ !$loop->last ? ', ' : '' }}
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>
            <div style="font-size: 8px; font-style: italic; margin-top: 4px;">(Continue on separate sheet if necessary)</div>
        </div>

        <!-- Page 3 signature -->
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
            <div style="text-align:right; font-size:7px; margin-top:4px;">CS FORM 212 (Revised 2026), Page 3 of 4</div>
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