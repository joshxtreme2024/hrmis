@extends('layouts.app')
@section('breadcrumbs')
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium"><a href="{{ route('my-leaves.index') }}">My Leaves</a></span>
    </li>
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">Application For Leave</span>
    </li>
@endsection
@section('content')
@php
    $editing = $application->exists;
    $val = function ($key, $default = null) use ($application) {
        $v = old($key, data_get($application, $key, $default));
        return $v instanceof \Carbon\CarbonInterface ? $v->format('Y-m-d') : $v;
    };
    $inp = 'w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100';
    $lbl = 'mb-1.5 block text-sm font-medium text-gray-800 dark:text-gray-200';
    $card = 'rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900';
    $h = 'mb-4 text-base font-bold text-blue-900 dark:text-blue-200';
@endphp
<div class="mx-auto max-w-4xl px-4 py-8">
    <a href="{{ route('my-leaves.index') }}" class="text-sm text-blue-700 hover:underline dark:text-blue-400"><i class="bi bi-arrow-left"></i> Back to my leaves</a>
    <h1 class="mt-2 text-2xl font-bold text-blue-900 dark:text-white">{{ $editing ? 'Edit leave application' : 'Apply for leave' }}</h1>
    <p class="text-sm text-gray-600 dark:text-gray-400">Application for Leave — CS Form No. 6, Revised 2020</p>

    @if ($errors->any())
        <div class="mt-6 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
            <p class="font-semibold">Please fix the following:</p>
            <ul class="mt-1 list-inside list-disc">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ $editing ? route('my-leaves.update', $application) : route('my-leaves.store') }}" class="mt-6 space-y-6">
        @csrf
        @if ($editing) @method('PUT') @endif

        {{-- Applicant (read-only, taken from your employee record) --}}
        <section class="{{ $card }}">
            <h2 class="{{ $h }}">Applicant</h2>
            <dl class="grid grid-cols-[9rem_1fr] gap-x-4 gap-y-2 text-sm">
                <dt class="text-gray-500">Name</dt><dd class="font-medium">{{ \App\Support\EmployeeProfile::name($employee) }}</dd>
                <dt class="text-gray-500">Office/Department</dt><dd>{{ $department?->name ?? '—' }}</dd>
                <dt class="text-gray-500">Position</dt><dd>{{ \App\Support\EmployeeProfile::position($employee) ?: '—' }}</dd>
                <dt class="text-gray-500">Date of filing</dt><dd>{{ ($application->date_of_filing ?? now())->format('M d, Y') }}</dd>
                <dt class="text-gray-500">Leave balance</dt>
                <dd>
                    @foreach ($balances as $code => $b)
                        <span class="mr-4">{{ $b['name'] }}: <strong>{{ is_null($b['balance']) ? 'no record' : rtrim(rtrim(number_format($b['balance'], 3), '0'), '.') }}</strong></span>
                    @endforeach
                </dd>
            </dl>
            <p class="mt-3 text-xs text-gray-500">These details come from your HR record. Contact HR if something is wrong.</p>
        </section>

        {{-- 6.A Type of leave --}}
        <section class="{{ $card }}">
            <h2 class="{{ $h }}">6.A Type of leave to be availed of</h2>
            <label for="leave_type_id" class="sr-only">Leave type</label>
            <select id="leave_type_id" name="leave_type_id" required class="{{ $inp }}">
                <option value="">Select leave type…</option>
                @foreach ($leaveTypes as $t)
                    <option value="{{ $t->id }}" data-code="{{ $t->code }}" @selected($val('leave_type_id') == $t->id)>{{ $t->name }}</option>
                @endforeach
            </select>
            <div class="mt-4" data-show-for="OTHER">
                <label for="leave_type_other" class="{{ $lbl }}">Others — specify</label>
                <input id="leave_type_other" name="leave_type_other" type="text" value="{{ $val('leave_type_other') }}" class="{{ $inp }}">
            </div>
        </section>

        {{-- 6.B Details --}}
        <section class="{{ $card }}">
            <h2 class="{{ $h }}">6.B Details of leave</h2>
            <p id="noDetails" class="text-sm text-gray-500">Select a leave type to see its details.</p>

            <div class="space-y-5">
                <fieldset data-show-for="VL,SPL">
                    <legend class="{{ $lbl }}">In case of Vacation / Special Privilege Leave</legend>
                    <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm dark:text-gray-200">
                        <label class="flex items-center gap-2"><input type="radio" name="vacation_location" value="within_philippines" class="accent-blue-700" @checked($val('vacation_location') === 'within_philippines')> Within the Philippines</label>
                        <label class="flex items-center gap-2"><input type="radio" name="vacation_location" value="abroad" class="accent-blue-700" @checked($val('vacation_location') === 'abroad')> Abroad</label>
                    </div>
                    <input name="vacation_location_specify" type="text" placeholder="Specify place / country" value="{{ $val('vacation_location_specify') }}" class="{{ $inp }} mt-3">
                </fieldset>

                <fieldset data-show-for="SL">
                    <legend class="{{ $lbl }}">In case of Sick Leave</legend>
                    <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm dark:text-gray-200">
                        <label class="flex items-center gap-2"><input type="radio" name="sick_setting" value="in_hospital" class="accent-blue-700" @checked($val('sick_setting') === 'in_hospital')> In hospital</label>
                        <label class="flex items-center gap-2"><input type="radio" name="sick_setting" value="out_patient" class="accent-blue-700" @checked($val('sick_setting') === 'out_patient')> Out patient</label>
                    </div>
                </fieldset>

                <div data-show-for="SL,SLBW">
                    <label for="illness_specify" class="{{ $lbl }}">Specify illness</label>
                    <input id="illness_specify" name="illness_specify" type="text" value="{{ $val('illness_specify') }}" class="{{ $inp }}">
                </div>

                <fieldset data-show-for="STUDY">
                    <legend class="{{ $lbl }}">In case of Study Leave</legend>
                    <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm dark:text-gray-200">
                        <label class="flex items-center gap-2"><input type="radio" name="study_purpose" value="masters_degree" class="accent-blue-700" @checked($val('study_purpose') === 'masters_degree')> Completion of Master's Degree</label>
                        <label class="flex items-center gap-2"><input type="radio" name="study_purpose" value="bar_board_review" class="accent-blue-700" @checked($val('study_purpose') === 'bar_board_review')> BAR/Board Examination Review</label>
                    </div>
                </fieldset>

                <fieldset data-show-for="*">
                    <legend class="{{ $lbl }}">Other purpose <span class="font-normal text-gray-500">(optional)</span></legend>
                    <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm dark:text-gray-200">
                        <label class="flex items-center gap-2"><input type="radio" name="other_purpose" value="" class="accent-blue-700" @checked(! $val('other_purpose'))> None</label>
                        <label class="flex items-center gap-2"><input type="radio" name="other_purpose" value="monetization" class="accent-blue-700" @checked($val('other_purpose') === 'monetization')> Monetization of Leave Credits</label>
                        <label class="flex items-center gap-2"><input type="radio" name="other_purpose" value="terminal_leave" class="accent-blue-700" @checked($val('other_purpose') === 'terminal_leave')> Terminal Leave</label>
                    </div>
                </fieldset>
            </div>
        </section>

        {{-- 6.C / 6.D --}}
        <section class="{{ $card }}">
            <h2 class="{{ $h }}">6.C Working days &amp; inclusive dates</h2>
            <div class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label for="date_from" class="{{ $lbl }}">From</label>
                    <input id="date_from" name="date_from" type="date" required value="{{ $val('date_from') }}" class="{{ $inp }}">
                </div>
                <div>
                    <label for="date_to" class="{{ $lbl }}">To</label>
                    <input id="date_to" name="date_to" type="date" required value="{{ $val('date_to') }}" class="{{ $inp }}">
                </div>
                <div>
                    <label for="working_days" class="{{ $lbl }}">Number of working days</label>
                    <input id="working_days" name="working_days" type="number" step="0.5" min="0.5" required value="{{ $val('working_days') }}" class="{{ $inp }}">
                    <p class="mt-1 text-xs text-gray-500">Auto-counted Mon–Fri; edit for half days.</p>
                </div>
            </div>
        </section>

        <section class="{{ $card }}">
            <h2 class="{{ $h }}">6.D Commutation</h2>
            <label class="mt-5 flex items-center gap-2 text-sm dark:text-gray-200">
                <input type="checkbox" name="commutation_requested" value="1" class="h-4 w-4 rounded accent-blue-700" @checked($val('commutation_requested'))>
                Commutation requested (leave unchecked for “Not Requested”)
            </label>
        </section>

        <div class="flex justify-end gap-3">
            <a href="{{ route('my-leaves.index') }}" class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800">Cancel</a>
            <button type="submit" class="rounded-lg bg-blue-700 px-6 py-2.5 text-sm font-semibold text-white hover:bg-blue-600">{{ $editing ? 'Save changes' : 'Submit application' }}</button>
        </div>
    </form>
</div>

<script>
(function () {
    var type = document.getElementById('leave_type_id');
    var emp = document.getElementById('emp_id');
    var from = document.getElementById('date_from');
    var to = document.getElementById('date_to');
    var days = document.getElementById('working_days');
    var daysTouched = !!days.value;

    // Show only the 6.B groups that belong to the selected leave type
    function syncDetails() {
        var opt = type.options[type.selectedIndex];
        var code = opt ? opt.dataset.code : '';
        var any = false;
        document.querySelectorAll('[data-show-for]').forEach(function (el) {
            var codes = el.dataset.showFor.split(',');
            var show = !!code && (codes.indexOf('*') > -1 || codes.indexOf(code) > -1);
            el.classList.toggle('hidden', !show);
            el.querySelectorAll('input,select').forEach(function (i) { i.disabled = !show; });
            if (show) any = true;
        });
        document.getElementById('noDetails').classList.toggle('hidden', any);
    }

    // Count Mon-Fri days between two dates, inclusive
    function countWeekdays() {
        if (!from.value || !to.value || to.value < from.value) return;
        var d = new Date(from.value + 'T00:00:00'), end = new Date(to.value + 'T00:00:00'), n = 0;
        while (d <= end) { var w = d.getDay(); if (w !== 0 && w !== 6) n++; d.setDate(d.getDate() + 1); }
        if (!daysTouched) days.value = n;
    }

    if (emp) emp.addEventListener('change', function () {
        var o = emp.options[emp.selectedIndex];
        if (!o || !o.value) return;
        if (o.dataset.dept) document.getElementById('department_id').value = o.dataset.dept;
        if (o.dataset.position) document.getElementById('position').value = o.dataset.position;
        if (o.dataset.salary) document.getElementById('salary').value = o.dataset.salary;
    });

    type.addEventListener('change', syncDetails);
    from.addEventListener('change', function () { if (!to.value || to.value < from.value) to.value = from.value; countWeekdays(); });
    to.addEventListener('change', countWeekdays);
    days.addEventListener('input', function () { daysTouched = true; });
    syncDetails();
})();
</script>
@endsection