@extends('layouts.app')

@section('title', 'Leave Application')
@section('breadcrumbs')
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">My Leaves</span>
    </li>
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">Details</span>
    </li>
@endsection
@section('content')
@php
    $card = 'bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden p-6';
    $h = '-mx-6 -mt-6 mb-6 px-6 py-4 border-b border-gray-200 dark:border-gray-700 text-lg font-semibold text-gray-900 dark:text-white';
    $fmt = fn ($n) => is_null($n) ? '—' : rtrim(rtrim(number_format($n, 3), '0'), '.');

    $steps = [
        ['Submitted',          true,                       $a->created_at],
        ['Credits certified',  (bool) $a->certified_at,    $a->certified_at],
        ['Recommended',        (bool) $a->recommended_at,  $a->recommended_at],
        [$a->status === 'disapproved' ? 'Disapproved' : 'Approved', in_array($a->status, ['approved', 'disapproved']), $a->decided_at],
    ];
    $waiting = [
        'pending'     => 'Waiting for HR to certify your leave credits.',
        'certified'   => 'Waiting for your department head’s recommendation.',
        'recommended' => 'Waiting for the Municipal Mayor’s decision.',
    ][$a->status] ?? null;
@endphp
<div class="py-6">
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-leave.banner :title="$a->leaveType->name" icon="bi-file-earmark-text" :subtitle="'Filed ' . $a->date_of_filing->format('M d, Y')">
        <x-slot:actions>
            <span class="rounded-full px-3 py-1 text-sm font-medium {{ $a->statusBadge() }}">{{ $a->statusLabel() }}</span>
            <x-leave.banner-action :href="route('my-leaves.index')" icon="bi-arrow-left">Back</x-leave.banner-action>
            @if ($a->isEditable())
                <x-leave.banner-action :href="route('my-leaves.edit', $a)" icon="bi-pencil-square">Edit</x-leave.banner-action>
            @endif
            @if ($a->isCancellable())
                <form method="POST" action="{{ route('my-leaves.cancel', $a) }}" onsubmit="return confirm('Cancel this application?');">
                    @csrf
                    <x-leave.banner-action type="submit" icon="bi-x-circle">Cancel application</x-leave.banner-action>
                </form>
            @endif
        </x-slot:actions>
    </x-leave.banner>

    @if (session('success'))<div class="mt-6 rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-800" role="status">{{ session('success') }}</div>@endif

    <div class="mt-6 space-y-6">

        {{-- Progress --}}
        @unless ($a->status === 'cancelled')
        <section class="{{ $card }}">
            <h2 class="{{ $h }}"><i class="bi bi-list-check mr-2 text-indigo-600"></i>Progress</h2>
            <ol class="grid gap-4 sm:grid-cols-4">
                @foreach ($steps as $i => [$label, $done, $when])
                    @php $bad = $i === 3 && $a->status === 'disapproved'; @endphp
                    <li class="flex items-start gap-3 sm:block">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-sm font-semibold
                            {{ $done ? ($bad ? 'bg-red-600 text-white' : 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white') : 'bg-gray-200 text-gray-500 dark:bg-gray-800' }}">
                            @if ($done)<i class="bi {{ $bad ? 'bi-x-lg' : 'bi-check-lg' }}"></i>@else{{ $i + 1 }}@endif
                        </span>
                        <div class="sm:mt-2">
                            <p class="text-sm font-medium {{ $done ? 'text-gray-900 dark:text-gray-100' : 'text-gray-500' }}">{{ $label }}</p>
                            @if ($done && $when)<p class="text-xs text-gray-500">{{ $when->format('M d, Y') }}</p>@endif
                        </div>
                    </li>
                @endforeach
            </ol>
            @if ($waiting)<p class="mt-5 rounded-lg bg-indigo-50 px-4 py-2.5 text-sm text-indigo-900 dark:bg-gray-800 dark:text-indigo-200">{{ $waiting }}</p>@endif
        </section>
        @endunless

        {{-- Outcome --}}
        @if ($a->status === 'approved')
            <section class="{{ $card }} border-green-300">
                <h2 class="{{ $h }}"><i class="bi bi-check-circle mr-2 text-indigo-600"></i>Approved</h2>
                <p class="text-sm">
                    {{ $fmt($a->approved_days_with_pay) }} day(s) with pay
                    @if ($a->approved_days_without_pay) · {{ $fmt($a->approved_days_without_pay) }} day(s) without pay @endif
                    @if ($a->approved_days_others) · {{ $fmt($a->approved_days_others) }} other{{ $a->approved_others_specify ? ' (' . $a->approved_others_specify . ')' : '' }} @endif
                </p>
            </section>
        @elseif ($a->status === 'disapproved')
            <section class="{{ $card }} border-red-300">
                <h2 class="{{ $h }}"><i class="bi bi-x-circle mr-2 text-indigo-600"></i>Disapproved</h2>
                <p class="text-sm">{{ $a->disapproval_reason }}</p>
            </section>
        @endif

        {{-- Details --}}
        <section class="{{ $card }}">
            <h2 class="{{ $h }}"><i class="bi bi-file-earmark-text mr-2 text-indigo-600"></i>Application details</h2>
            <dl class="grid grid-cols-[10rem_1fr] gap-x-4 gap-y-2 text-sm">
                <dt class="text-gray-500">Leave type</dt><dd>{{ $a->leaveType->name }}{{ $a->leave_type_other ? ' — ' . $a->leave_type_other : '' }}</dd>
                <dt class="text-gray-500">Details</dt><dd>{{ $a->detailsSummary() }}</dd>
                <dt class="text-gray-500">Working days</dt><dd>{{ $fmt($a->working_days) }}</dd>
                <dt class="text-gray-500">Inclusive dates</dt>
                <dd>
                    {{ $a->date_from->format('M d, Y') }} – {{ $a->date_to->format('M d, Y') }}
                    <span class="block text-xs text-gray-500">{{ $a->dates->map(fn ($d) => $d->leave_date->format('M d'))->implode(', ') }}</span>
                </dd>
                <dt class="text-gray-500">Commutation</dt><dd>{{ $a->commutation_requested ? 'Requested' : 'Not requested' }}</dd>
                @if ($a->recommendation)
                    <dt class="text-gray-500">Recommendation</dt>
                    <dd>{{ $a->recommendation === 'for_approval' ? 'For approval' : 'For disapproval' }}{{ $a->recommendation_disapproval_reason ? ' — ' . $a->recommendation_disapproval_reason : '' }}</dd>
                @endif
            </dl>
        </section>
    </div>
</div>
</div>
@endsection