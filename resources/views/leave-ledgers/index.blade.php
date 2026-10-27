@extends('layouts.app')

@section('title', 'Leave Ledger')
@section('header', 'Leave ledger')
@section('subheader', 'Leave credits and transaction history for every employee')

@php
    $fmt = fn ($n) => is_null($n) ? '—' : rtrim(rtrim(number_format($n, 3), '0'), '.');

    $btnPrimary = 'inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700';
    $btnGhost   = 'inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700';
    $field      = 'w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white';
    $lbl        = 'mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200';
    $card       = 'overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800';
    $stat       = 'rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800';

    $statConfig = [
        ['Employees',          $stats['employees'],  'bi-people',            'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-300',  'text-gray-900 dark:text-white'],
        ['Ledger entries',     $stats['entries'],    'bi-journal-check',     'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300','text-gray-900 dark:text-white'],
        ['Credited this month',$stats['accrued'],    'bi-calendar-check',    'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-300',          'text-gray-900 dark:text-white'],
        ['No ledger yet',      $stats['no_ledger'],  'bi-exclamation-circle','bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-300',     $stats['no_ledger'] > 0 ? 'text-amber-700 dark:text-amber-400' : 'text-gray-900 dark:text-white'],
    ];
@endphp

@section('header-actions')
    <button type="button" id="openAccrual" class="{{ $btnPrimary }}"><i class="bi bi-plus-circle"></i> Post monthly accrual</button>
@endsection

@section('breadcrumbs')
    <li class="flex items-center"><i class="bi bi-chevron-right mx-2 text-xs text-gray-300 dark:text-gray-600"></i>
        <span class="font-medium text-gray-800 dark:text-gray-200">Leave management</span></li>
    <li class="flex items-center"><i class="bi bi-chevron-right mx-2 text-xs text-gray-300 dark:text-gray-600"></i>
        <span class="font-medium text-gray-800 dark:text-gray-200">Leave ledger</span></li>
@endsection

@section('content')
<div class="space-y-6 p-6">

    {{-- ===== Flash ===== --}}
    @if (session('success'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-300" role="status">
            <i class="bi bi-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-300" role="alert">
            <ul class="list-inside list-disc space-y-1">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- ===== Key numbers ===== --}}
    <section class="grid grid-cols-2 gap-4 lg:grid-cols-4" aria-label="Summary">
        @foreach ($statConfig as [$name, $value, $icon, $chip, $color])
            <div class="{{ $stat }}">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $name }}</p>
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg {{ $chip }}">
                        <i class="bi {{ $icon }}" aria-hidden="true"></i>
                    </span>
                </div>
                <p class="mt-2 text-3xl font-semibold tabular-nums {{ $color }}">{{ number_format($value) }}</p>
            </div>
        @endforeach
    </section>

    {{-- ===== Filters ===== --}}
    <form method="GET" role="search" aria-label="Search employees"
          class="grid gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:grid-cols-2">
        <div>
            <label for="q" class="{{ $lbl }}">Employee</label>
            <div class="relative">
                <i class="bi bi-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                <input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="Search by name" class="{{ $field }} pl-9">
            </div>
        </div>
        <div class="flex items-end gap-3">
            <button type="submit" class="{{ $btnPrimary }}"><i class="bi bi-funnel"></i> Search</button>
            @if (request('q'))
                <a href="{{ route('leave-ledgers.index') }}" class="text-sm font-medium text-indigo-700 hover:underline dark:text-indigo-300">Clear</a>
            @endif
        </div>
    </form>

    {{-- ===== Ledger table ===== --}}
    <section class="{{ $card }}" aria-label="Leave ledger balances">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Employee balances</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400" role="status">
                {{ $employees->total() }} {{ \Illuminate\Support\Str::plural('employee', $employees->total()) }}
            </p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                <caption class="sr-only">Per-employee leave balances across leave types</caption>
                <thead class="bg-gray-50 dark:bg-gray-900/30">
                    <tr class="text-left text-gray-600 dark:text-gray-400">
                        <th scope="col" class="px-4 py-3 font-medium">Employee</th>
                        @foreach ($types as $t)
                            <th scope="col" class="px-4 py-3 text-right font-medium">{{ $t->code }} balance</th>
                        @endforeach
                        <th scope="col" class="px-4 py-3 text-right font-medium"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($employees as $e)
                        @php $emp = $employments[$e->user_id] ?? null; @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="px-4 py-3 align-top">
                                <p class="font-medium text-gray-900 dark:text-white">{{ \App\Support\EmployeeProfile::name($e) }}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    {{ $emp?->position?->title ?? '—' }} · {{ $emp?->department?->name ?? '—' }}
                                </p>
                            </td>
                            @foreach ($types as $t)
                                @php $b = $balances[$e->id][$t->id] ?? null; @endphp
                                <td class="px-4 py-3 text-right align-top tabular-nums {{ !is_null($b) && $b <= 0 ? 'font-semibold text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-300' }}">
                                    {{ $fmt($b) }}
                                </td>
                            @endforeach
                            <td class="whitespace-nowrap px-4 py-3 text-right align-top">
                                <a href="{{ route('leave-ledgers.show', $e) }}"
                                   class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-indigo-600 hover:bg-indigo-50 dark:border-gray-600 dark:bg-gray-800 dark:text-indigo-400 dark:hover:bg-gray-700"
                                   aria-label="Open ledger for {{ \App\Support\EmployeeProfile::name($e) }}">
                                    <i class="bi bi-journal-text" aria-hidden="true"></i> Open ledger
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $types->count() + 2 }}" class="px-4 py-14 text-center">
                                <i class="bi bi-journal-x text-4xl text-gray-300 dark:text-gray-600" aria-hidden="true"></i>
                                <p class="mt-2 font-medium text-gray-900 dark:text-white">
                                    {{ request('q') ? 'No employees match your search' : 'No employees found' }}
                                </p>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {{ request('q') ? 'Try a different name or clear the search.' : 'Employees will appear here once they are registered.' }}
                                </p>
                                @if (request('q'))
                                    <a href="{{ route('leave-ledgers.index') }}" class="{{ $btnGhost }} mt-4">Show all employees</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-col items-center justify-between gap-3 border-t border-gray-200 px-6 py-4 dark:border-gray-700 sm:flex-row">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Showing {{ $employees->firstItem() ?? 0 }}–{{ $employees->lastItem() ?? 0 }} of {{ $employees->total() }}
            </p>
            {{ $employees->withQueryString()->links() }}
        </div>
    </section>
</div>

{{-- ===== Monthly accrual modal ===== --}}
<dialog id="accrualModal" class="m-auto w-full max-w-md rounded-xl bg-white p-0 text-gray-900 shadow-2xl dark:bg-gray-800 dark:text-white" aria-labelledby="accrualTitle">
    <form method="POST" action="{{ route('leave-ledgers.accrue') }}">
        @csrf
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
            <h2 id="accrualTitle" class="text-lg font-semibold">Post monthly accrual</h2>
            <button type="button" id="closeAccrual" class="rounded p-1 text-gray-500 hover:text-gray-900 dark:hover:text-white" aria-label="Close">
                <i class="bi bi-x-lg" aria-hidden="true"></i>
            </button>
        </div>
        <div class="space-y-4 px-6 py-5">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Credits the month to every active employee. Anyone already credited for that month is skipped.
            </p>
            <div>
                <label for="month" class="{{ $lbl }}">Month <span class="text-red-600" aria-hidden="true">*</span></label>
                <input id="month" type="month" name="month" required
                       value="{{ old('month', now()->format('Y-m')) }}" class="{{ $field }}">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="vl_amount" class="{{ $lbl }}">Vacation leave (days) <span class="text-red-600" aria-hidden="true">*</span></label>
                    <input id="vl_amount" type="number" step="0.001" min="0" name="vl_amount" required
                           value="{{ old('vl_amount', '1.25') }}" class="{{ $field }}">
                </div>
                <div>
                    <label for="sl_amount" class="{{ $lbl }}">Sick leave (days) <span class="text-red-600" aria-hidden="true">*</span></label>
                    <input id="sl_amount" type="number" step="0.001" min="0" name="sl_amount" required
                           value="{{ old('sl_amount', '1.25') }}" class="{{ $field }}">
                </div>
            </div>
        </div>
        <div class="flex justify-end gap-3 border-t border-gray-200 px-6 py-4 dark:border-gray-700">
            <button type="button" id="cancelAccrual" class="{{ $btnGhost }}">Cancel</button>
            <button type="submit" class="{{ $btnPrimary }}"><i class="bi bi-calendar-check"></i> Post accrual</button>
        </div>
    </form>
</dialog>
@endsection

@push('scripts')
<script>
(() => {
    const modal = document.getElementById('accrualModal');
    if (!modal) return;

    document.getElementById('openAccrual')?.addEventListener('click', () => modal.showModal());
    document.getElementById('closeAccrual')?.addEventListener('click', () => modal.close());
    document.getElementById('cancelAccrual')?.addEventListener('click', () => modal.close());
    modal.addEventListener('click', e => { if (e.target === modal) modal.close(); });
})();
</script>
@endpush