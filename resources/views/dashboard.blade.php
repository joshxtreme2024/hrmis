@extends('layouts.app')

@section('title', 'Dashboard')

@php
    use Illuminate\Support\Facades\Route;

    $u     = auth()->user();
    $first = $u?->personalData?->first_name ?? $u?->name ?? 'there';
    $url   = fn ($name, $params = []) => Route::has($name) ? route($name, $params) : null;

    $card  = 'rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800';
    $title = 'text-base font-semibold text-gray-900 dark:text-white';
    $help  = 'text-sm text-gray-600 dark:text-gray-400';
    $pill  = 'rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-200';
    $btnGhost = 'inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700';

    $o = $overview ?? [];
    $a = $attendance ?? [];
    $p = $plantilla ?? [];
    $l = $leaveMonitoring ?? [];

    /* Headline numbers: label, value, icon, value color, icon chip, link */
    $kpis = [
        ['Total employees', $o['total_employees'] ?? 0, 'bi-people', 'text-blue-700 dark:text-blue-300', 'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-300', $url('employees.index')],
        ['Active', $o['active_employees'] ?? 0, 'bi-person-check', 'text-emerald-700 dark:text-emerald-400', 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300', $url('employees.index', ['status' => 'active'])],
        ['Present today', $o['present_today'] ?? 0, 'bi-door-open', 'text-emerald-700 dark:text-emerald-400', 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300', null],
        ['On leave', $o['on_leave_today'] ?? 0, 'bi-calendar-check', 'text-violet-700 dark:text-violet-300', 'bg-violet-50 text-violet-600 dark:bg-violet-900/30 dark:text-violet-300', $url('employees.index', ['status' => 'on_leave'])],
        ['Absent', $o['absent_today'] ?? 0, 'bi-person-x', 'text-red-700 dark:text-red-400', 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-300', null],
        ['Late', $o['late_today'] ?? 0, 'bi-clock', 'text-amber-700 dark:text-amber-400', 'bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-300', null],
        ['Pending actions', $o['pending_actions'] ?? 0, 'bi-bell', ($o['pending_actions'] ?? 0) > 0 ? 'text-amber-700 dark:text-amber-400' : 'text-gray-900 dark:text-white', 'bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-300', $url('leave-applications.index')],
    ];

    /* Attendance: the same colors are used by the chart and by the tiles */
    $attItems = [
        ['present', 'Present', '#10b981', 'bg-emerald-500'],
        ['late', 'Late', '#f59e0b', 'bg-amber-500'],
        ['on_official_business', 'Official business', '#3b82f6', 'bg-blue-500'],
        ['absent', 'Absent', '#ef4444', 'bg-red-500'],
        ['on_leave', 'On leave', '#8b5cf6', 'bg-violet-500'],
        ['no_record', 'No record', '#9ca3af', 'bg-gray-400'],
    ];
    $chart = [
        'labels' => array_column($attItems, 1),
        'values' => array_map(fn ($i) => (int) ($a[$i[0]] ?? 0), $attItems),
        'colors' => array_column($attItems, 2),
    ];

    $alertStyles = [
        'danger'  => ['border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-900/20', 'text-red-500', 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300'],
        'warning' => ['border-amber-200 bg-amber-50 dark:border-amber-800 dark:bg-amber-900/20', 'text-amber-500', 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300'],
        'info'    => ['border-blue-200 bg-blue-50 dark:border-blue-800 dark:bg-blue-900/20', 'text-blue-500', 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300'],
    ];
    $moveStyles = [
        'appointment' => ['bi-person-plus', 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300'],
        'promotion'   => ['bi-arrow-up-circle', 'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-300'],
        'separation'  => ['bi-person-x', 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-300'],
    ];

    $deptRows  = $distribution['by_department'] ?? [];
    $deptTotal = array_sum($deptRows);
    $totalPos  = (int) ($p['total_positions'] ?? 0);
    $filled    = (int) ($p['filled'] ?? 0);
    $vacant    = (int) ($p['vacant'] ?? 0);
    $filledPct = $totalPos > 0 ? min(100, round($filled / $totalPos * 100)) : 0;
@endphp

@section('header', 'Dashboard')
@section('subheader', 'Welcome back, ' . $first . '. Here is what is happening today.')

@section('header-actions')
    <span class="text-sm text-gray-600 dark:text-gray-400"><i class="bi bi-calendar3 mr-1" aria-hidden="true"></i>{{ now()->format('l, F d, Y') }}</span>
    <button type="button" onclick="location.reload()" class="{{ $btnGhost }}"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
@endsection

@section('content')
<div class="space-y-6">

    {{-- ===== Quick actions ===== --}}
    @if(!empty($quickActions))
        <section aria-label="Quick actions">
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-7">
                @foreach($quickActions as $action)
                    <a href="{{ $action['route'] }}" class="group flex items-center gap-3 rounded-xl border border-gray-200 bg-white p-3 shadow-sm hover:border-blue-300 hover:bg-blue-50/50 dark:border-gray-700 dark:bg-gray-800 dark:hover:border-blue-700 dark:hover:bg-blue-900/20">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white dark:bg-blue-900/30 dark:text-blue-300"><i class="bi {{ $action['icon'] }}" aria-hidden="true"></i></span>
                        <span class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $action['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ===== Headline numbers ===== --}}
    <section class="grid grid-cols-2 gap-4 md:grid-cols-4 xl:grid-cols-7" aria-label="Key numbers">
        @foreach($kpis as [$name, $value, $icon, $color, $chip, $href])
            @php $tag = $href ? 'a' : 'div'; @endphp
            <{{ $tag }} @if($href) href="{{ $href }}" @endif class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 {{ $href ? 'hover:border-blue-300 dark:hover:border-blue-700' : '' }}">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $name }}</p>
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $chip }}"><i class="bi {{ $icon }}" aria-hidden="true"></i></span>
                </div>
                <p class="mt-2 text-3xl font-semibold tabular-nums {{ $color }}">{{ number_format($value) }}</p>
            </{{ $tag }}>
        @endforeach
    </section>

    {{-- ===== Attendance + HR alerts ===== --}}
    <div class="grid gap-6 lg:grid-cols-3">
        <section class="{{ $card }} lg:col-span-2" aria-labelledby="att-title">
            <div class="mb-5 flex flex-wrap items-start justify-between gap-2">
                <div><h2 id="att-title" class="{{ $title }}">Today's attendance</h2><p class="{{ $help }}">{{ now()->format('F d, Y') }}</p></div>
                <span class="{{ $pill }}">Attendance rate <strong class="text-blue-700 dark:text-blue-300">{{ $a['attendance_rate'] ?? 0 }}%</strong></span>
            </div>
            <div class="grid items-center gap-6 sm:grid-cols-2">
                <div class="mx-auto h-48 w-48">
                    <canvas id="attendanceChart" role="img" aria-label="Attendance today: {{ collect($attItems)->map(fn ($i) => $i[1] . ' ' . (int) ($a[$i[0]] ?? 0))->implode(', ') }}"></canvas>
                </div>
                <ul class="grid grid-cols-2 gap-3">
                    @foreach($attItems as [$key, $label, $hex, $swatch])
                        <li class="rounded-lg bg-gray-50 p-3 dark:bg-gray-900/30">
                            <p class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400"><span class="h-2.5 w-2.5 rounded-full {{ $swatch }}" aria-hidden="true"></span>{{ $label }}</p>
                            <p class="mt-1 text-xl font-semibold tabular-nums text-gray-900 dark:text-white">{{ number_format($a[$key] ?? 0) }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

        <section class="{{ $card }}" aria-labelledby="alert-title">
            <div class="mb-4 flex items-start justify-between gap-2">
                <div><h2 id="alert-title" class="{{ $title }}">HR alerts</h2><p class="{{ $help }}">Needs your attention</p></div>
                <span class="{{ count($alerts ?? []) ? 'rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700 dark:bg-red-900/30 dark:text-red-300' : $pill }}">{{ count($alerts ?? []) }} {{ \Illuminate\Support\Str::plural('alert', count($alerts ?? [])) }}</span>
            </div>
            <ul class="max-h-72 space-y-2 overflow-y-auto pr-1" tabindex="0" aria-label="HR alerts list">
                @forelse($alerts ?? [] as $alert)
                    @php $s = $alertStyles[$alert['type']] ?? $alertStyles['info']; @endphp
                    <li class="flex items-start gap-3 rounded-lg border p-3 {{ $s[0] }}">
                        <i class="bi {{ $alert['icon'] }} mt-0.5 text-lg {{ $s[1] }}" aria-hidden="true"></i>
                        <p class="flex-1 text-sm text-gray-800 dark:text-gray-200">{{ $alert['message'] }}</p>
                        <span class="inline-flex h-6 min-w-6 items-center justify-center rounded-full px-1.5 text-xs font-semibold {{ $s[2] }}">{{ $alert['count'] }}</span>
                    </li>
                @empty
                    <li class="py-8 text-center"><i class="bi bi-check-circle-fill text-3xl text-emerald-500" aria-hidden="true"></i><p class="mt-2 text-sm text-gray-600 dark:text-gray-400">All clear. Nothing needs attention.</p></li>
                @endforelse
            </ul>
        </section>
    </div>

    {{-- ===== Leave, offices, plantilla ===== --}}
    <div class="grid gap-6 lg:grid-cols-3">
        <section class="{{ $card }}" aria-labelledby="leave-title">
            <div class="mb-4 flex items-start justify-between gap-2">
                <div><h2 id="leave-title" class="{{ $title }}">Leave monitoring</h2><p class="{{ $help }}">Requests overview</p></div>
                <span class="{{ $pill }}">On leave today: {{ $l['on_leave_today'] ?? 0 }}</span>
            </div>
            <ul class="mb-5 grid grid-cols-3 gap-3">
                @foreach([['Pending', 'pending', 'text-amber-700 dark:text-amber-400'], ['Approved', 'approved', 'text-emerald-700 dark:text-emerald-400'], ['Rejected', 'rejected', 'text-red-700 dark:text-red-400']] as [$lb, $k, $c])
                    <li class="rounded-lg bg-gray-50 p-3 text-center dark:bg-gray-900/30">
                        <p class="text-xl font-semibold tabular-nums {{ $c }}">{{ number_format($l[$k] ?? 0) }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $lb }}</p>
                    </li>
                @endforeach
            </ul>
            <h3 class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Upcoming leaves</h3>
            <ul class="space-y-1">
                @forelse(($l['upcoming'] ?? []) as $leave)
                    <li class="flex items-center justify-between rounded-lg px-2 py-1.5 text-sm hover:bg-gray-50 dark:hover:bg-gray-700/40">
                        <span class="text-gray-800 dark:text-gray-200">{{ $leave['employee'] }}</span>
                        <span class="text-xs text-gray-600 dark:text-gray-400">{{ $leave['date'] }}</span>
                    </li>
                @empty
                    <li class="py-2 text-center text-sm text-gray-600 dark:text-gray-400">No upcoming leaves</li>
                @endforelse
            </ul>
            @if($url('leave-applications.index'))<a href="{{ $url('leave-applications.index') }}" class="mt-4 inline-block text-sm font-medium text-blue-700 hover:underline dark:text-blue-300">Review leave requests</a>@endif
        </section>

        <section class="{{ $card }}" aria-labelledby="office-title">
            <div class="mb-4 flex items-start justify-between gap-2">
                <div><h2 id="office-title" class="{{ $title }}">Employees by office</h2><p class="{{ $help }}">Department distribution</p></div>
                <span class="{{ $pill }}">Top 5</span>
            </div>
            <ul class="space-y-4">
                @forelse($deptRows as $name => $count)
                    @php $pct = $deptTotal > 0 ? round($count / $deptTotal * 100) : 0; @endphp
                    <li>
                        <div class="flex items-center justify-between gap-3 text-sm">
                            <span class="truncate text-gray-800 dark:text-gray-200">{{ $name }}</span>
                            <span class="shrink-0 tabular-nums text-gray-600 dark:text-gray-400"><strong class="text-gray-900 dark:text-white">{{ $count }}</strong> · {{ $pct }}%</span>
                        </div>
                        <div class="mt-1.5 h-2 w-full rounded-full bg-gray-100 dark:bg-gray-700"><div class="h-2 rounded-full bg-blue-600" style="width: {{ $pct }}%"></div></div>
                    </li>
                @empty
                    <li class="py-6 text-center text-sm text-gray-600 dark:text-gray-400">No department data yet</li>
                @endforelse
            </ul>
        </section>

        <section class="{{ $card }}" aria-labelledby="plantilla-title">
            <div class="mb-4 flex items-start justify-between gap-2">
                <div><h2 id="plantilla-title" class="{{ $title }}">Plantilla status</h2><p class="{{ $help }}">Position status overview</p></div>
                <span class="{{ $pill }}">LGU</span>
            </div>
            <p class="text-3xl font-semibold tabular-nums text-gray-900 dark:text-white">{{ $filledPct }}% <span class="text-base font-normal text-gray-600 dark:text-gray-400">filled</span></p>
            <div class="mt-3 flex h-2.5 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700" role="img" aria-label="{{ $filled }} of {{ $totalPos }} positions filled">
                <div class="h-2.5 bg-blue-600" style="width: {{ $filledPct }}%"></div>
            </div>
            <dl class="mt-5 space-y-3 text-sm">
                @foreach([['Authorized positions', $totalPos], ['Filled', $filled], ['Vacant', $vacant], ['Permanent', $p['permanent'] ?? 0], ['Casual / JO / Contractual', $p['casual_jo'] ?? 0]] as [$lb, $v])
                    <div class="flex items-center justify-between border-b border-gray-100 pb-2 last:border-0 dark:border-gray-700">
                        <dt class="text-gray-600 dark:text-gray-400">{{ $lb }}</dt><dd class="font-semibold tabular-nums text-gray-900 dark:text-white">{{ number_format($v) }}</dd>
                    </div>
                @endforeach
            </dl>
        </section>
    </div>

    {{-- ===== Events + personnel actions ===== --}}
    <div class="grid gap-6 lg:grid-cols-2">
        <section class="{{ $card }}" aria-labelledby="events-title">
            <div class="mb-4 flex items-start justify-between gap-2">
                <div><h2 id="events-title" class="{{ $title }}">Upcoming events</h2><p class="{{ $help }}">Birthdays, anniversaries and more</p></div>
                <span class="{{ $pill }}">Next 30 days</span>
            </div>
            <ul class="max-h-72 space-y-1 overflow-y-auto pr-1" tabindex="0" aria-label="Upcoming events list">
                @forelse($upcomingEvents ?? [] as $event)
                    <li class="flex items-center gap-3 rounded-lg p-2 hover:bg-gray-50 dark:hover:bg-gray-700/40">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-xl dark:bg-blue-900/30" aria-hidden="true">{{ $event['icon'] }}</span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-gray-900 dark:text-white">{{ $event['title'] }}</p>
                            <p class="truncate text-xs text-gray-600 dark:text-gray-400">{{ $event['details'] }}</p>
                        </div>
                        <span class="shrink-0 rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">{{ $event['date'] }}</span>
                    </li>
                @empty
                    <li class="py-8 text-center"><i class="bi bi-calendar text-3xl text-gray-300 dark:text-gray-600" aria-hidden="true"></i><p class="mt-2 text-sm text-gray-600 dark:text-gray-400">No upcoming events</p></li>
                @endforelse
            </ul>
        </section>

        <section class="{{ $card }}" aria-labelledby="moves-title">
            <div class="mb-4 flex items-start justify-between gap-2">
                <div><h2 id="moves-title" class="{{ $title }}">Recent personnel actions</h2><p class="{{ $help }}">Appointments, promotions and separations</p></div>
                <span class="{{ $pill }}">Last 30 days</span>
            </div>
            <ul class="max-h-72 space-y-1 overflow-y-auto pr-1" tabindex="0" aria-label="Recent personnel actions list">
                @forelse($personnelMovements ?? [] as $m)
                    @php $ms = $moveStyles[$m['type']] ?? ['bi-arrow-right', 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300']; @endphp
                    <li class="flex items-center gap-3 rounded-lg p-2 hover:bg-gray-50 dark:hover:bg-gray-700/40">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ $ms[1] }}"><i class="bi {{ $ms[0] }}" aria-hidden="true"></i></span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-gray-900 dark:text-white">{{ $m['employee'] }}</p>
                            <p class="truncate text-xs text-gray-600 dark:text-gray-400">{{ $m['action'] }}</p>
                        </div>
                        <span class="shrink-0 text-xs text-gray-600 dark:text-gray-400">{{ $m['date'] }}</span>
                    </li>
                @empty
                    <li class="py-8 text-center"><i class="bi bi-arrow-left-right text-3xl text-gray-300 dark:text-gray-600" aria-hidden="true"></i><p class="mt-2 text-sm text-gray-600 dark:text-gray-400">No recent personnel actions</p></li>
                @endforelse
            </ul>
        </section>
    </div>
</div>
@endsection

@push('scripts')
{{-- Pinned version; no jQuery needed --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" defer></script>
<script>
window.addEventListener('load', () => {
    const canvas = document.getElementById('attendanceChart');
    if (!canvas || typeof Chart === 'undefined') return;
    const d = @json($chart);
    const dark = document.documentElement.classList.contains('dark');
    const total = d.values.reduce((s, v) => s + v, 0);
    new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels: d.labels,
            datasets: [{
                data: total ? d.values : [1],                         // grey ring when there is no data yet
                backgroundColor: total ? d.colors : [dark ? '#374151' : '#e5e7eb'],
                borderWidth: 2, borderColor: dark ? '#1f2937' : '#ffffff',
            }],
        },
        options: {
            responsive: true, maintainAspectRatio: false, cutout: '68%',
            plugins: { legend: { display: false }, tooltip: { enabled: total > 0, cornerRadius: 8, padding: 10 } },
        },
    });
});
</script>
@endpush