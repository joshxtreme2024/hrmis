<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Personal Data Sheet (PDS)</title>
    @include('pds._styles')
</head>
<body>
@php
    $p = $user->personalData; $bi = $user->backgroundInfo;
    $date = fn ($v) => $v ? date('d/m/Y', strtotime($v)) : '';
    // YES / NO tick boxes for one response
    $yn = fn ($v) => '<span class="pds-check ' . (strtolower((string) $v) === 'yes' ? 'checked' : '') . '"></span> YES &nbsp;&nbsp; '
                   . '<span class="pds-check ' . (strtolower((string) $v) === 'no' ? 'checked' : '') . '"></span> NO';
@endphp
    <div class="pds-container">

        {{-- Items 34-40: questions on the left, responses on the right (CSC guide) --}}
        <div class="pds-section">
            <table class="pds-table pds-qtable" style="margin-top:0;">

                {{-- 34 --}}
                <tr><td class="q" colspan="2"><strong>34.</strong> Are you related by consanguinity or affinity to the appointing or recommending authority, or to the chief of bureau or office or to the person who has immediate supervision over you in the Office, Bureau or Department where you will be appointed,</td></tr>
                <tr>
                    <td class="q sub">a. within the third degree?</td>
                    <td class="r">{!! $yn($bi->q34_a ?? '') !!}</td>
                </tr>
                <tr>
                    <td class="q sub">b. within the fourth degree (for Local Government Unit - Career Employees)?</td>
                    <td class="r">{!! $yn($bi->q34_b ?? '') !!}<div class="det">If YES, give details:<span class="fill">{{ $bi->q34_details ?? '' }}</span></div></td>
                </tr>

                {{-- 35 --}}
                <tr>
                    <td class="q"><strong>35.</strong> a. Have you ever been found guilty of any administrative offense?</td>
                    <td class="r">{!! $yn($bi->q35_a ?? '') !!}<div class="det">If YES, give details:<span class="fill">{{ $bi->q35_a_details ?? '' }}</span></div></td>
                </tr>
                <tr>
                    <td class="q sub">b. Have you been criminally charged before any court?</td>
                    <td class="r">{!! $yn($bi->q35_b ?? '') !!}
                        <div class="det">If YES, give details:<span class="fill">{{ $bi->q35_b_details ?? '' }}</span></div>
                        <div class="det">Date Filed (dd/mm/yyyy):<span class="fill">{{ $date($bi->q35_b_date_filed ?? null) }}</span></div>
                        <div class="det">Status of Case/s:<span class="fill">{{ $bi->q35_b_case_status ?? '' }}</span></div>
                    </td>
                </tr>

                {{-- 36 --}}
                <tr>
                    <td class="q"><strong>36.</strong> Have you ever been convicted of any crime or violation of any law, decree, ordinance or regulation by any court or tribunal?</td>
                    <td class="r">{!! $yn($bi->q36 ?? '') !!}<div class="det">If YES, give details:<span class="fill">{{ $bi->q36_details ?? '' }}</span></div></td>
                </tr>

                {{-- 37 --}}
                <tr>
                    <td class="q"><strong>37.</strong> Have you ever been separated from the service in any of the following modes: resignation, retirement, dropped from the rolls, dismissal, termination, end of term, finished contract or phased out (abolition) in the public or private sector?</td>
                    <td class="r">{!! $yn($bi->q37 ?? '') !!}<div class="det">If YES, give details:<span class="fill">{{ $bi->q37_details ?? '' }}</span></div></td>
                </tr>

                {{-- 38 --}}
                <tr>
                    <td class="q"><strong>38.</strong> a. Have you ever been a candidate in a national or local election held within the last year (except Barangay election)?</td>
                    <td class="r">{!! $yn($bi->q38_a ?? '') !!}<div class="det">If YES, give details:<span class="fill">{{ $bi->q38_a_details ?? '' }}</span></div></td>
                </tr>
                <tr>
                    <td class="q sub">b. Have you resigned from the government service during the three (3)-month period before the last election to promote/actively campaign for a national or local candidate?</td>
                    <td class="r">{!! $yn($bi->q38_b ?? '') !!}<div class="det">If YES, give details:<span class="fill">{{ $bi->q38_b_details ?? '' }}</span></div></td>
                </tr>

                {{-- 39 --}}
                <tr>
                    <td class="q"><strong>39.</strong> Have you acquired the status of an immigrant or permanent resident of another country?</td>
                    <td class="r">{!! $yn($bi->q39 ?? '') !!}<div class="det">If YES, give details (country):<span class="fill">{{ $bi->q39_details ?? '' }}</span></div></td>
                </tr>

                {{-- 40 --}}
                <tr><td class="q" colspan="2"><strong>40.</strong> Pursuant to: (a) Indigenous People's Act (RA 8371); (b) Magna Carta for Disabled Persons (RA 7277, as amended); and (c) Expanded Solo Parents Welfare Act (RA 11861), please answer the following items:</td></tr>
                <tr>
                    <td class="q sub">a. Are you a member of any indigenous group?</td>
                    <td class="r">{!! $yn($bi->q40_a ?? '') !!}<div class="det">If YES, please specify:<span class="fill">{{ $bi->q40_a_details ?? '' }}</span></div></td>
                </tr>
                <tr>
                    <td class="q sub">b. Are you a person with disability?</td>
                    <td class="r">{!! $yn($bi->q40_b ?? '') !!}<div class="det">If YES, please specify ID No:<span class="fill">{{ $bi->q40_b_details ?? '' }}</span></div></td>
                </tr>
                <tr>
                    <td class="q sub">c. Are you a solo parent?</td>
                    <td class="r">{!! $yn($bi->q40_c ?? '') !!}<div class="det">If YES, please specify ID No:<span class="fill">{{ $bi->q40_c_details ?? '' }}</span></div></td>
                </tr>
            </table>
        </div>

        {{-- 41. References, with the photo box beside them --}}
        <div class="pds-section">
            <div class="pds-section-title" style="background:#e8e8e8; color:#000;">41. REFERENCES (Person not related by consanguinity or affinity to applicant/appointee)</div>

            @php $references = collect($user->references ?? []); @endphp
            <div style="display:flex; gap:10px; align-items:flex-start;">
                <table class="pds-table" style="flex:1; margin-top:0;">
                    <thead>
                        <tr>
                            <th style="width:34%;">NAME<br>(First Name, MI, Surname)</th>
                            <th style="width:38%;">OFFICE/RESIDENTIAL ADDRESS</th>
                            <th style="width:28%;">CONTACT NO. AND/OR EMAIL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($references as $r)
                            <tr><td>{{ $r->name ?? '' }}</td><td>{{ $r->address ?? '' }}</td><td>{{ $r->contact ?? '' }}</td></tr>
                        @empty
                            <tr><td>N/A</td><td>N/A</td><td>N/A</td></tr>
                        @endforelse
                        @for ($i = max($references->count(), 1); $i < 3; $i++)
                            <tr><td class="blank">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                        @endfor
                    </tbody>
                </table>
                <div class="pds-photo-box">PHOTO<br>Passport-sized unfiltered digital picture taken within the last 6 months, 4.5 cm x 3.5 cm</div>
            </div>
        </div>

        {{-- 42. Declaration, signature / thumbmark, government ID --}}
        <div class="pds-section">
            <div style="font-size:9px; margin-bottom:10px;">
                <strong>42.</strong> I declare under oath that I have personally accomplished this Personal Data Sheet which
                is a true, correct, and complete statement pursuant to the provisions of pertinent laws, rules, and
                regulations of the Republic of the Philippines. I authorize the agency head/authorized representative to
                verify/validate the contents stated herein. I agree that any misrepresentation made in this document and
                its attachments shall cause the filing of administrative/criminal case/s against me.
            </div>

            <table class="pds-table" style="margin-top:0;">
                <tr>
                    <td style="width:33%; vertical-align:top;">
                        <div style="font-weight:bold; font-size:8px;">Government Issued ID (i.e. Passport, GSIS, SSS, PRC, Driver's License, etc.)</div>
                        <div style="font-style:italic; font-size:7px; margin-bottom:4px;">PLEASE INDICATE ID Number and Date of Issuance</div>
                        <div style="margin-top:6px;">Government Issued ID: {{ $p->gov_id_type ?? '' }}</div>
                        <div style="margin-top:10px;">ID/License/Passport No.: {{ $p->gov_id_number ?? '' }}</div>
                        <div style="margin-top:10px;">Date/Place of Issuance: {{ $date($p->gov_id_issue_date ?? null) }}@if (! empty($p->gov_id_issue_place)) / {{ $p->gov_id_issue_place }}@endif</div>
                    </td>
                    <td style="width:34%; vertical-align:top; text-align:center;">
                        <div class="pds-sign-box" style="min-height:50px;">{{ $p->signature ?? '(wet signature/e-signature/digital certificate)' }}</div>
                        <div style="border-top:1px solid #000; margin-top:4px; padding-top:2px; font-weight:bold;">Signature (Sign inside the box)</div>
                        <div style="margin-top:14px;">{{ $date($p->signature_date ?? null) }}</div>
                        <div style="border-top:1px solid #000; padding-top:2px; font-weight:bold;">Date Accomplished (dd/mm/yyyy)</div>
                    </td>
                    <td style="width:33%; vertical-align:top; text-align:center;">
                        <div class="pds-thumbmark-box" style="margin:0 auto;">Right Thumbmark<br>(for those unable to sign)</div>
                    </td>
                </tr>
            </table>

            <div style="font-size:9px; margin-top:10px;">
                SUBSCRIBED AND SWORN to before me this
                <span style="display:inline-block; border-bottom:1px solid #000; width:220px;">&nbsp;</span>,
                affiant exhibiting his/her validly issued government ID as indicated above.
            </div>

            {{-- Person Administering Oath: centered on the page --}}
            <div style="width:50%; margin:15px auto 0; text-align:center;">
                <div class="pds-sign-box" style="min-height:40px;">(wet signature/e-signature/digital certificate except for notary public)</div>
                <div style="border-top:1px solid #000; margin-top:4px; padding-top:2px; font-weight:bold;">Person Administering Oath</div>
            </div>

            <div style="text-align:right; font-size:7px; margin-top:10px;">CS FORM 212 (Revised 2026), Page 4 of 4</div>
        </div>
    </div>

    @include('pds._actions')
</body>
</html>