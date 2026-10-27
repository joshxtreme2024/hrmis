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
    $pageNo = 3;
@endphp
    <div class="pds-container">

        {{-- ==================== VI. VOLUNTARY WORK ==================== --}}
        <div class="pds-section">
            <div class="pds-section-title">VI. VOLUNTARY WORK OR INVOLVEMENT IN CIVIC / NON-GOVERNMENT / PEOPLE / VOLUNTARY ORGANIZATION/S</div>

            @php $voluntary = collect($user->voluntaryWorks ?? []); @endphp
            <table class="pds-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width:34%;">29. NAME &amp; ADDRESS OF ORGANIZATION<br>(Write in full)</th>
                        <th colspan="2" style="width:24%;">INCLUSIVE DATES<br>(dd/mm/yyyy)</th>
                        <th rowspan="2" style="width:14%;">NUMBER OF HOURS</th>
                        <th rowspan="2" style="width:28%;">POSITION / NATURE OF WORK</th>
                    </tr>
                    <tr><th style="width:12%;">From</th><th style="width:12%;">To</th></tr>
                </thead>
                <tbody>
                    @forelse ($voluntary as $v)
                        <tr>
                            <td>{{ $na($v->organization_name ?? null) }}@if (! empty($v->organization_address))<br><small>{{ $v->organization_address }}</small>@endif</td>
                            <td>{{ $date($v->inclusive_from ?? null) }}</td>
                            <td>{{ $date($v->inclusive_to ?? null) }}</td>
                            <td>{{ $na($v->number_of_hours ?? null) }}</td>
                            <td>{{ $na($v->position_nature_of_work ?? null) }}</td>
                        </tr>
                    @empty
                        <tr><td>N/A</td><td>N/A</td><td>N/A</td><td>N/A</td><td>N/A</td></tr>
                    @endforelse
                    @for ($i = max($voluntary->count(), 1); $i < 5; $i++)
                        <tr><td class="blank">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                    @endfor
                </tbody>
            </table>
            <div style="font-size:8px; font-style:italic; margin-top:4px;">(Continue on separate sheet if necessary)</div>
        </div>

        {{-- ==================== VII. LEARNING AND DEVELOPMENT ==================== --}}
        <div class="pds-section">
            <div class="pds-section-title">VII. LEARNING AND DEVELOPMENT (L&amp;D) INTERVENTIONS/TRAINING PROGRAMS ATTENDED</div>

            @php $trainings = collect($user->trainings ?? []); @endphp
            <table class="pds-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width:30%;">30. TITLE OF LEARNING AND DEVELOPMENT INTERVENTIONS/TRAINING PROGRAMS<br>(Write in full)</th>
                        <th colspan="2" style="width:20%;">INCLUSIVE DATES OF ATTENDANCE<br>(dd/mm/yyyy)</th>
                        <th rowspan="2" style="width:9%;">NUMBER OF HOURS</th>
                        <th rowspan="2" style="width:15%;">Type of L&amp;D<br>(Managerial/ Supervisory/ Technical/etc)</th>
                        <th rowspan="2" style="width:26%;">CONDUCTED/SPONSORED BY<br>(Write in full)</th>
                    </tr>
                    <tr><th style="width:10%;">From</th><th style="width:10%;">To</th></tr>
                </thead>
                <tbody>
                    @forelse ($trainings as $t)
                        <tr>
                            <td>{{ $na($t->title_of_program ?? null) }}</td>
                            <td>{{ $date($t->inclusive_from ?? null) }}</td>
                            <td>{{ $date($t->inclusive_to ?? null) }}</td>
                            <td>{{ $na($t->number_of_hours ?? null) }}</td>
                            <td>{{ $t->type_of_ld ? ucwords(str_replace('_', ' ', $t->type_of_ld)) : 'N/A' }}</td>
                            <td>{{ $na($t->conducted_by ?? null) }}</td>
                        </tr>
                    @empty
                        <tr><td>N/A</td><td>N/A</td><td>N/A</td><td>N/A</td><td>N/A</td><td>N/A</td></tr>
                    @endforelse
                    @for ($i = max($trainings->count(), 1); $i < 7; $i++)
                        <tr><td class="blank">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                    @endfor
                </tbody>
            </table>
            <div style="font-size:8px; font-style:italic; margin-top:4px;">(Continue on separate sheet if necessary)</div>
        </div>

        {{-- ==================== VIII. OTHER INFORMATION ==================== --}}
        <div class="pds-section">
            <div class="pds-section-title">VIII. OTHER INFORMATION</div>

            @php
                $skills = collect($user->skills ?? [])->pluck('skill_hobby')->filter()->implode(', ');
                $distinctions = collect($user->distinctions ?? [])->pluck('distinctions')->filter()->implode(', ');
                $orgs = collect($user->organizations ?? [])->pluck('organization')->filter()->implode(', ');
            @endphp
            <div class="pds-grid-3">
                <div>
                    <div style="font-weight:bold; font-size:9px; margin-bottom:3px;">31. SPECIAL SKILLS and HOBBIES</div>
                    <div class="pds-value" style="min-height:60px; display:block;">{{ $na($skills) }}</div>
                </div>
                <div>
                    <div style="font-weight:bold; font-size:9px; margin-bottom:3px;">32. NON-ACADEMIC DISTINCTIONS/RECOGNITION<br>(Write in full)</div>
                    <div class="pds-value" style="min-height:60px; display:block;">{{ $na($distinctions) }}</div>
                </div>
                <div>
                    <div style="font-weight:bold; font-size:9px; margin-bottom:3px;">33. MEMBERSHIP IN ASSOCIATION/ORGANIZATION<br>(Write in full)</div>
                    <div class="pds-value" style="min-height:60px; display:block;">{{ $na($orgs) }}</div>
                </div>
            </div>
            <div style="font-size:8px; font-style:italic; margin-top:4px;">(Continue on separate sheet if necessary)</div>
        </div>

        @include('pds._page-footer')
    </div>

    @include('pds._actions')
</body>
</html>