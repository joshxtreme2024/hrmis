{{-- resources/views/employee/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')
@section('subheader', 'Welcome back, ' . ($personalInfo['name'] ?? 'Employee'))

@php
    $navFrom = '#0a2463';
    $navTo   = '#06173f';

    $btnGhost = 'inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700';
    $card     = 'rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800';
    $cardHead = 'mb-5 flex items-center justify-between';
    $dot      = fn ($c) => '<span class="inline-block h-2 w-2 rounded-full ' . $c . '"></span>';
    $h3       = 'flex items-center gap-2 text-sm font-semibold text-gray-900 dark:text-white';
    $sub      = 'mt-0.5 text-xs text-gray-500 dark:text-gray-400';

    $statusVariant = function ($status) {
        return match ($status) {
            'Present', 'Active'  => ['text-emerald-600 dark:text-emerald-400', 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300', 'bg-emerald-500'],
            'Absent'             => ['text-rose-600 dark:text-rose-400',       'bg-rose-50 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300',             'bg-rose-500'],
            'On Leave'           => ['text-amber-600 dark:text-amber-400',     'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',         'bg-amber-500'],
            default              => ['text-gray-600 dark:text-gray-300',       'bg-gray-50 text-gray-700 dark:bg-gray-700/40 dark:text-gray-300',             'bg-gray-400'],
        };
    };

    $todayStatus   = $attendanceSummary['today']['status'] ?? '—';
    [$todayText, $todayChip, $todayDot] = $statusVariant($todayStatus);
@endphp

@section('header-actions')
    <span class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-600 shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
        <i class="bi bi-calendar3 text-gray-400" aria-hidden="true"></i>
        {{ now()->format('l, F d, Y') }}
    </span>
    <a href="{{ route('employee.profile.edit') }}" class="{{ $btnGhost }}">
        <i class="bi bi-pencil-square"></i> Edit profile
    </a>
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
                <div class="relative shrink-0">
                    @if (!empty($personalInfo['profile_picture']))
                        <img src="{{ asset('storage/' . $personalInfo['profile_picture']) }}"
                             alt="{{ $personalInfo['name'] }}"
                             class="h-16 w-16 rounded-full object-cover ring-4 ring-white/20">
                    @else
                        <span class="flex h-16 w-16 items-center justify-center rounded-full bg-white/10 text-xl font-semibold text-white ring-4 ring-white/20">
                            {{ $personalInfo['initials'] ?? '?' }}
                        </span>
                    @endif
                    <span class="absolute bottom-0 right-0 h-4 w-4 rounded-full border-2 border-[#0a2463] bg-emerald-400" aria-hidden="true"></span>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium uppercase tracking-wider text-blue-300/80">Welcome back</p>
                    <h2 class="text-xl font-semibold text-white">{{ $personalInfo['name'] }}</h2>
                    <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-blue-200">
                        <span class="inline-flex items-center gap-1.5">
                            <i class="bi bi-building" aria-hidden="true"></i>{{ $personalInfo['department'] }}
                        </span>
                        <span aria-hidden="true">·</span>
                        <span class="inline-flex items-center gap-1.5">
                            <i class="bi bi-briefcase" aria-hidden="true"></i>{{ $personalInfo['position'] }}
                        </span>
                        <span aria-hidden="true">·</span>
                        <span class="inline-flex items-center gap-1.5">
                            <i class="bi bi-person-badge" aria-hidden="true"></i>ID: {{ $personalInfo['employee_id'] }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2 text-xs">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1 text-blue-100 ring-1 ring-white/15">
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                    Online
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1 text-blue-100 ring-1 ring-white/15">
                    <i class="bi bi-calendar-check" aria-hidden="true"></i>
                    Hired: {{ $personalInfo['hire_date'] }}
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1 text-blue-100 ring-1 ring-white/15">
                    <span class="inline-block h-1.5 w-1.5 rounded-full {{ $todayDot }}"></span>
                    {{ $todayStatus }}
                </span>
            </div>
        </div>
    </section>

    {{-- ===== Quick stats ===== --}}
    @php
        $stats = [
            [
                'label' => 'Today\'s status',
                'value' => $todayStatus,
                'hint'  => 'Check-in: ' . ($attendanceSummary['today']['check_in'] ?? '—'),
                'icon'  => $todayStatus === 'Present' ? 'bi-check-circle-fill' : ($todayStatus === 'Absent' ? 'bi-x-circle-fill' : 'bi-clock'),
                'valueColor' => $todayText,
                'chip'  => $todayChip,
            ],
            [
                'label' => 'Attendance rate',
                'value' => ($attendanceSummary['monthly']['attendance_rate'] ?? 0) . '%',
                'hint'  => 'This month',
                'icon'  => 'bi-percent',
                'valueColor' => 'text-blue-600 dark:text-blue-400',
                'chip'  => 'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-300',
            ],
            [
                'label' => 'Leave balance',
                'value' => $leaveSummary['balance']['vacation'] ?? 0,
                'hint'  => 'Vacation days left',
                'icon'  => 'bi-calendar2-week',
                'valueColor' => 'text-sky-600 dark:text-sky-400',
                'chip'  => 'bg-sky-50 text-sky-600 dark:bg-sky-900/30 dark:text-sky-300',
            ],
            [
                'label' => 'Pending leaves',
                'value' => $leaveSummary['pending'] ?? 0,
                'hint'  => 'Awaiting approval',
                'icon'  => 'bi-clock',
                'valueColor' => 'text-amber-600 dark:text-amber-400',
                'chip'  => 'bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-300',
            ],
        ];
    @endphp
    <section class="grid grid-cols-2 gap-4 lg:grid-cols-4" aria-label="Quick stats">
        @foreach ($stats as $s)
            <div class="{{ $card }} p-5">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ $s['label'] }}</p>
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg {{ $s['chip'] }}">
                        <i class="bi {{ $s['icon'] }}" aria-hidden="true"></i>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-semibold tabular-nums {{ $s['valueColor'] }}">{{ $s['value'] }}</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $s['hint'] }}</p>
            </div>
        @endforeach
    </section>

    {{-- ===== Leave summary + quick actions ===== --}}
    <section class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- My leave summary --}}
        <div class="{{ $card }} p-6 lg:col-span-2">
            <div class="{{ $cardHead }}">
                <div>
                    <h3 class="{{ $h3 }}">{!! $dot('bg-amber-500') !!} My leave summary</h3>
                    <p class="{{ $sub }}">Overview of your leaves</p>
                </div>
                <a href="{{ route('employee.leaves.history') }}" class="text-xs font-medium text-sky-700 hover:underline dark:text-sky-300">View all</a>
            </div>

            <div class="mb-5 grid grid-cols-3 gap-2">
                @foreach ([
                    ['Pending',  number_format($leaveSummary['pending'] ?? 0),  'bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-300'],
                    ['Approved', number_format($leaveSummary['approved'] ?? 0), 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300'],
                    ['Rejected', number_format($leaveSummary['rejected'] ?? 0), 'bg-rose-50 text-rose-700 dark:bg-rose-900/20 dark:text-rose-300'],
                ] as [$label, $val, $cls])
                    <div class="rounded-lg p-2.5 text-center {{ $cls }}">
                        <p class="text-lg font-semibold tabular-nums">{{ $val }}</p>
                        <p class="text-[10px] font-medium">{{ $label }}</p>
                    </div>
                @endforeach
            </div>

            <p class="mb-2 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Leave balance</p>
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-3">
                @foreach ([
                    ['Vacation',              $leaveSummary['balance']['vacation'] ?? 0,     'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/20 dark:text-indigo-300'],
                    ['Sick',                  $leaveSummary['balance']['sick'] ?? 0,         'bg-rose-50 text-rose-700 dark:bg-rose-900/20 dark:text-rose-300'],
                    ['Mandatory Forced Leave', $leaveSummary['balance']['forced_leave'] ?? 0, 'bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-300'],
                ] as [$label, $val, $cls])
                    <div class="flex items-center justify-between rounded-lg border border-gray-200 p-2.5 dark:border-gray-700">
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $label }}</span>
                        <span class="text-sm font-semibold tabular-nums text-gray-900 dark:text-white">{{ $val }} <span class="text-xs font-normal text-gray-500 dark:text-gray-400">days</span></span>
                    </div>
                @endforeach
            </div>

            @if (count($leaveSummary['upcoming'] ?? []) > 0)
                <p class="mb-2 mt-4 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Upcoming leaves</p>
                <div class="space-y-2">
                    @foreach ($leaveSummary['upcoming'] as $leave)
                        <div class="flex items-center justify-between rounded-lg border border-gray-200 p-2.5 dark:border-gray-700">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst($leave['type']) }} leave</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $leave['date_from'] }} – {{ $leave['date_to'] }}</p>
                            </div>
                            <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">Approved</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Quick actions --}}
        <div class="{{ $card }} p-6">
            <div class="{{ $cardHead }}">
                <div>
                    <h3 class="{{ $h3 }}">{!! $dot('bg-gray-400') !!} Quick actions</h3>
                    <p class="{{ $sub }}">Common tasks</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
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
        </div>
    </section>

    {{-- ===== Upcoming events ===== --}}
    <section class="{{ $card }} p-6">
        <div class="{{ $cardHead }}">
            <div>
                <h3 class="{{ $h3 }}">{!! $dot('bg-purple-500') !!} Upcoming events</h3>
                <p class="{{ $sub }}">Your birthdays and anniversaries</p>
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
                <div class="py-8 text-center">
                    <span class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-gray-50 dark:bg-gray-700/40">
                        <i class="bi bi-calendar text-2xl text-gray-300 dark:text-gray-600" aria-hidden="true"></i>
                    </span>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-200">No upcoming events</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">You don't have any events in the next 30 days</p>
                </div>
            @endforelse
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