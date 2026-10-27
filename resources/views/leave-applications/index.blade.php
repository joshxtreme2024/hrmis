@extends('layouts.app')

@section('title', 'Leave Applications')
@section('header', 'Leave requests')
@section('subheader', 'CS Form No. 6 applications for the Municipality of Aguinaldo')

@php
    use Illuminate\Support\Facades\Route;

    $btnPrimary = 'inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700';
    $btnGhost   = 'inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700';
    $field = 'w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white';
    $lbl   = 'mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200';

    $createUrl  = Route::has('leave-applications.create') ? route('leave-applications.create') : null;
    $hasFilters = collect(['q', 'leave_type_id', 'from', 'to'])->contains(fn ($k) => filled(request($k)));
    $statusTabs = ['' => 'All'] + \App\Models\LeaveApplication::STATUSES;

    $stats = [
        ['Awaiting HR', $counts['pending'] ?? 0, 'bi-hourglass-split', 'pending', 'bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-300', ($counts['pending'] ?? 0) > 0 ? 'text-amber-700 dark:text-amber-400' : 'text-gray-900 dark:text-white'],
        ['In review', ($counts['certified'] ?? 0) + ($counts['recommended'] ?? 0), 'bi-eye', null, 'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-300', 'text-gray-900 dark:text-white'],
        ['Approved', $counts['approved'] ?? 0, 'bi-check2-circle', 'approved', 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300', 'text-emerald-700 dark:text-emerald-400'],
        ['Disapproved', $counts['disapproved'] ?? 0, 'bi-x-circle', 'disapproved', 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-300', 'text-gray-900 dark:text-white'],
    ];

    // "Mar 03", "Mar 03 – Mar 05, 2026", or both years when the range crosses a year end
    $range = function ($a) {
        if ($a->date_from->eq($a->date_to)) return $a->date_from->format('M d, Y');
        if ($a->date_from->year !== $a->date_to->year) return $a->date_from->format('M d, Y') . ' – ' . $a->date_to->format('M d, Y');
        return $a->date_from->format('M d') . ' – ' . $a->date_to->format('M d, Y');
    };
@endphp


@section('breadcrumbs')
    <li class="flex items-center"><i class="bi bi-chevron-right mx-2 text-xs text-gray-300 dark:text-gray-600"></i><span class="font-medium text-gray-800 dark:text-gray-200">Leave management</span></li>
    <li class="flex items-center"><i class="bi bi-chevron-right mx-2 text-xs text-gray-300 dark:text-gray-600"></i><span class="font-medium text-gray-800 dark:text-gray-200">Leave applications</span></li>
@endsection

@section('content')
<div class="space-y-6">

    {{-- ===== Key numbers (click to filter) ===== --}}
    <section class="grid grid-cols-2 gap-4 lg:grid-cols-4" aria-label="Summary">
        @foreach($stats as [$name, $value, $icon, $status, $chip, $color])
            @php $tag = $status ? 'a' : 'div'; @endphp
            <{{ $tag }} @if($status) href="{{ route('leave-applications.index', ['status' => $status]) }}" @endif
                class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800 {{ $status ? 'hover:border-blue-300 dark:hover:border-blue-700' : '' }}">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $name }}</p>
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg {{ $chip }}"><i class="bi {{ $icon }}" aria-hidden="true"></i></span>
                </div>
                <p class="mt-2 text-3xl font-semibold tabular-nums {{ $color }}">{{ number_format($value) }}</p>
            </{{ $tag }}>
        @endforeach
    </section>

    {{-- ===== Status tabs ===== --}}
    <nav class="flex flex-wrap gap-2" aria-label="Filter by status">
        @foreach($statusTabs as $key => $label)
            @php $active = (string) request('status') === (string) $key; @endphp
            <a href="{{ route('leave-applications.index', array_merge(request()->except('status', 'page'), $key === '' ? [] : ['status' => $key])) }}"
               @if($active) aria-current="page" @endif
               class="inline-flex items-center gap-1.5 rounded-full border px-3.5 py-1.5 text-sm font-medium transition-colors
                      {{ $active ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300 bg-white text-gray-700 hover:bg-blue-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                {{ $label }}
                @if($key !== '' && ($counts[$key] ?? 0) > 0)
                    <span class="rounded-full px-1.5 text-xs tabular-nums {{ $active ? 'bg-white/20' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">{{ $counts[$key] }}</span>
                @endif
            </a>
        @endforeach
    </nav>

    {{-- ===== Filters ===== --}}
    <form method="GET" role="search" aria-label="Filter leave applications"
          class="grid gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:grid-cols-2 lg:grid-cols-4">
        @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
        <div>
            <label for="q" class="{{ $lbl }}">Employee</label>
            <div class="relative">
                <i class="bi bi-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                <input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="Search by name" class="{{ $field }} pl-9">
            </div>
        </div>
        <div>
            <label for="leave_type_id" class="{{ $lbl }}">Leave type</label>
            <select id="leave_type_id" name="leave_type_id" class="{{ $field }}">
                <option value="">All leave types</option>
                @foreach($leaveTypes as $t)
                    <option value="{{ $t->id }}" @selected(request('leave_type_id') == $t->id)>{{ $t->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="from" class="{{ $lbl }}">Leave starts on or after</label>
            <input id="from" type="date" name="from" value="{{ request('from') }}" class="{{ $field }}">
        </div>
        <div>
            <label for="to" class="{{ $lbl }}">Leave ends on or before</label>
            <input id="to" type="date" name="to" value="{{ request('to') }}" class="{{ $field }}">
        </div>
        <div class="flex items-center gap-3 sm:col-span-2 lg:col-span-4">
            <button type="submit" class="{{ $btnPrimary }}"><i class="bi bi-funnel"></i> Apply filters</button>
            @if($hasFilters)
                <a href="{{ route('leave-applications.index', request()->only('status')) }}" class="text-sm font-medium text-blue-700 hover:underline dark:text-blue-300">Clear filters</a>
            @endif
        </div>
    </form>

    {{-- ===== Applications ===== --}}
    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800" aria-label="Leave applications">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Applications</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400" role="status">{{ $applications->total() }} {{ \Illuminate\Support\Str::plural('result', $applications->total()) }}</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                <caption class="sr-only">Leave applications with employee, leave type, dates, days and status</caption>
                <thead class="bg-gray-50 dark:bg-gray-900/30">
                    <tr class="text-left text-gray-600 dark:text-gray-400">
                        <th scope="col" class="px-4 py-3 font-medium">Filed</th>
                        <th scope="col" class="px-4 py-3 font-medium">Employee</th>
                        <th scope="col" class="px-4 py-3 font-medium">Leave type</th>
                        <th scope="col" class="px-4 py-3 font-medium">Inclusive dates</th>
                        <th scope="col" class="px-4 py-3 text-right font-medium">Days</th>
                        <th scope="col" class="px-4 py-3 font-medium">Status</th>
                        <th scope="col" class="px-4 py-3 text-right font-medium"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($applications as $a)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="whitespace-nowrap px-4 py-3 align-top text-gray-700 dark:text-gray-300">{{ $a->date_of_filing->format('M d, Y') }}</td>
                            <td class="px-4 py-3 align-top">
                                <a href="{{ route('leave-applications.show', $a) }}" class="font-medium text-gray-900 hover:text-blue-700 dark:text-white dark:hover:text-blue-300">{{ $a->employeeName() }}</a>
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $a->department?->name ?? $a->position }}</p>
                            </td>
                            <td class="px-4 py-3 align-top text-gray-700 dark:text-gray-300">{{ $a->leaveType->name }}{{ $a->leave_type_other ? ' — ' . $a->leave_type_other : '' }}</td>
                            <td class="whitespace-nowrap px-4 py-3 align-top text-gray-700 dark:text-gray-300">{{ $range($a) }}</td>
                            <td class="px-4 py-3 text-right align-top tabular-nums text-gray-700 dark:text-gray-300">{{ rtrim(rtrim(number_format($a->working_days, 2), '0'), '.') }}</td>
                            <td class="whitespace-nowrap px-4 py-3 align-top"><span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $a->statusBadge() }}">{{ $a->statusLabel() }}</span></td>
                            <td class="whitespace-nowrap px-4 py-3 text-right align-top">
                                <a href="{{ route('leave-applications.show', $a) }}" class="inline-flex rounded p-2 text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/30" aria-label="View application of {{ $a->employeeName() }}" title="View"><i class="bi bi-eye"></i></a>
                                @if($a->isEditable())
                                    <a href="{{ route('leave-applications.edit', $a) }}" class="inline-flex rounded p-2 text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/30" aria-label="Edit application of {{ $a->employeeName() }}" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                @endif
                                @if($a->isDeletable())
                                    <form method="POST" action="{{ route('leave-applications.destroy', $a) }}" class="inline js-confirm" data-message="The leave application of {{ $a->employeeName() }} will be deleted. This can’t be undone.">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex rounded p-2 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/30" aria-label="Delete application of {{ $a->employeeName() }}" title="Delete"><i class="bi bi-trash3"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-14 text-center">
                                <i class="bi bi-calendar2-x text-4xl text-gray-300 dark:text-gray-600" aria-hidden="true"></i>
                                <p class="mt-2 font-medium text-gray-900 dark:text-white">{{ ($hasFilters || request('status')) ? 'No applications match your filters' : 'No leave applications yet' }}</p>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ ($hasFilters || request('status')) ? 'Try a different status or date range, or clear the filters.' : 'Applications will appear here once they are filed.' }}</p>
                                @if($hasFilters || request('status'))
                                    <a href="{{ route('leave-applications.index') }}" class="{{ $btnGhost }} mt-4">Show all applications</a>
                                @elseif($createUrl)
                                    <a href="{{ $createUrl }}" class="{{ $btnPrimary }} mt-4"><i class="bi bi-plus-lg"></i> New application</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-col items-center justify-between gap-3 border-t border-gray-200 px-6 py-4 dark:border-gray-700 sm:flex-row">
            <p class="text-sm text-gray-600 dark:text-gray-400">Showing {{ $applications->firstItem() ?? 0 }}–{{ $applications->lastItem() ?? 0 }} of {{ $applications->total() }}</p>
            {{ $applications->withQueryString()->links() }}
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
// Delete confirmation: SweetAlert when available, browser confirm otherwise
document.querySelectorAll('form.js-confirm').forEach(form => {
    form.addEventListener('submit', e => {
        if (form.dataset.ok) return;
        e.preventDefault();
        const msg = form.dataset.message || 'This can’t be undone.';
        const go = () => { form.dataset.ok = '1'; form.submit(); };
        if (window.Swal) {
            Swal.fire({ title: 'Delete this application?', text: msg, icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#dc2626', confirmButtonText: 'Delete', cancelButtonText: 'Cancel' }).then(r => r.isConfirmed && go());
        } else if (confirm(msg)) { go(); }
    });
});
</script>
@endpush