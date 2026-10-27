@extends('layouts.app')

@section('title', 'Leave Application')
@section('breadcrumbs')
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">Leave Management</span>
    </li>
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium"><a href="{{ route('leave-applications.index') }}" class="hover:text-indigo-600">Leave Applications</a></span>
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
    $inp = 'w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors';
    $lbl = 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1';
    $btn = 'inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg';
    $fmt = fn ($n) => is_null($n) ? '—' : rtrim(rtrim(number_format($n, 3), '0'), '.');
    $dl = 'grid grid-cols-[10rem_1fr] gap-x-4 gap-y-2 text-sm';
@endphp
<div class="py-6">
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-leave.banner :title="$a->employeeName()" icon="bi-file-earmark-person" :subtitle="$a->leaveType->name . ' · filed ' . $a->date_of_filing->format('M d, Y')">
        <x-slot:actions>
            <span class="rounded-full px-3 py-1 text-sm font-medium {{ $a->statusBadge() }}">{{ $a->statusLabel() }}</span>
            <x-leave.banner-action :href="route('leave-applications.index')" icon="bi-arrow-left">Back</x-leave.banner-action>
            @if ($a->isEditable())
                <x-leave.banner-action :href="route('leave-applications.edit', $a)" icon="bi-pencil-square">Edit</x-leave.banner-action>
            @endif
            @if ($a->isCancellable())
                <form method="POST" action="{{ route('leave-applications.cancel', $a) }}" onsubmit="return confirm('Cancel this application?');">
                    @csrf
                    <x-leave.banner-action type="submit" icon="bi-x-circle">Cancel application</x-leave.banner-action>
                </form>
            @endif
        </x-slot:actions>
    </x-leave.banner>

    @if (session('success'))<div class="mt-6 rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-800" role="status">{{ session('success') }}</div>@endif
    @if ($errors->any())
        <div class="mt-6 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
            <ul class="list-inside list-disc">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="mt-6 space-y-6">

        {{-- Application details --}}
        <section class="{{ $card }}">
            <h2 class="{{ $h }}"><i class="bi bi-file-earmark-text mr-2 text-indigo-600"></i>Application details</h2>
            <dl class="{{ $dl }}">
                <dt class="text-gray-500">Office/Department</dt><dd>{{ $a->department?->name ?? '—' }}</dd>
                <dt class="text-gray-500">Position</dt><dd>{{ $a->position }}</dd>
                <dt class="text-gray-500">Salary</dt><dd>{{ $a->salary ? '₱' . number_format($a->salary, 2) : '—' }}</dd>
                <dt class="text-gray-500">Leave type</dt><dd>{{ $a->leaveType->name }}{{ $a->leave_type_other ? ' — ' . $a->leave_type_other : '' }}</dd>
                <dt class="text-gray-500">Details</dt><dd>{{ $a->detailsSummary() }}</dd>
                <dt class="text-gray-500">Working days</dt><dd>{{ $fmt($a->working_days) }}</dd>
                <dt class="text-gray-500">Inclusive dates</dt>
                <dd>
                    {{ $a->date_from->format('M d, Y') }} – {{ $a->date_to->format('M d, Y') }}
                    <span class="block text-xs text-gray-500">{{ $a->dates->map(fn ($d) => $d->leave_date->format('M d'))->implode(', ') }}</span>
                </dd>
                <dt class="text-gray-500">Commutation</dt><dd>{{ $a->commutation_requested ? 'Requested' : 'Not requested' }}</dd>
            </dl>
        </section>

        {{-- 7.A --}}
        <section class="{{ $card }}">
            <h2 class="{{ $h }}"><i class="bi bi-clipboard-check mr-2 text-indigo-600"></i>7.A Certification of leave credits</h2>
            @if ($a->status === 'pending' && $can['certify'])
                <form method="POST" action="{{ route('leave-applications.certify', $a) }}" class="space-y-4">
                    @csrf
                    <p class="text-sm text-gray-600 dark:text-gray-400">Prefilled from the employee's current balance{{ $creditsAsOf ? ' (as of ' . \Carbon\Carbon::parse($creditsAsOf)->format('M d, Y') . ')' : '' }}. Adjust if needed.</p>
                    <div class="max-w-xs">
                        <label class="{{ $lbl }}" for="credits_as_of">As of</label>
                        <input id="credits_as_of" type="date" name="credits_as_of" required value="{{ old('credits_as_of', now()->toDateString()) }}" class="{{ $inp }}">
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach (['vl' => ['Vacation Leave', $vlBalance, $a->leaveType->code === 'VL'], 'sl' => ['Sick Leave', $slBalance, $a->leaveType->code === 'SL']] as $k => [$name, $bal, $applies])
                            <fieldset class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                                <legend class="px-1 text-sm font-semibold">{{ $name }}</legend>
                                <label class="{{ $lbl }}" for="{{ $k }}_total_earned">Total earned</label>
                                <input id="{{ $k }}_total_earned" type="number" step="0.001" min="0" name="{{ $k }}_total_earned" required value="{{ old($k . '_total_earned', $bal) }}" class="{{ $inp }}">
                                <label class="{{ $lbl }} mt-3" for="{{ $k }}_less">Less this application</label>
                                <input id="{{ $k }}_less" type="number" step="0.001" min="0" name="{{ $k }}_less_this_application" required value="{{ old($k . '_less_this_application', $applies ? $a->working_days : 0) }}" class="{{ $inp }}">
                            </fieldset>
                        @endforeach
                    </div>
                    <button class="{{ $btn }}">Certify credits</button>
                </form>
            @elseif ($a->certified_at)
                <table class="w-full max-w-md text-sm">
                    <thead><tr class="text-left text-gray-500"><th></th><th class="py-1">Vacation</th><th class="py-1">Sick</th></tr></thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr><td class="py-1.5">Total earned</td><td>{{ $fmt($a->vl_total_earned) }}</td><td>{{ $fmt($a->sl_total_earned) }}</td></tr>
                        <tr><td class="py-1.5">Less this application</td><td>{{ $fmt($a->vl_less_this_application) }}</td><td>{{ $fmt($a->sl_less_this_application) }}</td></tr>
                        <tr class="font-semibold"><td class="py-1.5">Balance</td><td>{{ $fmt($a->vl_balance) }}</td><td>{{ $fmt($a->sl_balance) }}</td></tr>
                    </tbody>
                </table>
                <p class="mt-3 text-xs text-gray-500">As of {{ $a->credits_as_of?->format('M d, Y') }} · certified by {{ $a->certifier?->name ?? '—' }} on {{ $a->certified_at->format('M d, Y') }}</p>
            @else
                <p class="text-sm text-gray-500">Not certified.</p>
            @endif
        </section>

        {{-- 7.B --}}
        <section class="{{ $card }}">
            <h2 class="{{ $h }}"><i class="bi bi-chat-square-text mr-2 text-indigo-600"></i>7.B Recommendation</h2>
            @if ($a->status === 'certified' && $can['recommend'])
                <form method="POST" action="{{ route('leave-applications.recommend', $a) }}" class="space-y-3">
                    @csrf
                    <div class="flex flex-wrap gap-6 text-sm dark:text-gray-200">
                        <label class="flex items-center gap-2"><input type="radio" name="recommendation" value="for_approval" class="accent-indigo-600" required @checked(old('recommendation') === 'for_approval')> For approval</label>
                        <label class="flex items-center gap-2"><input type="radio" name="recommendation" value="for_disapproval" class="accent-indigo-600" @checked(old('recommendation') === 'for_disapproval')> For disapproval due to</label>
                    </div>
                    <textarea name="recommendation_disapproval_reason" rows="2" placeholder="Reason (required for disapproval)" class="{{ $inp }}">{{ old('recommendation_disapproval_reason') }}</textarea>
                    <button class="{{ $btn }}">Record recommendation</button>
                </form>
            @elseif ($a->recommended_at)
                <p class="text-sm">{{ $a->recommendation === 'for_approval' ? 'For approval' : 'For disapproval' }}
                    @if ($a->recommendation_disapproval_reason) — {{ $a->recommendation_disapproval_reason }}@endif</p>
                <p class="mt-2 text-xs text-gray-500">By {{ $a->recommender?->name ?? '—' }} on {{ $a->recommended_at->format('M d, Y') }}</p>
            @else
                <p class="text-sm text-gray-500">Awaiting recommendation.</p>
            @endif
        </section>

        {{-- 7.C / 7.D --}}
        <section class="{{ $card }}">
            <h2 class="{{ $h }}"><i class="bi bi-patch-check mr-2 text-indigo-600"></i>7.C Approved for · 7.D Disapproved due to</h2>
            @if ($a->status === 'recommended' && $can['decide'])
                <div class="grid gap-6 md:grid-cols-2">
                    <form method="POST" action="{{ route('leave-applications.decide', $a) }}" class="space-y-3">
                        @csrf <input type="hidden" name="decision" value="approve">
                        <p class="text-sm font-semibold text-green-800 dark:text-green-400">Approve</p>
                        <div><label class="{{ $lbl }}" for="dwp">Days with pay</label>
                            <input id="dwp" type="number" step="0.5" min="0" name="approved_days_with_pay" required value="{{ old('approved_days_with_pay', $a->working_days) }}" class="{{ $inp }}"></div>
                        <div><label class="{{ $lbl }}" for="dwop">Days without pay</label>
                            <input id="dwop" type="number" step="0.5" min="0" name="approved_days_without_pay" value="{{ old('approved_days_without_pay') }}" class="{{ $inp }}"></div>
                        <div><label class="{{ $lbl }}" for="dot">Others (days)</label>
                            <input id="dot" type="number" step="0.5" min="0" name="approved_days_others" value="{{ old('approved_days_others') }}" class="{{ $inp }}">
                            <input type="text" name="approved_others_specify" placeholder="Specify" value="{{ old('approved_others_specify') }}" class="{{ $inp }} mt-2"></div>
                        <button class="rounded-lg bg-green-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-600">Approve application</button>
                    </form>
                    <form method="POST" action="{{ route('leave-applications.decide', $a) }}" class="space-y-3">
                        @csrf <input type="hidden" name="decision" value="disapprove">
                        <p class="text-sm font-semibold text-red-800 dark:text-red-400">Disapprove</p>
                        <label class="{{ $lbl }}" for="dr">Reason</label>
                        <textarea id="dr" name="disapproval_reason" rows="5" required class="{{ $inp }}">{{ old('disapproval_reason') }}</textarea>
                        <button class="rounded-lg bg-red-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-600">Disapprove application</button>
                    </form>
                </div>
            @elseif ($a->status === 'approved')
                <dl class="{{ $dl }}">
                    <dt class="text-gray-500">Days with pay</dt><dd>{{ $fmt($a->approved_days_with_pay) }}</dd>
                    <dt class="text-gray-500">Days without pay</dt><dd>{{ $fmt($a->approved_days_without_pay) }}</dd>
                    <dt class="text-gray-500">Others</dt><dd>{{ $fmt($a->approved_days_others) }}{{ $a->approved_others_specify ? ' (' . $a->approved_others_specify . ')' : '' }}</dd>
                </dl>
                <p class="mt-3 text-xs text-gray-500">Approved by the {{ $a->approver?->getPositionTitleAttribute() ?? '—' }} on {{ $a->decided_at?->format('M d, Y') }}</p>
            @elseif ($a->status === 'disapproved')
                <p class="text-sm">{{ $a->disapproval_reason }}</p>
                <p class="mt-3 text-xs text-gray-500">Disapproved by {{ $a->approver?->first_name ?? '—' }} on {{ $a->decided_at?->format('M d, Y') }}</p>
            @else
                <p class="text-sm text-gray-500">Awaiting decision.</p>
            @endif
        </section>
    </div>
</div>
</div>
@endsection