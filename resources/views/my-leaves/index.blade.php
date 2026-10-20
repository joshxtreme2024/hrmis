@extends('layouts.app')

@section('title', 'My Leaves')
@section('breadcrumbs')
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">My Leaves</span>
    </li>
@endsection
@section('content')
@php $fmt = fn ($n) => is_null($n) ? '—' : rtrim(rtrim(number_format($n, 3), '0'), '.'); @endphp
<div class="py-6">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <x-leave.banner title="My Leaves" icon="bi-calendar2-heart" subtitle="Your leave credits and applications.">
        <x-slot:actions>
            <x-leave.banner-action :href="route('my-leaves.create')" icon="bi-plus-lg">Apply for leave</x-leave.banner-action>
        </x-slot:actions>
    </x-leave.banner>

    @if (session('success'))<div class="mt-6 rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-800" role="status">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="mt-6 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">{{ session('error') }}</div>@endif

    {{-- Balances --}}
    @php
        $vl = $balances['VL']; $sl = $balances['SL'];
        $hintFor = fn ($b) => is_null($b['earned']) ? 'No credit record yet. Ask HR.' : 'Earned ' . $fmt($b['earned']) . ' · used ' . $fmt($b['used']);
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
        <x-leave.stat label="Vacation Leave" :value="$fmt($vl['balance'])" unit="days" :hint="$hintFor($vl)" icon="bi-sun" gradient="bg-gradient-to-r from-[#0e7490] via-[#3b82f6] to-[#4f46e5]" />
        <x-leave.stat label="Sick Leave" :value="$fmt($sl['balance'])" unit="days" :hint="$hintFor($sl)" icon="bi-heart-pulse" gradient="bg-gradient-to-bl from-[#84cc16] via-[#16a34a] to-[#0f766e]" iconBg="bg-green-100" iconColor="text-green-600" />
        <x-leave.stat label="In Progress" :value="($counts['pending'] ?? 0) + ($counts['certified'] ?? 0) + ($counts['recommended'] ?? 0)" icon="bi-hourglass-split" gradient="bg-gradient-to-tl from-[#1e293b] via-[#6366f1] to-[#71717a]" iconBg="bg-purple-100" iconColor="text-purple-600" />
        <x-leave.stat label="Approved" :value="$counts['approved'] ?? 0" icon="bi-check2-circle" gradient="bg-[radial-gradient(ellipse_at_bottom_left,_var(--tw-gradient-stops))] from-[#ea580c] via-[#fb923c] to-[#fed7aa]" iconBg="bg-amber-100" iconColor="text-amber-600" />
    </div>

    {{-- Status tabs --}}
    <div class="mt-8 flex flex-wrap gap-2">
        @php $tabs = ['' => 'All'] + \App\Models\LeaveApplication::STATUSES; @endphp
        @foreach ($tabs as $key => $label)
            @continue($key === 'draft')
            @php $active = (string) request('status') === (string) $key; @endphp
            <a href="{{ route('my-leaves.index', $key === '' ? [] : ['status' => $key]) }}"
               class="rounded-full border px-3 py-1 text-sm {{ $active ? 'border-indigo-600 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white' : 'border-gray-300 text-gray-700 hover:bg-indigo-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                {{ $label }}@if ($key !== '' && ($counts[$key] ?? 0) > 0)<span class="ml-1 text-xs opacity-80">{{ $counts[$key] }}</span>@endif
            </a>
        @endforeach
    </div>

    <div class="mt-4 overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-gray-200 bg-gray-50 text-xs uppercase tracking-wide text-gray-600 dark:border-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                <tr>
                    <th class="px-4 py-3 font-semibold">Filed</th>
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
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $a->leaveType->name }}{{ $a->leave_type_other ? ' — ' . $a->leave_type_other : '' }}</td>
                        <td class="whitespace-nowrap px-4 py-3">
                            {{ $a->date_from->format('M d') }}{{ $a->date_from->ne($a->date_to) ? ' – ' . $a->date_to->format('M d, Y') : ', ' . $a->date_to->format('Y') }}
                        </td>
                        <td class="px-4 py-3 text-right tabular-nums">{{ $fmt($a->working_days) }}</td>
                        <td class="whitespace-nowrap px-4 py-3"><span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $a->statusBadge() }}">{{ $a->statusLabel() }}</span></td>
                        <td class="whitespace-nowrap px-4 py-3 text-right">
                            <a href="{{ route('my-leaves.show', $a) }}" class="rounded p-1.5 text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-gray-800" aria-label="View"><i class="bi bi-eye"></i></a>
                            @if ($a->isEditable())
                                <a href="{{ route('my-leaves.edit', $a) }}" class="rounded p-1.5 text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-gray-800" aria-label="Edit"><i class="bi bi-pencil-square"></i></a>
                            @endif
                            @if ($a->isCancellable())
                                <form method="POST" action="{{ route('my-leaves.cancel', $a) }}" class="inline" onsubmit="return confirm('Cancel this application?');">
                                    @csrf
                                    <button class="rounded p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-gray-800" aria-label="Cancel application"><i class="bi bi-x-circle"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">You have no leave applications yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $applications->links() }}</div>
</div>
</div>
@endsection