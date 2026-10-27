@extends('layouts.app')

@section('title', 'My Leave Ledger')
@section('breadcrumbs')
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">My Leave Ledger</span>
    </li>
@endsection
@section('content')
@php
    $fmt = fn ($n) => is_null($n) ? '—' : rtrim(rtrim(number_format($n, 3), '0'), '.');
    $period = function ($e) {
        if (! $e->period_start) return '—';
        if (! $e->period_end || $e->period_start->eq($e->period_end)) return $e->period_start->format('M d, Y');
        if ($e->period_start->day === 1 && $e->period_end->isSameDay($e->period_start->copy()->endOfMonth())) return $e->period_start->format('M Y');
        return $e->period_start->format('M d') . ' – ' . $e->period_end->format('M d, Y');
    };
    $current = $types->firstWhere('id', $typeId);
    $select = 'px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors text-sm';
@endphp
<div class="py-6">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <x-leave.banner title="My Leave Ledger" icon="bi-journal-text" subtitle="Your leave credits and every change made to them.">
        <x-slot:actions>
            <x-leave.banner-action :href="route('my-leaves.create')" icon="bi-plus-lg" class="no-print">Apply for leave</x-leave.banner-action>
            <x-leave.banner-action onclick="window.print()" icon="bi-printer" class="no-print">Print</x-leave.banner-action>
        </x-slot:actions>
    </x-leave.banner>

    {{-- Balances --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
        @foreach ($types->take(2) as $i => $t)
            @if ($i === 0)
                <x-leave.stat :label="$t->name . ' balance'" :value="$fmt($balances[$t->id] ?? null)" unit="days" icon="bi-wallet2" gradient="bg-gradient-to-r from-[#0e7490] via-[#3b82f6] to-[#4f46e5]" />
            @else
                <x-leave.stat :label="$t->name . ' balance'" :value="$fmt($balances[$t->id] ?? null)" unit="days" icon="bi-heart-pulse" gradient="bg-gradient-to-bl from-[#84cc16] via-[#16a34a] to-[#0f766e]" iconBg="bg-green-100" iconColor="text-green-600" />
            @endif
        @endforeach
        <x-leave.stat :label="'Earned' . ($year ? ' in ' . $year : '')" :value="$fmt($entries->sum('earned'))" icon="bi-plus-circle" gradient="bg-gradient-to-tl from-[#1e293b] via-[#6366f1] to-[#71717a]" iconBg="bg-purple-100" iconColor="text-purple-600" />
        <x-leave.stat :label="'Used' . ($year ? ' in ' . $year : '')" :value="$fmt($entries->sum('days_with_pay'))" icon="bi-dash-circle" gradient="bg-[radial-gradient(ellipse_at_bottom_left,_var(--tw-gradient-stops))] from-[#ea580c] via-[#fb923c] to-[#fed7aa]" iconBg="bg-amber-100" iconColor="text-amber-600" />
    </div>

    {{-- Leave type tabs + year filter --}}
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between no-print">
        <div class="flex flex-wrap gap-2">
            @foreach ($types as $t)
                <a href="{{ route('my-leave-ledger.index', ['type' => $t->id]) }}"
                   class="rounded-full border px-3 py-1 text-sm {{ $typeId === $t->id ? 'border-indigo-600 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white' : 'border-gray-300 text-gray-700 hover:bg-indigo-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700' }}">{{ $t->name }}</a>
            @endforeach
        </div>
        @if ($years->isNotEmpty())
            <form method="GET" class="flex items-center gap-2">
                <input type="hidden" name="type" value="{{ $typeId }}">
                <label for="year" class="text-sm text-gray-600 dark:text-gray-400">Year</label>
                <select id="year" name="year" onchange="this.form.submit()" class="{{ $select }}">
                    <option value="">All years</option>
                    @foreach ($years as $y)<option value="{{ $y }}" @selected($year === $y)>{{ $y }}</option>@endforeach
                </select>
            </form>
        @endif
    </div>

    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-gray-200 bg-gray-50 text-xs uppercase tracking-wide text-gray-600 dark:border-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                <tr>
                    <th class="px-4 py-3 font-semibold">Date</th>
                    <th class="px-4 py-3 font-semibold">Period</th>
                    <th class="px-4 py-3 font-semibold">Particulars</th>
                    <th class="px-4 py-3 text-right font-semibold">Earned</th>
                    <th class="px-4 py-3 text-right font-semibold">Used (w/ pay)</th>
                    <th class="px-4 py-3 text-right font-semibold">Used (w/o pay)</th>
                    <th class="px-4 py-3 text-right font-semibold">Balance</th>
                    <th class="px-4 py-3 font-semibold">Action taken</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @if ($year && ! is_null($brought))
                    <tr class="bg-indigo-50/60 dark:bg-gray-700/30">
                        <td class="px-4 py-3" colspan="6"><i class="bi bi-arrow-return-right mr-1.5 text-indigo-600"></i>Balance brought forward from earlier years</td>
                        <td class="px-4 py-3 text-right font-semibold tabular-nums">{{ $fmt($brought) }}</td>
                        <td></td>
                    </tr>
                @endif
                @forelse ($entries as $e)
                    <tr class="{{ $e->reversal ? 'bg-gray-50 text-gray-500 dark:bg-gray-900/40' : '' }}">
                        <td class="whitespace-nowrap px-4 py-3">{{ $e->transaction_date->format('M d, Y') }}</td>
                        <td class="whitespace-nowrap px-4 py-3">{{ $period($e) }}</td>
                        <td class="px-4 py-3">
                            <span class="mr-1.5 rounded-full px-2 py-0.5 text-xs font-medium {{ $e->typeBadge() }}">{{ $e->typeLabel() }}</span>
                            {{ $e->particulars }}
                            @if ($e->reversal)<span class="ml-1 text-xs italic">(reversed)</span>@endif
                        </td>
                        <td class="px-4 py-3 text-right tabular-nums text-green-700 dark:text-green-400">{{ $e->earned ? $fmt($e->earned) : '' }}</td>
                        <td class="px-4 py-3 text-right tabular-nums text-red-600">{{ $e->days_with_pay ? $fmt($e->days_with_pay) : '' }}</td>
                        <td class="px-4 py-3 text-right tabular-nums">{{ $e->days_without_pay ? $fmt($e->days_without_pay) : '' }}</td>
                        <td class="px-4 py-3 text-right font-semibold tabular-nums {{ $e->balance < 0 ? 'text-red-600' : '' }}">{{ $fmt($e->balance) }}</td>
                        <td class="px-4 py-3">{{ $e->action_taken ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-12 text-center text-gray-500">No ledger entries yet{{ $current ? ' for ' . $current->name : '' }}. HR posts your leave credits and approved leaves here.</td></tr>
                @endforelse
            </tbody>
            @if ($entries->isNotEmpty())
                <tfoot class="border-t border-gray-200 bg-gray-50 text-sm font-semibold dark:border-gray-700 dark:bg-gray-700/50">
                    <tr>
                        <td class="px-4 py-3" colspan="3">Totals{{ $year ? ' for ' . $year : '' }}</td>
                        <td class="px-4 py-3 text-right tabular-nums">{{ $fmt($entries->sum('earned')) }}</td>
                        <td class="px-4 py-3 text-right tabular-nums">{{ $fmt($entries->sum('days_with_pay')) }}</td>
                        <td class="px-4 py-3 text-right tabular-nums">{{ $fmt($entries->sum('days_without_pay')) }}</td>
                        <td class="px-4 py-3 text-right tabular-nums">{{ $fmt($entries->last()->balance) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
    <p class="mt-3 text-xs text-gray-500 no-print">Entries are posted by HR and can’t be edited. If something looks wrong, contact HR.</p>
</div>
</div>
@endsection

@push('styles')
<style>
    @media print { .no-print { display: none !important; } }
</style>
@endpush