@extends('layouts.app')

@section('title', $title)
@section('breadcrumbs')
    @foreach ($crumbs as $crumb)
        <li class="flex items-center">
            <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
            <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $crumb }}</span>
        </li>
    @endforeach
@endsection
@section('content')
@php
    $today = now()->toDateString();
    $query = fn (array $extra = []) => array_merge(request()->only(['department_id', 'leave_type_id', 'show', 'q']), $extra);
    $field = 'px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors text-sm';

    // One colour per leave type (full class names so Tailwind can see them)
    $palette = [
        'VL'    => 'bg-indigo-100 text-indigo-800 border-indigo-300',
        'SL'    => 'bg-rose-100 text-rose-800 border-rose-300',
        'MFL'   => 'bg-amber-100 text-amber-800 border-amber-300',
        'ML'    => 'bg-pink-100 text-pink-800 border-pink-300',
        'PL'    => 'bg-sky-100 text-sky-800 border-sky-300',
        'SPL'   => 'bg-violet-100 text-violet-800 border-violet-300',
        'SOLO'  => 'bg-teal-100 text-teal-800 border-teal-300',
        'STUDY' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
        'VAWC'  => 'bg-fuchsia-100 text-fuchsia-800 border-fuchsia-300',
        'REHAB' => 'bg-lime-100 text-lime-800 border-lime-300',
        'SLBW'  => 'bg-red-100 text-red-800 border-red-300',
        'SEL'   => 'bg-orange-100 text-orange-800 border-orange-300',
        'ADOPT' => 'bg-cyan-100 text-cyan-800 border-cyan-300',
    ];
    $fallback = 'bg-gray-100 text-gray-800 border-gray-300';
@endphp
<div class="py-6">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <x-leave.banner :title="$title" icon="bi-calendar3" :subtitle="$subtitle">
        <x-slot:actions>
            @if ($mode === 'hr')
                <x-leave.banner-action :href="route('leave-applications.index')" icon="bi-list-ul" class="no-print">Leave requests</x-leave.banner-action>
            @endif
            @if ($mode === 'self')
                <x-leave.banner-action :href="route('my-leaves.create')" icon="bi-plus-lg" class="no-print">Apply for leave</x-leave.banner-action>
            @endif
            <x-leave.banner-action onclick="window.print()" icon="bi-printer" class="no-print">Print</x-leave.banner-action>
        </x-slot:actions>
    </x-leave.banner>

    @php
        $grads = [
            ['bg-gradient-to-r from-[#0e7490] via-[#3b82f6] to-[#4f46e5]', 'bg-indigo-100', 'text-indigo-600'],
            ['bg-gradient-to-bl from-[#84cc16] via-[#16a34a] to-[#0f766e]', 'bg-green-100', 'text-green-600'],
            ['bg-gradient-to-tl from-[#1e293b] via-[#6366f1] to-[#71717a]', 'bg-purple-100', 'text-purple-600'],
            ['bg-[radial-gradient(ellipse_at_bottom_left,_var(--tw-gradient-stops))] from-[#ea580c] via-[#fb923c] to-[#fed7aa]', 'bg-amber-100', 'text-amber-600'],
        ];
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6 no-print">
        @foreach ($statCards as $i => $card)
            <x-leave.stat :label="$card['label']" :value="$card['value']" :hint="$card['hint'] ?? null" :icon="$card['icon']"
                :gradient="$grads[$i][0]" :iconBg="$grads[$i][1]" :iconColor="$grads[$i][2]" />
        @endforeach
    </div>

    {{-- Month navigation + filters --}}
    <div class="mb-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between no-print">
        <div class="flex items-center gap-2">
            <a href="{{ route($routeName, $query(['month' => $prev])) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-300 hover:bg-indigo-50 dark:border-gray-600 dark:hover:bg-gray-700" aria-label="Previous month"><i class="bi bi-chevron-left"></i></a>
            <h2 class="min-w-[10rem] text-center text-xl font-semibold text-gray-900 dark:text-white">{{ $first->format('F Y') }}</h2>
            <a href="{{ route($routeName, $query(['month' => $next])) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-300 hover:bg-indigo-50 dark:border-gray-600 dark:hover:bg-gray-700" aria-label="Next month"><i class="bi bi-chevron-right"></i></a>
            <a href="{{ route($routeName, $query()) }}" class="ml-2 rounded-lg border border-gray-300 px-3 py-1.5 text-sm hover:bg-indigo-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Today</a>
        </div>

        @if ($mode !== 'self')
        <form method="GET" class="flex flex-wrap items-center gap-2">
            <input type="hidden" name="month" value="{{ $first->format('Y-m') }}">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Search employee" class="{{ $field }} w-40" aria-label="Search employee">
            @if ($mode === 'hr')
            <select name="department_id" onchange="this.form.submit()" class="{{ $field }}" aria-label="Department">
                <option value="">All departments</option>
                @foreach ($departments as $d)<option value="{{ $d->id }}" @selected(request('department_id') == $d->id)>{{ $d->name }}</option>@endforeach
            </select>
            @endif
            <select name="leave_type_id" onchange="this.form.submit()" class="{{ $field }}" aria-label="Leave type">
                <option value="">All leave types</option>
                @foreach ($leaveTypes as $t)<option value="{{ $t->id }}" @selected(request('leave_type_id') == $t->id)>{{ $t->name }}</option>@endforeach
            </select>
            <select name="show" onchange="this.form.submit()" class="{{ $field }}" aria-label="Status">
                <option value="approved" @selected(request('show') !== 'all')>Approved only</option>
                <option value="all" @selected(request('show') === 'all')>Approved + pending</option>
            </select>
            <button class="rounded-lg border border-gray-300 px-3 py-2 text-sm hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Search</button>
            @if (request()->hasAny(['q', 'department_id', 'leave_type_id', 'show']))
                <a href="{{ route($routeName, ['month' => $first->format('Y-m')]) }}" class="text-sm text-indigo-600 hover:underline dark:text-indigo-400">Clear</a>
            @endif
        </form>
        @endif
    </div>

    {{-- Calendar --}}
    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="min-w-[56rem]">
            <div class="grid grid-cols-7 border-b border-gray-200 bg-gray-50 text-xs font-semibold uppercase tracking-wide text-gray-600 dark:border-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dow)
                    <div class="px-3 py-2 text-center">{{ $dow }}</div>
                @endforeach
            </div>

            @foreach ($weeks as $week)
                <div class="grid grid-cols-7">
                    @foreach ($week as $d)
                        @php
                            $key = $d->toDateString();
                            $items = $byDay[$key] ?? [];
                            $inMonth = $d->month === $first->month;
                            $weekend = $d->isWeekend();
                            $isToday = $key === $today;
                        @endphp
                        <div class="min-h-[7.5rem] border-b border-r border-gray-200 p-1.5 dark:border-gray-700 {{ ! $inMonth ? 'bg-gray-50 text-gray-400 dark:bg-gray-900/40' : ($weekend ? 'bg-gray-50/70 dark:bg-gray-800/60' : '') }}">
                            <div class="flex items-center justify-between">
                                <span class="inline-flex h-6 min-w-[1.5rem] items-center justify-center rounded-full px-1 text-xs font-medium {{ $isToday ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white' : '' }}">{{ $d->day }}</span>
                                @if (count($items))
                                    <button type="button" data-day="{{ $key }}" class="day-open text-[10px] text-gray-500 hover:text-indigo-600">{{ count($items) }} out</button>
                                @endif
                            </div>
                            @foreach (array_slice($items, 0, 3) as $it)
                                @php $tag = $it['url'] ? 'a' : 'span'; @endphp
                                <{{ $tag }} @if ($it['url']) href="{{ $it['url'] }}" @endif title="{{ $it['name'] }} — {{ $it['type'] }} ({{ $it['status_label'] }})"
                                   class="mt-1 block truncate rounded border px-1.5 py-0.5 text-[11px] leading-tight {{ $it['url'] ? 'hover:opacity-80' : '' }} {{ $palette[$it['code']] ?? $fallback }} {{ $it['status'] !== 'approved' ? 'border-dashed opacity-70' : '' }}">
                                    @if ($it['half'])½ @endif{{ $it['label'] }}
                                </{{ $tag }}>
                            @endforeach
                            @if (count($items) > 3)
                                <button type="button" data-day="{{ $key }}" class="day-open mt-1 text-[11px] text-indigo-600 hover:underline dark:text-indigo-400">+{{ count($items) - 3 }} more</button>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

    {{-- Legend --}}
    <div class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-gray-600 dark:text-gray-400">
        @foreach ($legend as $l)
            <span class="inline-flex items-center gap-1.5"><span class="inline-block h-3 w-3 rounded border {{ $palette[$l['code']] ?? $fallback }}"></span>{{ $l['type'] }}</span>
        @endforeach
        @if (request('show') === 'all')
            <span class="inline-flex items-center gap-1.5"><span class="inline-block h-3 w-3 rounded border border-dashed border-gray-400"></span>Dashed = not yet approved</span>
        @endif
        <span>½ = half day</span>
        @if (! count($byDay))<span class="italic">No leave in this period.</span>@endif
    </div>
</div>
</div>

{{-- Day detail modal --}}
<div id="dayModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 backdrop-blur-sm hidden z-50 items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="dayTitle">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full max-h-[85vh] flex flex-col">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 id="dayTitle" class="text-lg font-semibold text-gray-900 dark:text-white"></h3>
            <button type="button" id="closeDay" class="text-gray-400 hover:text-gray-600" aria-label="Close"><i class="bi bi-x-lg"></i></button>
        </div>
        <ul id="dayList" class="divide-y divide-gray-100 overflow-y-auto px-6 py-2 dark:divide-gray-700"></ul>
    </div>
</div>

<script>
(function () {
    var data = @json($byDay);
    var modal = document.getElementById('dayModal');
    function esc(t) { var d = document.createElement('div'); d.textContent = t == null ? '' : t; return d.innerHTML; }
    function open(day) {
        var list = data[day] || [];
        var d = new Date(day + 'T00:00:00');
        document.getElementById('dayTitle').textContent =
            d.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) + ' — ' + list.length + ' on leave';
        document.getElementById('dayList').innerHTML = list.map(function (i) {
            var badge = i.status === 'approved'
                ? '<span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Approved</span>'
                : '<span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-800">' + esc(i.status_label) + '</span>';
            var tagOpen = i.url ? '<a href="' + esc(i.url) + '" class="block hover:opacity-80">' : '<div class="block">';
            var tagClose = i.url ? '</a>' : '</div>';
            return '<li class="py-3">' + tagOpen +
                '<div class="flex items-center justify-between gap-2"><span class="font-medium text-gray-900 dark:text-gray-100">' + esc(i.name) + '</span>' + badge + '</div>' +
                '<p class="text-sm text-gray-600 dark:text-gray-400">' + esc(i.type) + (i.half ? ' (half day)' : '') + ' · ' + esc(i.range) + ' · ' + esc(i.days) + ' day(s)</p>' +
                (i.dept ? '<p class="text-xs text-gray-500">' + esc(i.dept) + '</p>' : '') + tagClose + '</li>';
        }).join('');
        modal.classList.remove('hidden'); modal.classList.add('flex');
    }
    function close() { modal.classList.add('hidden'); modal.classList.remove('flex'); }
    document.querySelectorAll('.day-open').forEach(function (b) { b.addEventListener('click', function () { open(b.dataset.day); }); });
    document.getElementById('closeDay').addEventListener('click', close);
    modal.addEventListener('click', function (e) { if (e.target === modal) close(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
})();
</script>
@endsection

@push('styles')
<style>
    @media print { .no-print { display: none !important; } }
</style>
@endpush