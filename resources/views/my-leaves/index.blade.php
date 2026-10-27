@extends('layouts.app')

@section('title', 'My Leaves')

@php
    $fmt = fn ($n) => is_null($n) ? '—' : rtrim(rtrim(number_format($n, 3), '0'), '.');

    $g = config('stats');

    $vl = $balances['VL'];
    $sl = $balances['SL'];
    $hintFor = fn ($b) => is_null($b['earned'])
        ? 'No credit record yet — ask HR.'
        : 'Earned ' . $fmt($b['earned']) . ' · used ' . $fmt($b['used']);

    $tabs = ['' => 'All'] + \App\Models\LeaveApplication::STATUSES;

    $inProgress = ($counts['pending'] ?? 0) + ($counts['certified'] ?? 0) + ($counts['recommended'] ?? 0);
@endphp

@section('breadcrumbs')
    <li class="flex items-center"><i class="bi bi-chevron-right mx-2 text-xs text-gray-300 dark:text-gray-600"></i>
        <span class="font-medium text-gray-800 dark:text-gray-200">My leaves</span></li>
@endsection

@section('content')
<div class="p-6 space-y-6">

    {{-- ===== Banner ===== --}}
    <x-leave.banner
        title="My leaves"
        subtitle="Your leave credits and applications"
        icon="bi-calendar2-heart">
        <x-slot:actions>
            <x-leave.banner-action :href="route('my-leaves.create')" icon="bi-plus-lg" variant="primary">Apply for leave</x-leave.banner-action>
        </x-slot:actions>
    </x-leave.banner>

    {{-- ===== Flash ===== --}}
    @if (session('success'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-300" role="status">
            <i class="bi bi-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-300" role="alert">
            <i class="bi bi-exclamation-triangle mr-1"></i> {{ session('error') }}
        </div>
    @endif

    {{-- ===== Gradient stat cards ===== --}}
    <section class="grid grid-cols-2 gap-4 lg:grid-cols-4" aria-label="Summary">
        <x-leave.stat
            label="Vacation leave"
            :value="$fmt($vl['balance'])"
            unit="days"
            :hint="$hintFor($vl)"
            icon="bi-sun"
            :gradient="$g['indigo']" />
        <x-leave.stat
            label="Sick leave"
            :value="$fmt($sl['balance'])"
            unit="days"
            :hint="$hintFor($sl)"
            icon="bi-heart-pulse"
            :gradient="$g['emerald']" />
        <x-leave.stat
            label="In progress"
            :value="number_format($inProgress)"
            icon="bi-hourglass-split"
            :gradient="$g['sky']" />
        <x-leave.stat
            label="Approved"
            :value="number_format($counts['approved'] ?? 0)"
            icon="bi-check2-circle"
            :gradient="$g['amber']" />
    </section>

    {{-- ===== Status tabs ===== --}}
    <nav class="flex flex-wrap gap-2" aria-label="Filter by status">
        @foreach ($tabs as $key => $label)
            @continue($key === 'draft')
            @php $active = (string) request('status') === (string) $key; @endphp
            <a href="{{ route('my-leaves.index', array_merge(request()->except('status', 'page'), $key === '' ? [] : ['status' => $key])) }}"
               @if($active) aria-current="page" @endif
               class="inline-flex items-center gap-1.5 rounded-full border px-3.5 py-1.5 text-sm font-medium transition-colors
                      {{ $active
                          ? 'border-purple-600 bg-purple-600 text-white'
                          : 'border-gray-300 bg-white text-gray-700 hover:bg-purple-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                {{ $label }}
                @if ($key !== '' && ($counts[$key] ?? 0) > 0)
                    <span class="rounded-full px-1.5 text-xs tabular-nums
                                 {{ $active ? 'bg-white/20' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $counts[$key] }}
                    </span>
                @endif
            </a>
        @endforeach
    </nav>

    {{-- ===== Applications ===== --}}
    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800" aria-label="My leave applications">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">My applications</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400" role="status">
                {{ $applications->total() }} {{ \Illuminate\Support\Str::plural('result', $applications->total()) }}
            </p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                <caption class="sr-only">My leave applications with leave type, dates, days and status</caption>
                <thead class="bg-gray-50 dark:bg-gray-900/30">
                    <tr class="text-left text-gray-600 dark:text-gray-400">
                        <th scope="col" class="px-4 py-3 font-medium">Filed</th>
                        <th scope="col" class="px-4 py-3 font-medium">Leave type</th>
                        <th scope="col" class="px-4 py-3 font-medium">Inclusive dates</th>
                        <th scope="col" class="px-4 py-3 text-right font-medium">Days</th>
                        <th scope="col" class="px-4 py-3 font-medium">Status</th>
                        <th scope="col" class="px-4 py-3 text-right font-medium"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($applications as $a)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="whitespace-nowrap px-4 py-3 align-top text-gray-700 dark:text-gray-300">
                                {{ $a->date_of_filing->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3 align-top text-gray-700 dark:text-gray-300">
                                <span class="font-medium text-gray-900 dark:text-white">{{ $a->leaveType->name }}</span>
                                @if ($a->leave_type_other)
                                    <span class="block text-xs text-gray-500 dark:text-gray-400">{{ $a->leave_type_other }}</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 align-top text-gray-700 dark:text-gray-300">
                                {{ $a->date_from->format('M d') }}{{ $a->date_from->ne($a->date_to) ? ' – ' . $a->date_to->format('M d, Y') : ', ' . $a->date_to->format('Y') }}
                            </td>
                            <td class="px-4 py-3 text-right align-top tabular-nums text-gray-700 dark:text-gray-300">
                                {{ $fmt($a->working_days) }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 align-top">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $a->statusBadge() }}">{{ $a->statusLabel() }}</span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-right align-top">
                                <a href="{{ route('my-leaves.show', $a) }}"
                                   class="inline-flex rounded p-2 text-purple-600 hover:bg-purple-50 dark:text-purple-400 dark:hover:bg-purple-900/30"
                                   aria-label="View application filed {{ $a->date_of_filing->format('M d, Y') }}" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if ($a->isEditable())
                                    <a href="{{ route('my-leaves.edit', $a) }}"
                                       class="inline-flex rounded p-2 text-purple-600 hover:bg-purple-50 dark:text-purple-400 dark:hover:bg-purple-900/30"
                                       aria-label="Edit application filed {{ $a->date_of_filing->format('M d, Y') }}" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                @endif
                                @if ($a->isCancellable())
                                    <form method="POST" action="{{ route('my-leaves.cancel', $a) }}"
                                          class="inline js-confirm"
                                          data-message="Cancel this leave application? This cannot be undone.">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex rounded p-2 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/30"
                                                aria-label="Cancel application filed {{ $a->date_of_filing->format('M d, Y') }}" title="Cancel">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-14 text-center">
                                <i class="bi bi-calendar2-x text-4xl text-gray-300 dark:text-gray-600" aria-hidden="true"></i>
                                <p class="mt-2 font-medium text-gray-900 dark:text-white">
                                    {{ request('status') ? 'No applications with this status' : 'You have no leave applications yet' }}
                                </p>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {{ request('status') ? 'Try a different status, or show all applications.' : 'Once you file a leave, it will appear here.' }}
                                </p>
                                @if (request('status'))
                                    <a href="{{ route('my-leaves.index') }}"
                                       class="mt-4 inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                                        Show all applications
                                    </a>
                                @else
                                    <a href="{{ route('my-leaves.create') }}"
                                       class="mt-4 inline-flex items-center gap-2 rounded-lg bg-purple-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-purple-700">
                                        <i class="bi bi-plus-lg"></i> Apply for leave
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-col items-center justify-between gap-3 border-t border-gray-200 px-6 py-4 dark:border-gray-700 sm:flex-row">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Showing {{ $applications->firstItem() ?? 0 }}–{{ $applications->lastItem() ?? 0 }} of {{ $applications->total() }}
            </p>
            {{ $applications->withQueryString()->links() }}
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('form.js-confirm').forEach(form => {
    form.addEventListener('submit', e => {
        if (form.dataset.ok) return;
        e.preventDefault();
        const msg = form.dataset.message || 'This can’t be undone.';
        const go = () => { form.dataset.ok = '1'; form.submit(); };
        if (window.Swal) {
            Swal.fire({ title: 'Cancel this application?', text: msg, icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#dc2626', confirmButtonText: 'Cancel application', cancelButtonText: 'Keep it' })
                .then(r => r.isConfirmed && go());
        } else if (confirm(msg)) { go(); }
    });
});
</script>
@endpush