{{-- Shared create / edit form for CS Form No. 6, sections 1-6 --}}
@extends('layouts.app')

@section('title', 'Leave Application')
@section('breadcrumbs')
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">Leave Management</span>
    </li>
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">Leave Applications</span>
    </li>
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">Application</span>
    </li>
@endsection
@section('content')
@php
    $editing = $application->exists;
    $val = function ($key, $default = null) use ($application) {
        $v = old($key, data_get($application, $key, $default));
        return $v instanceof \Carbon\CarbonInterface ? $v->format('Y-m-d') : $v;
    };
    $inp = 'w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors';
    $lbl = 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1';
    $card = 'bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden p-6';
    $h = '-mx-6 -mt-6 mb-6 px-6 py-4 border-b border-gray-200 dark:border-gray-700 text-lg font-semibold text-gray-900 dark:text-white';
@endphp
<div class="py-6">
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-leave.banner :title="$editing ? 'Edit leave application' : 'New leave application'" icon="bi-file-earmark-text" subtitle="Application for Leave — CS Form No. 6, Revised 2020">
        <x-slot:actions>
            <x-leave.banner-action :href="route('leave-applications.index')" icon="bi-arrow-left">Back</x-leave.banner-action>
        </x-slot:actions>
    </x-leave.banner>

    @if ($errors->any())
        <div class="mt-6 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
            <p class="font-semibold">Please fix the following:</p>
            <ul class="mt-1 list-inside list-disc">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ $editing ? route('leave-applications.update', $application) : route('leave-applications.store') }}" class="mt-6 space-y-6">
        @csrf
        @if ($editing) @method('PUT') @endif

        {{-- Items 1-5 --}}
        <section class="{{ $card }}">
            <h2 class="{{ $h }}"><i class="bi bi-person-badge mr-2 text-indigo-600"></i>Applicant <span class="font-normal text-gray-500">(items 1–5)</span></h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="emp_id" class="{{ $lbl }}">Employee (2. Name)</label>
                    <select id="emp_id" name="emp_id" required class="{{ $inp }}">
                        <option value="">Select employee…</option>
                        @foreach ($employees as $e)
                            <option value="{{ $e['id'] }}" data-dept="{{ $e['department_id'] }}" data-position="{{ $e['position'] }}" data-salary="{{ $e['salary'] }}"
                                    @selected($val('emp_id') == $e['id'])>{{ $e['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="department_id" class="{{ $lbl }}">1. Office/Department</label>
                    <select id="department_id" name="department_id" class="{{ $inp }}">
                        <option value="">—</option>
                        @foreach ($departments as $d)
                            <option value="{{ $d->id }}" @selected($val('department_id') == $d->id)>{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="date_of_filing" class="{{ $lbl }}">3. Date of filing</label>
                    <input id="date_of_filing" name="date_of_filing" type="date" required value="{{ $val('date_of_filing') }}" class="{{ $inp }}">
                </div>
                <div>
                    <label for="position" class="{{ $lbl }}">4. Position</label>
                    <input id="position" name="position" type="text" required value="{{ $val('position') }}" class="{{ $inp }}">
                </div>
                <div>
                    <label for="salary" class="{{ $lbl }}">5. Salary (monthly)</label>
                    <input id="salary" name="salary" type="number" step="0.01" min="0" value="{{ $val('salary') }}" class="{{ $inp }}">
                </div>
            </div>
        </section>

        {{-- 6.A Type of leave --}}
        <section class="{{ $card }}">
            <h2 class="{{ $h }}"><i class="bi bi-tags mr-2 text-indigo-600"></i>6.A Type of leave to be availed of</h2>
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
            <h2 class="{{ $h }}"><i class="bi bi-card-checklist mr-2 text-indigo-600"></i>6.B Details of leave</h2>
            <p id="noDetails" class="text-sm text-gray-500">Select a leave type to see its details.</p>

            <div class="space-y-5">
                <fieldset data-show-for="VL,SPL">
                    <legend class="{{ $lbl }}">In case of Vacation / Special Privilege Leave</legend>
                    <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm dark:text-gray-200">
                        <label class="flex items-center gap-2"><input type="radio" name="vacation_location" value="within_philippines" class="accent-indigo-600" @checked($val('vacation_location') === 'within_philippines')> Within the Philippines</label>
                        <label class="flex items-center gap-2"><input type="radio" name="vacation_location" value="abroad" class="accent-indigo-600" @checked($val('vacation_location') === 'abroad')> Abroad</label>
                    </div>
                    <input name="vacation_location_specify" type="text" placeholder="Specify place / country" value="{{ $val('vacation_location_specify') }}" class="{{ $inp }} mt-3">
                </fieldset>

                <fieldset data-show-for="SL">
                    <legend class="{{ $lbl }}">In case of Sick Leave</legend>
                    <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm dark:text-gray-200">
                        <label class="flex items-center gap-2"><input type="radio" name="sick_setting" value="in_hospital" class="accent-indigo-600" @checked($val('sick_setting') === 'in_hospital')> In hospital</label>
                        <label class="flex items-center gap-2"><input type="radio" name="sick_setting" value="out_patient" class="accent-indigo-600" @checked($val('sick_setting') === 'out_patient')> Out patient</label>
                    </div>
                </fieldset>

                <div data-show-for="SL,SLBW">
                    <label for="illness_specify" class="{{ $lbl }}">Specify illness</label>
                    <input id="illness_specify" name="illness_specify" type="text" value="{{ $val('illness_specify') }}" class="{{ $inp }}">
                </div>

                <fieldset data-show-for="STUDY">
                    <legend class="{{ $lbl }}">In case of Study Leave</legend>
                    <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm dark:text-gray-200">
                        <label class="flex items-center gap-2"><input type="radio" name="study_purpose" value="masters_degree" class="accent-indigo-600" @checked($val('study_purpose') === 'masters_degree')> Completion of Master's Degree</label>
                        <label class="flex items-center gap-2"><input type="radio" name="study_purpose" value="bar_board_review" class="accent-indigo-600" @checked($val('study_purpose') === 'bar_board_review')> BAR/Board Examination Review</label>
                    </div>
                </fieldset>

                <fieldset data-show-for="*">
                    <legend class="{{ $lbl }}">Other purpose <span class="font-normal text-gray-500">(optional)</span></legend>
                    <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm dark:text-gray-200">
                        <label class="flex items-center gap-2"><input type="radio" name="other_purpose" value="" class="accent-indigo-600" @checked(! $val('other_purpose'))> None</label>
                        <label class="flex items-center gap-2"><input type="radio" name="other_purpose" value="monetization" class="accent-indigo-600" @checked($val('other_purpose') === 'monetization')> Monetization of Leave Credits</label>
                        <label class="flex items-center gap-2"><input type="radio" name="other_purpose" value="terminal_leave" class="accent-indigo-600" @checked($val('other_purpose') === 'terminal_leave')> Terminal Leave</label>
                    </div>
                </fieldset>
            </div>
        </section>

        {{-- 6.C / 6.D --}}
        <section class="{{ $card }}">
            <h2 class="{{ $h }}"><i class="bi bi-calendar-range mr-2 text-indigo-600"></i>6.C Working days &amp; inclusive dates · 6.D Commutation</h2>
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
            <label class="mt-5 flex items-center gap-2 text-sm dark:text-gray-200">
                <input type="checkbox" name="commutation_requested" value="1" class="h-4 w-4 rounded accent-indigo-600" @checked($val('commutation_requested'))>
                Commutation requested (leave unchecked for “Not Requested”)
            </label>
        </section>

        <div class="flex justify-end gap-3">
            <a href="{{ route('leave-applications.index') }}" class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800">Cancel</a>
            <button type="submit" class="rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-2.5 text-sm font-semibold text-white hover:from-indigo-700 hover:to-indigo-800">{{ $editing ? 'Save changes' : 'File application' }}</button>
        </div>
    </form>
</div>
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

    emp.addEventListener('change', function () {
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