@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')
@section('subheader', 'Welcome back, ' . (auth()->user()->personalData->first_name ?? 'Admin'))

@php
    $navFrom = '#0a2463';
    $navTo   = '#06173f';
    $navAccent = '#7dd3fc'; // sky-300

    $btnGhost = 'inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700';
    $card     = 'rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800';
    $cardHead = 'mb-5 flex items-center justify-between';
    $dot      = fn ($c) => '<span class="inline-block h-2 w-2 rounded-full ' . $c . '"></span>';
    $h3       = 'flex items-center gap-2 text-sm font-semibold text-gray-900 dark:text-white';
    $sub      = 'mt-0.5 text-xs text-gray-500 dark:text-gray-400';
@endphp

@section('header-actions')
    <span class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-600 shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
        <i class="bi bi-calendar3 text-gray-400" aria-hidden="true"></i>
        {{ now()->format('l, F d, Y') }}
    </span>
    <button type="button" onclick="window.location.reload()" class="{{ $btnGhost }}">
        <i class="bi bi-arrow-clockwise"></i> Refresh
    </button>
@endsection

@section('content')
<div class="space-y-6 p-6">

    {{-- ===== Hero strip: navy → deep-navy (matches nav) ===== --}}
    <section class="overflow-hidden rounded-xl text-blue-100 shadow-sm"
             style="background-image: linear-gradient(to bottom right, {{ $navFrom }}, {{ $navTo }});">
        <div class="flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/10 ring-1 ring-white/20">
                    <i class="bi bi-speedometer2 text-xl text-white" aria-hidden="true"></i>
                </span>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-blue-300/80">Welcome back</p>
                    <h2 class="text-xl font-semibold text-white">
                        {{ auth()->user()->personalData->first_name ?? 'Admin' }}
                    </h2>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2 text-xs">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1 text-blue-100 ring-1 ring-white/15">
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                    Online
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1 text-blue-100 ring-1 ring-white/15">
                    <i class="bi bi-person-check" aria-hidden="true"></i>
                    {{ number_format($overview['present_today'] ?? 0) }} present today
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1 text-blue-100 ring-1 ring-white/15">
                    <i class="bi bi-calendar-check" aria-hidden="true"></i>
                    {{ number_format($overview['on_leave_today'] ?? 0) }} on leave
                </span>
            </div>
        </div>
    </section>

    {{-- ===== KPI strip ===== --}}
    @php
        $kpis = [
            ['Total employees',     number_format($overview['total_employees'] ?? 0),   'bi-people',            'text-blue-600 dark:text-blue-400',     ($overview['growth'] ?? 0) . '% from last month'],
            ['Active',              number_format($overview['active_employees'] ?? 0),  'bi-person-check',      'text-emerald-600 dark:text-emerald-400', ($overview['active_percentage'] ?? 0) . '% of total'],
            ['Present today',       number_format($overview['present_today'] ?? 0),     'bi-person-check-fill', 'text-sky-600 dark:text-sky-400',        'Rate: ' . ($attendance['attendance_rate'] ?? 0) . '%'],
            ['On leave',            number_format($overview['on_leave_today'] ?? 0),    'bi-clock-history',     'text-amber-600 dark:text-amber-400',    ($overview['leave_percentage'] ?? 0) . '% of total'],
            ['Absent',              number_format($overview['absent_today'] ?? 0),      'bi-person-x',          'text-rose-600 dark:text-rose-400',      'Today\'s absence'],
            ['Late',                number_format($overview['late_today'] ?? 0),        'bi-clock',             'text-orange-600 dark:text-orange-400',  'Today\'s late arrivals'],
            ['Pending',             number_format($overview['pending_actions'] ?? 0),   'bi-bell-fill',         'text-red-600 dark:text-red-400',        'Requires attention'],
        ];
        $kpiChip = [
            'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-300',
            'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300',
            'bg-sky-50 text-sky-600 dark:bg-sky-900/30 dark:text-sky-300',
            'bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-300',
            'bg-rose-50 text-rose-600 dark:bg-rose-900/30 dark:text-rose-300',
            'bg-orange-50 text-orange-600 dark:bg-orange-900/30 dark:text-orange-300',
            'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-300',
        ];
    @endphp
    <section class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-7" aria-label="Key metrics">
        @foreach ($kpis as $i => [$name, $value, $icon, $valueColor, $hint])
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ $name }}</p>
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ $kpiChip[$i] }}">
                        <i class="bi {{ $icon }}" aria-hidden="true"></i>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-semibold tabular-nums {{ $valueColor }}">{{ $value }}</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $hint }}</p>
            </div>
        @endforeach
    </section>

    {{-- ===== Attendance + Distribution + Plantilla ===== --}}
    <section class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Today's attendance --}}
        <div class="{{ $card }} p-6">
            <div class="{{ $cardHead }}">
                <div>
                    <h3 class="{{ $h3 }}">{!! $dot('bg-sky-500') !!} Today's attendance</h3>
                    <p class="{{ $sub }}">{{ now()->format('F d, Y') }}</p>
                </div>
                <span class="rounded-full bg-sky-50 px-3 py-1 text-xs font-medium text-sky-700 dark:bg-sky-900/30 dark:text-sky-300">
                    Rate: {{ $attendance['attendance_rate'] ?? 0 }}%
                </span>
            </div>

            <div class="mb-5 grid grid-cols-3 gap-2">
                @foreach ([
                    ['Present',        number_format($attendance['present'] ?? 0),             'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300'],
                    ['Late',           number_format($attendance['late'] ?? 0),                'bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-300'],
                    ['Official Biz',   number_format($attendance['on_official_business'] ?? 0),'bg-sky-50 text-sky-700 dark:bg-sky-900/20 dark:text-sky-300'],
                    ['Absent',         number_format($attendance['absent'] ?? 0),              'bg-rose-50 text-rose-700 dark:bg-rose-900/20 dark:text-rose-300'],
                    ['On Leave',       number_format($attendance['on_leave'] ?? 0),            'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200'],
                    ['No Record',      number_format($attendance['no_record'] ?? 0),           'bg-slate-50 text-slate-600 dark:bg-slate-700/40 dark:text-slate-300'],
                ] as [$label, $val, $cls])
                    <div class="rounded-lg p-2.5 text-center {{ $cls }}">
                        <p class="text-lg font-semibold tabular-nums">{{ $val }}</p>
                        <p class="text-[10px] font-medium">{{ $label }}</p>
                    </div>
                @endforeach
            </div>

            <div class="flex h-32 items-center justify-center">
                <canvas id="attendanceChart" aria-label="Attendance breakdown chart" role="img"></canvas>
            </div>
        </div>

        {{-- Employees by office --}}
        <div class="{{ $card }} p-6">
            <div class="{{ $cardHead }}">
                <div>
                    <h3 class="{{ $h3 }}">{!! $dot('bg-indigo-500') !!} Employees by office</h3>
                    <p class="{{ $sub }}">Department distribution</p>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-700 dark:text-slate-300">Top 5</span>
            </div>
            @php $deptTotal = array_sum($distribution['by_department'] ?? []); @endphp
            <div class="space-y-3.5">
                @forelse (($distribution['by_department'] ?? []) as $name => $count)
                    @php $pct = $deptTotal > 0 ? round(($count / $deptTotal) * 100) : 0; @endphp
                    <div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="truncate pr-2 font-medium text-gray-700 dark:text-gray-300">{{ $name }}</span>
                            <span class="font-semibold tabular-nums text-gray-900 dark:text-white">{{ $count }}</span>
                        </div>
                        <div class="mt-1.5 h-2 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700">
                            <div class="h-2 rounded-full bg-indigo-600" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-center">
                        <i class="bi bi-building mb-2 block text-3xl text-gray-300 dark:text-gray-600" aria-hidden="true"></i>
                        <p class="text-sm text-gray-500 dark:text-gray-400">No department data</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Plantilla --}}
        <div class="{{ $card }} p-6">
            <div class="{{ $cardHead }}">
                <div>
                    <h3 class="{{ $h3 }}">{!! $dot('bg-violet-500') !!} Plantilla status</h3>
                    <p class="{{ $sub }}">Position status overview</p>
                </div>
                <span class="rounded-full bg-violet-50 px-3 py-1 text-xs font-medium text-violet-700 dark:bg-violet-900/30 dark:text-violet-300">LGU</span>
            </div>
            @php
                $total    = (int) ($plantilla['total_positions'] ?? 0);
                $filled   = (int) ($plantilla['filled'] ?? 0);
                $vacant   = (int) ($plantilla['vacant'] ?? 0);
                $fillPct  = $total > 0 ? round(($filled / $total) * 100) : 0;
                $vacPct   = $total > 0 ? round(($vacant / $total) * 100) : 0;
            @endphp
            <div class="space-y-3.5">
                <div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-medium text-gray-700 dark:text-gray-300">Authorized positions</span>
                        <span class="font-semibold tabular-nums text-gray-900 dark:text-white">{{ $total }}</span>
                    </div>
                    <div class="mt-1.5 h-2 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700">
                        <div class="h-2 rounded-full bg-sky-600" style="width: 100%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-medium text-gray-700 dark:text-gray-300">Filled</span>
                        <span class="font-semibold tabular-nums text-gray-900 dark:text-white">{{ $filled }}</span>
                    </div>
                    <div class="mt-1.5 h-2 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700">
                        <div class="h-2 rounded-full bg-emerald-500" style="width: {{ $fillPct }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-medium text-gray-700 dark:text-gray-300">Vacant</span>
                        <span class="font-semibold tabular-nums text-gray-900 dark:text-white">{{ $vacant }}</span>
                    </div>
                    <div class="mt-1.5 h-2 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700">
                        <div class="h-2 rounded-full bg-rose-500" style="width: {{ $vacPct }}%"></div>
                    </div>
                </div>
                <div class="flex items-center justify-between border-t border-gray-100 pt-2 text-sm dark:border-gray-700">
                    <span class="font-medium text-gray-700 dark:text-gray-300">Permanent</span>
                    <span class="font-semibold tabular-nums text-gray-900 dark:text-white">{{ $plantilla['permanent'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="font-medium text-gray-700 dark:text-gray-300">Casual / JO / Contractual</span>
                    <span class="font-semibold tabular-nums text-gray-900 dark:text-white">{{ $plantilla['casual_jo'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== Leave monitoring + HR alerts ===== --}}
    <section class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        <div class="{{ $card }} p-6">
            <div class="{{ $cardHead }}">
                <div>
                    <h3 class="{{ $h3 }}">{!! $dot('bg-amber-500') !!} Leave monitoring</h3>
                    <p class="{{ $sub }}">Overview</p>
                </div>
                <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-medium text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">
                    Today: {{ $leaveMonitoring['on_leave_today'] ?? 0 }}
                </span>
            </div>
            <div class="mb-5 grid grid-cols-3 gap-2">
                @foreach ([
                    ['Pending',  number_format($leaveMonitoring['pending'] ?? 0),  'bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-300'],
                    ['Approved', number_format($leaveMonitoring['approved'] ?? 0), 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300'],
                    ['Rejected', number_format($leaveMonitoring['rejected'] ?? 0), 'bg-rose-50 text-rose-700 dark:bg-rose-900/20 dark:text-rose-300'],
                ] as [$label, $val, $cls])
                    <div class="rounded-lg p-2.5 text-center {{ $cls }}">
                        <p class="text-lg font-semibold tabular-nums">{{ $val }}</p>
                        <p class="text-[10px] font-medium">{{ $label }}</p>
                    </div>
                @endforeach
            </div>
            <p class="mb-2 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Upcoming leaves</p>
            <div class="space-y-2">
                @forelse (($leaveMonitoring['upcoming'] ?? []) as $leave)
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 p-2.5 text-sm dark:bg-gray-700/40">
                        <span class="font-medium text-gray-700 dark:text-gray-200">{{ $leave['employee'] }}</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $leave['date'] }}</span>
                    </div>
                @empty
                    <p class="py-3 text-center text-sm text-gray-500 dark:text-gray-400">No upcoming leaves</p>
                @endforelse
            </div>
        </div>

        <div class="{{ $card }} p-6 lg:col-span-2">
            <div class="{{ $cardHead }}">
                <div>
                    <h3 class="{{ $h3 }}">{!! $dot('bg-rose-500') !!} HR alerts</h3>
                    <p class="{{ $sub }}">Actions requiring your attention</p>
                </div>
                <span class="rounded-full bg-rose-50 px-3 py-1 text-xs font-medium text-rose-700 dark:bg-rose-900/30 dark:text-rose-300">
                    {{ count($alerts ?? []) }} alerts
                </span>
            </div>
            <div class="custom-scrollbar max-h-64 space-y-2.5 overflow-y-auto pr-1">
                @forelse ($alerts ?? [] as $alert)
                    @php
                        $variant = match ($alert['type']) {
                            'danger'  => ['bg-rose-50/80 border-rose-200/60 dark:bg-rose-900/20 dark:border-rose-800', 'bg-rose-100 text-rose-600 dark:bg-rose-900/40 dark:text-rose-300', 'bg-rose-200 text-rose-700 dark:bg-rose-900/50 dark:text-rose-200'],
                            'warning' => ['bg-amber-50/80 border-amber-200/60 dark:bg-amber-900/20 dark:border-amber-800', 'bg-amber-100 text-amber-600 dark:bg-amber-900/40 dark:text-amber-300', 'bg-amber-200 text-amber-700 dark:bg-amber-900/50 dark:text-amber-200'],
                            default   => ['bg-sky-50/80 border-sky-200/60 dark:bg-sky-900/20 dark:border-sky-800', 'bg-sky-100 text-sky-600 dark:bg-sky-900/40 dark:text-sky-300', 'bg-sky-200 text-sky-700 dark:bg-sky-900/50 dark:text-sky-200'],
                        };
                    @endphp
                    <div class="flex items-start gap-3 rounded-lg border p-3 {{ $variant[0] }}">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $variant[1] }}">
                            <i class="bi {{ $alert['icon'] }}" aria-hidden="true"></i>
                        </span>
                        <p class="flex-1 text-sm text-gray-700 dark:text-gray-200">{{ $alert['message'] }}</p>
                        <span class="shrink-0 rounded-full px-2 py-0.5 text-xs font-bold tabular-nums {{ $variant[2] }}">{{ $alert['count'] }}</span>
                    </div>
                @empty
                    <div class="py-8 text-center">
                        <span class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-50 dark:bg-emerald-900/30">
                            <i class="bi bi-check-circle-fill text-2xl text-emerald-500" aria-hidden="true"></i>
                        </span>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-200">All clear</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">No pending alerts</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ===== Events + Personnel ===== --}}
    <section class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        <div class="{{ $card }} p-6">
            <div class="{{ $cardHead }}">
                <div>
                    <h3 class="{{ $h3 }}">{!! $dot('bg-purple-500') !!} Upcoming events</h3>
                    <p class="{{ $sub }}">Birthdays, anniversaries, etc.</p>
                </div>
                <span class="rounded-full bg-purple-50 px-3 py-1 text-xs font-medium text-purple-700 dark:bg-purple-900/30 dark:text-purple-300">Next 30 days</span>
            </div>
            <div class="custom-scrollbar max-h-64 space-y-2.5 overflow-y-auto pr-1">
                @forelse ($upcomingEvents ?? [] as $event)
                    <div class="flex items-center gap-3 rounded-lg border border-gray-100 p-2.5 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700/40">
                        <div class="shrink-0 text-2xl">{{ $event['icon'] }}</div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $event['title'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $event['details'] }}</p>
                        </div>
                        <span class="shrink-0 rounded-full bg-sky-50 px-2.5 py-1 text-xs font-semibold text-sky-700 dark:bg-sky-900/30 dark:text-sky-300">{{ $event['date'] }}</span>
                    </div>
                @empty
                    <div class="py-6 text-center">
                        <i class="bi bi-calendar mb-2 block text-3xl text-gray-300 dark:text-gray-600" aria-hidden="true"></i>
                        <p class="text-sm text-gray-500 dark:text-gray-400">No upcoming events</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="{{ $card }} p-6">
            <div class="{{ $cardHead }}">
                <div>
                    <h3 class="{{ $h3 }}">{!! $dot('bg-teal-500') !!} Recent personnel actions</h3>
                    <p class="{{ $sub }}">Last 30 days</p>
                </div>
                <a href="#" class="text-xs font-medium text-sky-700 hover:underline dark:text-sky-300">View all</a>
            </div>
            <div class="custom-scrollbar max-h-64 space-y-2.5 overflow-y-auto pr-1">
                @forelse ($personnelMovements ?? [] as $movement)
                    @php
                        $variant = match ($movement['type']) {
                            'appointment' => ['bg-emerald-500', 'bi-person-plus'],
                            'promotion'   => ['bg-sky-500',     'bi-arrow-up'],
                            'separation'  => ['bg-rose-500',    'bi-person-x'],
                            default       => ['bg-slate-500',   'bi-arrow-right'],
                        };
                    @endphp
                    <div class="flex items-center gap-3 rounded-lg border border-gray-100 p-2.5 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700/40">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-xs font-bold text-white {{ $variant[0] }}">
                            <i class="bi {{ $variant[1] }}" aria-hidden="true"></i>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $movement['employee'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $movement['action'] }}</p>
                        </div>
                        <span class="shrink-0 text-xs text-gray-500 dark:text-gray-400">{{ $movement['date'] }}</span>
                    </div>
                @empty
                    <div class="py-6 text-center">
                        <i class="bi bi-arrow-left-right mb-2 block text-3xl text-gray-300 dark:text-gray-600" aria-hidden="true"></i>
                        <p class="text-sm text-gray-500 dark:text-gray-400">No recent personnel actions</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ===== Quick actions ===== --}}
    <section class="{{ $card }} p-6">
        <div class="{{ $cardHead }}">
            <div>
                <h3 class="{{ $h3 }}">{!! $dot('bg-gray-400') !!} Quick actions</h3>
                <p class="{{ $sub }}">Common HR tasks</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-7">
            @foreach ($quickActions ?? [] as $action)
                <a href="{{ $action['route'] }}"
                   class="group flex flex-col items-center justify-center rounded-lg border border-gray-200 bg-gray-50/80 p-4 transition-colors hover:bg-white dark:border-gray-700 dark:bg-gray-700/30 dark:hover:bg-gray-700/60">
                    <span class="mb-2 flex h-11 w-11 items-center justify-center rounded-lg bg-white text-sky-600 shadow-sm transition-colors group-hover:bg-sky-600 group-hover:text-white dark:bg-gray-800 dark:text-sky-300 dark:group-hover:bg-sky-500">
                        <i class="bi {{ $action['icon'] }} text-lg" aria-hidden="true"></i>
                    </span>
                    <span class="text-center text-xs font-medium text-gray-700 dark:text-gray-200">{{ $action['label'] }}</span>
                </a>
            @endforeach
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #475569; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('attendanceChart');
    if (!el || !window.Chart) return;

    new Chart(el.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Present', 'Late', 'Absent', 'On leave'],
            datasets: [{
                data: [
                    {{ $attendance['present'] ?? 0 }},
                    {{ $attendance['late'] ?? 0 }},
                    {{ $attendance['absent'] ?? 0 }},
                    {{ $attendance['on_leave'] ?? 0 }}
                ],
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#7dd3fc'],
                borderWidth: 2,
                borderColor: '#ffffff',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '72%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'white',
                    titleColor: '#0f172a',
                    bodyColor: '#475569',
                    borderColor: '#e2e8f0',
                    borderWidth: 1,
                    cornerRadius: 8,
                    padding: 10,
                    displayColors: true,
                    boxPadding: 4,
                    usePointStyle: true,
                }
            }
        }
    });
});
</script>
@endpush