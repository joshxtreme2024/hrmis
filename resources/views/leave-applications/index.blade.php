@extends('layouts.app')

@section('title', 'Leave Applications')
@section('breadcrumbs')
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">Leave Management</span>
    </li>
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">Leave Applications</span>
    </li>
@endsection
@section('content')
@php
    $field = 'w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors';
@endphp
<div class="py-6">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <x-leave.banner title="Leave Requests" icon="bi-calendar2-check" subtitle="CS Form No. 6 applications for the Municipality of Aguinaldo." />

    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
        <x-leave.stat label="Awaiting HR" :value="$counts['pending'] ?? 0" icon="bi-hourglass-split" gradient="bg-gradient-to-r from-[#0e7490] via-[#3b82f6] to-[#4f46e5]" />
        <x-leave.stat label="In Review" :value="($counts['certified'] ?? 0) + ($counts['recommended'] ?? 0)" icon="bi-eye" gradient="bg-gradient-to-bl from-[#84cc16] via-[#16a34a] to-[#0f766e]" iconBg="bg-green-100" iconColor="text-green-600" />
        <x-leave.stat label="Approved" :value="$counts['approved'] ?? 0" icon="bi-check2-circle" gradient="bg-gradient-to-tl from-[#1e293b] via-[#6366f1] to-[#71717a]" iconBg="bg-purple-100" iconColor="text-purple-600" />
        <x-leave.stat label="Disapproved" :value="$counts['disapproved'] ?? 0" icon="bi-x-circle" gradient="bg-[radial-gradient(ellipse_at_bottom_left,_var(--tw-gradient-stops))] from-[#ea580c] via-[#fb923c] to-[#fed7aa]" iconBg="bg-amber-100" iconColor="text-amber-600" />
    </div>

    @if (session('success'))
        <div class="mt-6 rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-800" role="status">{{ session('success') }}</div>
    @endif

    {{-- Status tabs --}}
    <div class="mt-6 flex flex-wrap gap-2">
        @php $tabs = ['' => 'All'] + \App\Models\LeaveApplication::STATUSES; @endphp
        @foreach ($tabs as $key => $label)
            @php $active = (string) request('status') === (string) $key; @endphp
            <a href="{{ route('leave-applications.index', array_merge(request()->except('status', 'page'), $key === '' ? [] : ['status' => $key])) }}"
               class="rounded-full border px-3 py-1 text-sm {{ $active ? 'border-indigo-600 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white' : 'border-gray-300 text-gray-700 hover:bg-indigo-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                {{ $label }}
                @if ($key !== '' && ($counts[$key] ?? 0) > 0)<span class="ml-1 text-xs opacity-80">{{ $counts[$key] }}</span>@endif
            </a>
        @endforeach
    </div>

    {{-- Filters --}}
    <form method="GET" class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-[1.4fr_1fr_auto_auto_auto]">
        @if (request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search employee name" class="{{ $field }}" aria-label="Search employee">
        <select name="leave_type_id" class="{{ $field }}" aria-label="Leave type">
            <option value="">All leave types</option>
            @foreach ($leaveTypes as $t)
                <option value="{{ $t->id }}" @selected(request('leave_type_id') == $t->id)>{{ $t->name }}</option>
            @endforeach
        </select>
        <input type="date" name="from" value="{{ request('from') }}" class="{{ $field }}" aria-label="Leave starts on or after">
        <input type="date" name="to" value="{{ request('to') }}" class="{{ $field }}" aria-label="Leave ends on or before">
        <button class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800">Filter</button>
    </form>

    <div class="mt-6 overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-gray-200 bg-gray-50 text-xs uppercase tracking-wide text-gray-600 dark:border-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                <tr>
                    <th class="px-4 py-3 font-semibold">Filed</th>
                    <th class="px-4 py-3 font-semibold">Employee</th>
                    <th class="px-4 py-3 font-semibold">Leave type</th>
                    <th class="px-4 py-3 font-semibold">Inclusive dates</th>
                    <th class="px-4 py-3 text-right font-semibold">Days</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                    <th class="px-4 py-3 text-right font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($applications as $a)
                    <tr>
                        <td class="whitespace-nowrap px-4 py-3 text-gray-600 dark:text-gray-400">{{ $a->date_of_filing->format('M d, Y') }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900 dark:text-gray-100">{{ $a->employeeName() }}</p>
                            <p class="text-xs text-gray-500">{{ $a->department?->name ?? $a->position }}</p>
                        </td>
                        <td class="px-4 py-3">{{ $a->leaveType->name }}{{ $a->leave_type_other ? ' — ' . $a->leave_type_other : '' }}</td>
                        <td class="whitespace-nowrap px-4 py-3">
                            {{ $a->date_from->format('M d') }}{{ $a->date_from->ne($a->date_to) ? ' – ' . $a->date_to->format('M d, Y') : ', ' . $a->date_to->format('Y') }}
                        </td>
                        <td class="px-4 py-3 text-right tabular-nums">{{ rtrim(rtrim(number_format($a->working_days, 2), '0'), '.') }}</td>
                        <td class="whitespace-nowrap px-4 py-3">
                            <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $a->statusBadge() }}">{{ $a->statusLabel() }}</span>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 text-right">
                            <a href="{{ route('leave-applications.show', $a) }}" class="rounded p-1.5 text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-gray-800" aria-label="View"><i class="bi bi-eye"></i></a>
                            @if ($a->isEditable())
                                <a href="{{ route('leave-applications.edit', $a) }}" class="rounded p-1.5 text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-gray-800" aria-label="Edit"><i class="bi bi-pencil-square"></i></a>
                            @endif
                            @if ($a->isDeletable())
                                <form method="POST" action="{{ route('leave-applications.destroy', $a) }}" class="inline" onsubmit="return confirm('Delete this application?');">
                                    @csrf @method('DELETE')
                                    <button class="rounded p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-gray-800" aria-label="Delete"><i class="bi bi-trash3"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-12 text-center text-gray-500">No leave applications found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $applications->links() }}</div>
</div>
</div>
@endsection