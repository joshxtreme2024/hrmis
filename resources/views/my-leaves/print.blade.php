<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>CS Form No. 6 - Application for Leave - {{ implode(' ', array_filter($name)) }}</title>
@php
    $fmt  = fn ($n) => is_null($n) ? '' : rtrim(rtrim(number_format($n, 3), '0'), '.');
    $code = $a->leaveType->code;
    $box  = fn ($on) => '<span class="box">' . ($on ? '✓' : '') . '</span>';

    $types = [
        ['VL',    'Vacation Leave',                      '(Sec. 51, Rule XVI, Omnibus Rules Implementing E.O. No. 292)'],
        ['MFL',   'Mandatory/Forced Leave',              '(Sec. 25, Rule XVI, Omnibus Rules Implementing E.O. No. 292)'],
        ['SL',    'Sick Leave',                          '(Sec. 43, Rule XVI, Omnibus Rules Implementing E.O. No. 292)'],
        ['ML',    'Maternity Leave',                     '(R.A. No. 11210 / IRR issued by CSC, DOLE and SSS)'],
        ['PL',    'Paternity Leave',                     '(R.A. No. 8187 / CSC MC No. 71, s. 1998, as amended)'],
        ['SPL',   'Special Privilege Leave',             '(Sec. 21, Rule XVI, Omnibus Rules Implementing E.O. No. 292)'],
        ['SOLO',  'Solo Parent Leave',                   '(RA No. 8972 / CSC MC No. 8, s. 2004)'],
        ['STUDY', 'Study Leave',                         '(Sec. 68, Rule XVI, Omnibus Rules Implementing E.O. No. 292)'],
        ['VAWC',  '10-Day VAWC Leave',                   '(RA No. 9262 / CSC MC No. 15, s. 2005)'],
        ['REHAB', 'Rehabilitation Privilege',            '(Sec. 55, Rule XVI, Omnibus Rules Implementing E.O. No. 292)'],
        ['SLBW',  'Special Leave Benefits for Women',    '(RA No. 9710 / CSC MC No. 25, s. 2010)'],
        ['SEL',   'Special Emergency (Calamity) Leave',  '(CSC MC No. 2, s. 2012, as amended)'],
        ['ADOPT', 'Adoption Leave',                      '(R.A. No. 8552)'],
        ['WL', 'Wellness Leave',                         '(CSC Memorandum Circular No. 01, s. 2026)'],
    ];

    $dates = $a->date_from->eq($a->date_to)
        ? $a->date_from->format('F d, Y')
        : $a->date_from->format('F d, Y') . ' to ' . $a->date_to->format('F d, Y');

    $hrOfficer   = setting('leave_form_hr_officer', 'KENELYN C. DULNUAN');
    $recommender = setting('leave_form_recommender', 'CLEMENTE S. TALUSIG');
    $mayor       = setting('leave_form_mayor', 'CLEMENTE S. TALUSIG');
@endphp
<style>
    @page { size: A4 portrait; margin: 8mm; }
    * { box-sizing: border-box; }
    body { margin: 0; background: #e5e7eb; color: #000; font-family: Arial, Helvetica, sans-serif; font-size: 8.5pt; line-height: 1.25; }
    .toolbar { position: sticky; top: 0; z-index: 5; display: flex; gap: 8px; justify-content: center; align-items: center; padding: 10px; background: #1f2937; color: #fff; font-size: 14px; }
    .toolbar button, .toolbar a { padding: 6px 14px; border-radius: 6px; border: 1px solid rgba(255,255,255,.3); background: #4f46e5; color: #fff; text-decoration: none; cursor: pointer; font-size: 14px; }
    .toolbar a { background: transparent; }
    .sheet { width: 194mm; margin: 14px auto; padding: 6mm; background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,.2); }
    table { border-collapse: collapse; width: 100%; }
    td { border: 1px solid #000; padding: 3px 6px; vertical-align: top; }
    td.nb { border: none; }
    .head td { border: none; padding: 0; }
    .title { text-align: center; font-weight: bold; font-size: 13pt; letter-spacing: .5px; padding: 4px; border: 1px solid #000; border-bottom: none; }
    .sec { font-weight: bold; text-align: center; background: #f1f1f1; }
    .cap { font-size: 6.5pt; text-align: center; }
    .fill { display: inline-block; min-width: 40px; padding: 0 4px; border-bottom: 1px solid #000; font-weight: bold; }
    .val { font-weight: bold; text-transform: none; }
    .box { display: inline-block; width: 11px; height: 11px; margin-right: 5px; border: 1px solid #000; text-align: center; font-size: 10px; line-height: 10px; vertical-align: -1px; font-weight: bold; }
    .law { font-size: 6.5pt; }
    .row { margin: 1.5px 0; }
    .ind { margin-left: 16px; }
    .sig { margin-top: 18px; text-align: center; }
    .sig .line { border-top: 1px solid #000; margin: 0 14px; padding-top: 1px; font-size: 7pt; }
    .sig .nm { font-weight: bold; text-transform: uppercase; }
    .mini td { text-align: center; padding: 2px 4px; }
    .mini td:first-child { text-align: left; }
    .foot { margin-top: 5px; font-size: 6.5pt; color: #444; display: flex; justify-content: space-between; }
    @media print {
        body { background: #fff; }
        .toolbar { display: none; }
        .sheet { margin: 0; padding: 0; width: auto; box-shadow: none; }
    }
</style>
</head>
<body>

<div class="toolbar">
    <span>Print on A4 paper, sign it, and submit it to HR.</span>
    <button type="button" onclick="window.print()">Print</button>
    <button onclick="window.close()">Close</button>
</div>

<div class="sheet">
    <table class="head">
        <tr>
            <td style="width:25%; font-size:7pt; font-style:italic; vertical-align:top;">Civil Service Form No. 6<br>Revised 2020</td>
            <td style="text-align:center;">
                @if (setting('logo'))<img src="{{ asset('storage/' . setting('logo')) }}" alt="" style="height:46px; vertical-align:middle; margin-right:8px;">@endif
                <span style="display:inline-block; vertical-align:middle; text-align:center; line-height:1.3;">
                    Republic of the Philippines<br><strong>Local Government Unit of Aguinaldo</strong><br>Galonogon, Aguinaldo, Ifugao
                </span>
            </td>
            <td style="width:25%;"></td>
        </tr>
    </table>

    <div class="title" style="margin-top:6px;">APPLICATION FOR LEAVE</div>

    <table>
        <tr>
            <td style="width:38%;">1. OFFICE/DEPARTMENT<br><span class="val">{{ $a->department?->name }}</span></td>
            <td>
                2. NAME :
                <table style="margin-top:2px;"><tr>
                    <td class="nb" style="width:34%; padding:0;"><span class="val">{{ $name[0] }}</span><div class="cap">(Last)</div></td>
                    <td class="nb" style="width:34%; padding:0;"><span class="val">{{ $name[1] }}</span><div class="cap">(First)</div></td>
                    <td class="nb" style="padding:0;"><span class="val">{{ $name[2] }}</span><div class="cap">(Middle)</div></td>
                </tr></table>
            </td>
        </tr>
        <tr>
            <td>3. DATE OF FILING &nbsp;<span class="val">{{ $a->date_of_filing->format('F d, Y') }}</span></td>
            <td>4. POSITION &nbsp;<span class="val">{{ $a->position }}</span> &nbsp;&nbsp;&nbsp; 5. SALARY &nbsp;<span class="val">{{ $a->salary ? '₱ ' . number_format($a->salary, 2) : '' }}</span></td>
        </tr>
        <tr><td colspan="2" class="sec">6. DETAILS OF APPLICATION</td></tr>
        <tr>
            <td colspan="1" style="width:50%;">
                <strong>6.A TYPE OF LEAVE TO BE AVAILED OF</strong>
                <div style="margin-top:3px;">
                    @foreach ($types as [$c, $label, $law])
                        <div class="row">{!! $box($code === $c) !!}{{ $label }} <span class="law">{{ $law }}</span></div>
                    @endforeach
                    <div class="row">{!! $box($code === 'OTHER') !!}Others:</div>
                    <div class="ind"><span class="fill" style="min-width:200px;">{{ $code === 'OTHER' ? $a->leave_type_other : '' }}</span></div>
                </div>
            </td>
            <td>
                <strong>6.B DETAILS OF LEAVE</strong>
                <div style="margin-top:3px;">
                    <div class="row"><em>In case of Vacation/Special Privilege Leave:</em></div>
                    <div class="row ind">{!! $box($a->vacation_location === 'within_philippines') !!}Within the Philippines <span class="fill" style="min-width:110px;">{{ $a->vacation_location === 'within_philippines' ? $a->vacation_location_specify : '' }}</span></div>
                    <div class="row ind">{!! $box($a->vacation_location === 'abroad') !!}Abroad (Specify) <span class="fill" style="min-width:110px;">{{ $a->vacation_location === 'abroad' ? $a->vacation_location_specify : '' }}</span></div>

                    <div class="row" style="margin-top:6px;"><em>In case of Sick Leave:</em></div>
                    <div class="row ind">{!! $box($a->sick_setting === 'in_hospital') !!}In Hospital (Specify Illness) <span class="fill" style="min-width:80px;">{{ $a->sick_setting === 'in_hospital' ? $a->illness_specify : '' }}</span></div>
                    <div class="row ind">{!! $box($a->sick_setting === 'out_patient') !!}Out Patient (Specify Illness) <span class="fill" style="min-width:80px;">{{ $a->sick_setting === 'out_patient' ? $a->illness_specify : '' }}</span></div>

                    <div class="row" style="margin-top:6px;"><em>In case of Special Leave Benefits for Women:</em></div>
                    <div class="row ind">(Specify Illness) <span class="fill" style="min-width:140px;">{{ $code === 'SLBW' ? $a->illness_specify : '' }}</span></div>

                    <div class="row" style="margin-top:6px;"><em>In case of Study Leave:</em></div>
                    <div class="row ind">{!! $box($a->study_purpose === 'masters_degree') !!}Completion of Master's Degree</div>
                    <div class="row ind">{!! $box($a->study_purpose === 'bar_board_review') !!}BAR/Board Examination Review</div>

                    <div class="row" style="margin-top:6px;"><em>Other purpose:</em></div>
                    <div class="row ind">{!! $box($a->other_purpose === 'monetization') !!}Monetization of Leave Credits</div>
                    <div class="row ind">{!! $box($a->other_purpose === 'terminal_leave') !!}Terminal Leave</div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <strong>6.C NUMBER OF WORKING DAYS APPLIED FOR</strong><br>
                <span class="fill" style="min-width:220px; margin:4px 0 6px;">{{ $fmt($a->working_days) }}</span><br>
                <strong>INCLUSIVE DATES</strong><br>
                <span class="fill" style="min-width:220px; margin-top:4px;">{{ $dates }}</span>
            </td>
            <td>
                <strong>6.D COMMUTATION</strong>
                <div class="row ind" style="margin-top:3px;">{!! $box(! $a->commutation_requested) !!}Not Requested</div>
                <div class="row ind">{!! $box($a->commutation_requested) !!}Requested</div>
                <div class="sig" style="margin-top:22px;"><div class="line">(Signature of Applicant)</div></div>
            </td>
        </tr>

        <tr><td colspan="2" class="sec">7. DETAILS OF ACTION ON APPLICATION</td></tr>
        <tr>
            <td>
                <strong>7.A CERTIFICATION OF LEAVE CREDITS</strong>
                <div style="margin:4px 0;">As of <span class="fill" style="min-width:130px;">{{ $a->credits_as_of?->format('F d, Y') }}</span></div>
                <table class="mini">
                    <tr><td style="width:40%;"></td><td><strong>Vacation Leave</strong></td><td><strong>Sick Leave</strong></td></tr>
                    <tr><td>Total Earned</td><td>{{ $fmt($a->vl_total_earned) }}</td><td>{{ $fmt($a->sl_total_earned) }}</td></tr>
                    <tr><td>Less this application</td><td>{{ $fmt($a->vl_less_this_application) }}</td><td>{{ $fmt($a->sl_less_this_application) }}</td></tr>
                    <tr><td>Balance</td><td><strong>{{ $fmt($a->vl_balance) }}</strong></td><td><strong>{{ $fmt($a->sl_balance) }}</strong></td></tr>
                </table>
                <div class="sig"><div class="nm">{{ $hrOfficer }}</div><div class="line">(Authorized Officer)</div></div>
            </td>
            <td>
                <strong>7.B RECOMMENDATION</strong>
                <div class="row ind" style="margin-top:4px;">{!! $box($a->recommendation === 'for_approval') !!}For approval</div>
                <div class="row ind">{!! $box($a->recommendation === 'for_disapproval') !!}For disapproval due to</div>
                <div class="ind"><span class="fill" style="min-width:210px;">{{ $a->recommendation === 'for_disapproval' ? $a->recommendation_disapproval_reason : '' }}</span></div>
                <div class="ind" style="margin-top:6px;"><span class="fill" style="min-width:210px;">&nbsp;</span></div>
                <div class="sig"><div class="nm">{{ $recommender }}</div><div class="line">(Authorized Officer)</div></div>
            </td>
        </tr>
        <tr>
            <td>
                <strong>7.C APPROVED FOR:</strong>
                <div class="row ind" style="margin-top:4px;"><span class="fill" style="min-width:45px; text-align:center;">{{ $fmt($a->approved_days_with_pay) }}</span> days with pay</div>
                <div class="row ind"><span class="fill" style="min-width:45px; text-align:center;">{{ $fmt($a->approved_days_without_pay) }}</span> days without pay</div>
                <div class="row ind"><span class="fill" style="min-width:45px; text-align:center;">{{ $fmt($a->approved_days_others) }}</span> others (Specify) <span class="fill" style="min-width:60px;">{{ $a->approved_others_specify }}</span></div>
            </td>
            <td>
                <strong>7.D DISAPPROVED DUE TO:</strong>
                <div class="ind" style="margin-top:8px;"><span class="fill" style="min-width:210px;">&nbsp;</span></div>
                <div class="ind" style="margin-top:6px;"><span class="fill" style="min-width:210px;">&nbsp;</span></div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="sig" style="margin-top:26px;"><div class="nm">{{ $mayor }}</div><div style="font-size:8pt;">Municipal Mayor</div></div>
            </td>
        </tr>
    </table>

    <div class="foot">
        <span>Application No. {{ str_pad($a->id, 6, '0', STR_PAD_LEFT) }} · Approved {{ $a->decided_at?->format('M d, Y') }}</span>
        <span>Printed from HRMIS on {{ now()->format('M d, Y h:i A') }}</span>
    </div>
</div>

@if (request('print'))
<script>window.addEventListener('load', function () { setTimeout(function () { window.print(); }, 300); });</script>
@endif
</body>
</html>