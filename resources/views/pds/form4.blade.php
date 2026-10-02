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
        <!-- ==================== PAGE 4 ==================== -->
        <div class="pds-section">
            @php $p = $user->personalData; $govId = $user->governmentID; @endphp
            @php $bi = $user->backgroundInfo; @endphp

            <!-- 34 -->
            <div class="pds-q">
                <strong>34.</strong> Are you related by consanguinity or affinity to the appointing or recommending authority,
                or to the chief of bureau or office or to the person who has immediate supervision over you in the Office,
                Bureau or Department where you will be appointed,
                <div style="margin-left:14px; margin-top:3px;">
                    a. within the third degree?
                    <span class="pds-check {{ ($bi->q34_a ?? '') === 'yes' ? 'checked' : '' }}"></span> YES
                    <span class="pds-check {{ ($bi->q34_a ?? '') === 'no' ? 'checked' : '' }}"></span> NO
                </div>
                <div style="margin-left:14px; margin-top:2px;">
                    b. within the fourth degree (for Local Government Unit - Career Employees)?
                    <span class="pds-check {{ ($bi->q34_b ?? '') === 'yes' ? 'checked' : '' }}"></span> YES
                    <span class="pds-check {{ ($bi->q34_b ?? '') === 'no' ? 'checked' : '' }}"></span> NO
                </div>
                <div style="margin-left:14px; margin-top:2px;">If YES, give details: {{ $bi->q34_details ?? '' }}</div>
            </div>

            <!-- 35 -->
            <div class="pds-q">
                <div>
                    <strong>35.</strong> a. Have you ever been found guilty of any administrative offense?
                    <span class="pds-check {{ ($bi->q35_a ?? '') === 'yes' ? 'checked' : '' }}"></span> YES
                    <span class="pds-check {{ ($bi->q35_a ?? '') === 'no' ? 'checked' : '' }}"></span> NO
                    <div style="margin-left:14px; margin-top:2px;">If YES, give details: {{ $bi->q35_a_details ?? '' }}</div>
                </div>
                <div style="margin-top:4px;">
                    b. Have you been criminally charged before any court?
                    <span class="pds-check {{ ($bi->q35_b ?? '') === 'yes' ? 'checked' : '' }}"></span> YES
                    <span class="pds-check {{ ($bi->q35_b ?? '') === 'no' ? 'checked' : '' }}"></span> NO
                    <div style="margin-left:14px; margin-top:2px;">If YES, give details: {{ $bi->q35_b_details ?? '' }}</div>
                    <div style="margin-left:14px; margin-top:2px;">Date Filed: {{ $bi->q35_b_date_filed ? date('d/m/Y', strtotime($bi->q35_b_date_filed)) : '' }}</div>
                    <div style="margin-left:14px; margin-top:2px;">Status of Case/s: {{ $bi->q35_b_case_status ?? '' }}</div>
                </div>
            </div>

            <!-- 36 -->
            <div class="pds-q">
                <strong>36.</strong> Have you ever been convicted of any crime or violation of any law, decree, ordinance or regulation by any court or tribunal?
                <span class="pds-check {{ ($bi->q36 ?? '') === 'yes' ? 'checked' : '' }}"></span> YES
                <span class="pds-check {{ ($bi->q36 ?? '') === 'no' ? 'checked' : '' }}"></span> NO
                <div style="margin-left:14px; margin-top:2px;">If YES, give details: {{ $bi->q36_details ?? '' }}</div>
            </div>

            <!-- 37 -->
            <div class="pds-q">
                <strong>37.</strong> Have you ever been separated from the service in any of the following modes: resignation,
                retirement, dropped from the rolls, dismissal, termination, end of term, finished contract or phased out
                (abolition) in the public or private sector?
                <span class="pds-check {{ ($bi->q37 ?? '') === 'yes' ? 'checked' : '' }}"></span> YES
                <span class="pds-check {{ ($bi->q37 ?? '') === 'no' ? 'checked' : '' }}"></span> NO
                <div style="margin-left:14px; margin-top:2px;">If YES, give details: {{ $bi->q37_details ?? '' }}</div>
            </div>

            <!-- 38 -->
            <div class="pds-q">
                <div>
                    <strong>38.</strong> a. Have you ever been a candidate in a national or local election held within the last year (except Barangay election)?
                    <span class="pds-check {{ ($bi->q38_a ?? '') === 'yes' ? 'checked' : '' }}"></span> YES
                    <span class="pds-check {{ ($bi->q38_a ?? '') === 'no' ? 'checked' : '' }}"></span> NO
                    <div style="margin-left:14px; margin-top:2px;">If YES, give details: {{ $bi->q38_a_details ?? '' }}</div>
                </div>
                <div style="margin-top:4px;">
                    b. Have you resigned from the government service during the three (3)-month period before the last election to promote/actively campaign for a national or local candidate?
                    <span class="pds-check {{ ($bi->q38_b ?? '') === 'yes' ? 'checked' : '' }}"></span> YES
                    <span class="pds-check {{ ($bi->q38_b ?? '') === 'no' ? 'checked' : '' }}"></span> NO
                    <div style="margin-left:14px; margin-top:2px;">If YES, give details: {{ $bi->q38_b_details ?? '' }}</div>
                </div>
            </div>

            <!-- 39 -->
            <div class="pds-q">
                <strong>39.</strong> Have you acquired the status of an immigrant or permanent resident of another country?
                <span class="pds-check {{ ($bi->q39 ?? '') === 'yes' ? 'checked' : '' }}"></span> YES
                <span class="pds-check {{ ($bi->q39 ?? '') === 'no' ? 'checked' : '' }}"></span> NO
                <div style="margin-left:14px; margin-top:2px;">If YES, give details (country): {{ $bi->q39_details ?? '' }}</div>
            </div>

            <!-- 40 -->
            <div class="pds-q">
                <strong>40.</strong> Pursuant to: (a) Indigenous People's Act (RA 8371); (b) Magna Carta for Disabled Persons
                (RA 7277, as amended); and (c) Expanded Solo Parents Welfare Act (RA 11861), please answer the following items:
                <div style="margin-left:14px; margin-top:3px;">
                    a. Are you a member of any indigenous group?
                    <span class="pds-check {{ ($bi->q40_a ?? '') === 'yes' ? 'checked' : '' }}"></span> YES
                    <span class="pds-check {{ ($bi->q40_a ?? '') === 'no' ? 'checked' : '' }}"></span> NO
                    <div style="margin-left:14px; margin-top:2px;">If YES, please specify: {{ $bi->q40_a_details ?? '' }}</div>
                </div>
                <div style="margin-left:14px; margin-top:4px;">
                    b. Are you a person with disability?
                    <span class="pds-check {{ ($bi->q40_b ?? '') === 'yes' ? 'checked' : '' }}"></span> YES
                    <span class="pds-check {{ ($bi->q40_b ?? '') === 'no' ? 'checked' : '' }}"></span> NO
                    <div style="margin-left:14px; margin-top:2px;">If YES, please specify ID No: {{ $bi->q40_b_details ?? '' }}</div>
                </div>
                <div style="margin-left:14px; margin-top:4px;">
                    c. Are you a solo parent?
                    <span class="pds-check {{ ($bi->q40_c ?? '') === 'yes' ? 'checked' : '' }}"></span> YES
                    <span class="pds-check {{ ($bi->q40_c ?? '') === 'no' ? 'checked' : '' }}"></span> NO
                    <div style="margin-left:14px; margin-top:2px;">If YES, please specify ID No: {{ $bi->q40_c_details ?? '' }}</div>
                </div>
            </div>
        </div>

        <!-- ==================== 41. REFERENCES ==================== -->
        <div class="pds-section">
            <div class="pds-section-title" style="background:#e8e8e8; color:#000;">41. REFERENCES (Person not related by consanguinity or affinity to applicant/appointee)</div>

            <table class="pds-table">
                <thead>
                    <tr>
                        <th style="width:35%;">NAME</th>
                        <th style="width:40%;">OFFICE/RESIDENTIAL ADDRESS</th>
                        <th style="width:25%;">CONTACT NO. AND/OR EMAIL</th>
                    </tr>
                </thead>
                <tbody>
                    @php $references = $user->references ?? []; @endphp
                    @forelse($references as $reference)
                        <tr>
                            <td>{{ $reference->name ?? '' }}</td>
                            <td>{{ $reference->address ?? '' }}</td>
                            <td>{{ $reference->contact ?? '' }}</td>
                        </tr>
                    @empty
                        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                        <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- ==================== 42. CERTIFICATION / SIGNATURE / ID / PHOTO ==================== -->
        <div class="pds-section">
            <div style="font-size: 9px; margin-bottom: 10px;">
                <strong>42.</strong> I declare under oath that I have personally accomplished this Personal Data Sheet which
                is a true, correct, and complete statement pursuant to the provisions of pertinent laws, rules, and
                regulations of the Republic of the Philippines. I authorize the agency head/authorized representative to
                verify/validate the contents stated herein. I agree that any misrepresentation made in this document and
                its attachments shall cause the filing of administrative/criminal case/s against me.
            </div>

            <table class="pds-table" style="border:none;">
                <tr style="border:none;">
                    <td style="border:1px solid #000; width:33%; vertical-align:top;">
                        <div style="font-weight:bold; font-size:8px;">Government Issued ID (i.e. Passport, GSIS, SSS, PRC, Driver's License, etc.)</div>
                        <div style="font-style:italic; font-size:7px; margin-bottom:4px;">PLEASE INDICATE ID Number and Date of Issuance</div>
                        <div style="margin-top:6px;">Government Issued ID: {{ $p->gov_id_type ?? '' }}</div>
                        <div style="margin-top:10px;">ID/License/Passport No.: {{ $p->gov_id_number ?? '' }}</div>
                        <div style="margin-top:10px;">
                            Date/Place of Issuance:
                            {{ $p->gov_id_issue_date ? date('d/m/Y', strtotime($p->gov_id_issue_date)) : '' }}
                            @if(!empty($p->gov_id_issue_place)) / {{ $p->gov_id_issue_place }} @endif
                        </div>
                    </td>
                    <td style="border:1px solid #000; width:34%; vertical-align:top; text-align:center;">
                        <div class="pds-sign-box" style="min-height:50px;">{{ $p->signature ?? '(wet signature/e-signature/digital certificate)' }}</div>
                        <div style="border-top:1px solid #000; margin-top:4px; padding-top:2px; font-weight:bold;">Signature (Sign inside the box)</div>
                        <div style="border-top:1px solid #000; margin-top:20px; padding-top:2px;">
                            {{ $p->signature_date ? date('d/m/Y', strtotime($p->signature_date)) : '' }}
                        </div>
                        <div style="font-weight:bold;">Date Accomplished</div>
                    </td>
                    <td style="border:1px solid #000; width:33%; vertical-align:top; text-align:center;">
                        <div class="pds-photo-box" style="margin:0 auto;">
                            PHOTO<br>Passport-sized unfiltered digital picture taken within the last 6 months, 4.5cm x 3.5cm
                        </div>
                        <div class="pds-thumbmark-box" style="margin:8px auto 0;">Right Thumbmark</div>
                    </td>
                </tr>
            </table>

            <div style="font-size: 9px; margin-top: 10px;">
                SUBSCRIBED AND SWORN to before me this
                <span style="display:inline-block; border-bottom:1px solid #000; width:220px;">&nbsp;</span>,
                affiant exhibiting his/her validly issued government ID as indicated above.
            </div>

            <table class="pds-table" style="border:none; margin-top:15px;">
                <tr style="border:none;">
                    <td style="border:1px solid #000; width:50%; text-align:center; vertical-align:top;">
                        <div class="pds-sign-box" style="min-height:40px;">(wet signature/e-signature/digital certificate except for notary public)</div>
                        <div style="border-top:1px solid #000; margin-top:4px; padding-top:2px; font-weight:bold;">Person Administering Oath</div>
                    </td>
                    <td style="border:none; width:50%;"></td>
                </tr>
            </table>

            <div style="text-align:right; font-size:7px; margin-top:10px;">CS FORM 212 (Revised 2026), Page 4 of 4</div>
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