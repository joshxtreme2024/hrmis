@extends('layouts.app')

@section('title', 'Edit employment - ' . ($employee->display_name ?? ''))
@section('header', 'Edit employment')
@section('subheader', 'Update employment record for ' . ($employee->display_name ?? ''))

@php
    use Carbon\Carbon;

    $btnPrimary = 'inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700';
    $btnGhost   = 'inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700';
    $field      = 'w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white';
    $lbl        = 'mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200';
    $err        = 'mt-1 text-xs text-red-600 dark:text-red-400';

    $employmentTypes = [
        'permanent'      => 'Permanent',
        'temporary' => 'Temporary',
        'contractual'  => 'Contractual',
        'casual'       => 'Casual',
        'job_order'    => 'Job order',
        'consultant'    => 'Consultant',
        'co_terminus'   => 'Coterminus',
    ];
@endphp

@section('header-actions')
    <a href="{{ route('employees.showProfile', $employee->user_id) }}" class="{{ $btnGhost }}"><i class="bi bi-arrow-left"></i> Back to profile</a>
@endsection

@section('breadcrumbs')
    <li class="flex items-center"><i class="bi bi-chevron-right mx-2 text-xs text-gray-300 dark:text-gray-600"></i>
        <a href="{{ route('employees.index') }}" class="font-medium text-gray-800 hover:text-indigo-600 dark:text-gray-200 dark:hover:text-indigo-400">Employee management</a></li>
    <li class="flex items-center"><i class="bi bi-chevron-right mx-2 text-xs text-gray-300 dark:text-gray-600"></i>
        <a href="{{ route('employees.showProfile', $employee->user_id) }}" class="font-medium text-gray-800 hover:text-indigo-600 dark:text-gray-200 dark:hover:text-indigo-400">{{ $employee->user?->display_name }}</a></li>
    <li class="flex items-center"><i class="bi bi-chevron-right mx-2 text-xs text-gray-300 dark:text-gray-600"></i>
        <span class="font-medium text-gray-800 dark:text-gray-200">Edit employment</span></li>
@endsection

@section('content')
<div class="mx-auto max-w-3xl space-y-6 p-6">

    {{-- Flash / status --}}
    @if(session('status'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-300" role="status">
            <i class="bi bi-check-circle mr-1"></i> {{ session('status') }}
        </div>
    @endif

    @if($employee?->status === 'terminated')
        <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-800 dark:bg-amber-900/20 dark:text-amber-300" role="alert">
            <i class="bi bi-exclamation-triangle mr-1"></i>
            This employment is currently <strong>terminated</strong>. Use the profile page to reactivate it before editing — or contact an administrator.
        </div>
    @endif

    <form action="{{ route('management.employment.update', $employee) }}" method="POST"
          class="space-y-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        @csrf
        @method('PATCH')

        {{-- Employment identity --}}
        <div>
            <h2 class="mb-4 text-base font-semibold text-gray-900 dark:text-white">Employment details</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="employee_id" class="{{ $lbl }}">Employee ID <span class="text-red-600">*</span></label>
                    <input type="text" id="employee_id" name="employee_id" required
                           value="{{ old('employee_id', $employee?->employee_id) }}"
                           class="{{ $field }} @error('employee_id') border-red-500 @enderror">
                    @error('employee_id')<p class="{{ $err }}">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="employment_type" class="{{ $lbl }}">Employment type <span class="text-red-600">*</span></label>
                    <select id="employment_type" name="employment_type" required
                            class="{{ $field }} @error('employment_type') border-red-500 @enderror">
                        <option value="">Select type</option>
                        @foreach($employmentTypes as $value => $label)
                            <option value="{{ $value }}" @selected(old('employment_type', $employee?->employment_type) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('employment_type')<p class="{{ $err }}">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="department_id" class="{{ $lbl }}">Department</label>
                    <select id="department_id" name="department_id"
                            class="{{ $field }} @error('department_id') border-red-500 @enderror">
                        <option value="">— None —</option>
                        @foreach($departments ?? [] as $dept)
                            <option value="{{ $dept->id }}" @selected((int) old('department_id', $employee?->department_id) === $dept->id)>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')<p class="{{ $err }}">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="position_id" class="{{ $lbl }}">Position</label>
                    <select id="position_id" name="position_id"
                            class="{{ $field }} @error('position_id') border-red-500 @enderror">
                        <option value="">— None —</option>
                        @foreach($positions ?? [] as $pos)
                            <option value="{{ $pos->id }}" @selected((int) old('position_id', $employee?->position_id) === $pos->id)>
                                {{ $pos->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('position_id')<p class="{{ $err }}">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Dates and compensation --}}
        <div>
            <h2 class="mb-4 text-base font-semibold text-gray-900 dark:text-white">Dates & compensation</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="hired_at" class="{{ $lbl }}">Date hired <span class="text-red-600">*</span></label>
                    <input type="date" id="hired_at" name="hired_at" required
                           value="{{ old('hired_at', optional($employee?->hired_at)->format('Y-m-d')) }}"
                           class="{{ $field }} @error('hired_at') border-red-500 @enderror">
                    @error('hired_at')<p class="{{ $err }}">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="contract_end" class="{{ $lbl }}">Contract end date</label>
                    <input type="date" id="contract_end" name="contract_end"
                           value="{{ old('contract_end', optional($employee?->contract_end)->format('Y-m-d')) }}"
                           class="{{ $field }} @error('contract_end') border-red-500 @enderror">
                    @error('contract_end')<p class="{{ $err }}">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave blank for regular/permanent employees.</p>

                    {{-- Live contract countdown (mirrors the profile page logic) --}}
                    <p id="contractHint" class="mt-1 hidden text-xs font-medium"></p>
                </div>

                <div>
                    <label for="monthly_salary" class="{{ $lbl }}">Monthly salary</label>
                    <input type="number" id="monthly_salary" name="monthly_salary" step="0.01" min="0"
                           value="{{ old('monthly_salary', $employee?->salary) }}"
                           class="{{ $field }} @error('monthly_salary') border-red-500 @enderror">
                    @error('monthly_salary')<p class="{{ $err }}">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="{{ $lbl }}">Status</label>
                    <div class="flex h-[38px] items-center rounded-lg border border-gray-300 bg-gray-50 px-3 text-sm text-gray-700 dark:border-gray-600 dark:bg-gray-700/50 dark:text-gray-300">
                        <i class="bi bi-lock mr-1" aria-hidden="true"></i>
                        {{ $employee?->status_label ?? 'No record' }}
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Change status via the profile page (terminate / reactivate).</p>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-200 pt-4 dark:border-gray-700">
            <a href="{{ route('employees.showProfile', $employee->user_id) }}" class="{{ $btnGhost }}">Cancel</a>
            <button type="submit" class="{{ $btnPrimary }}" @disabled($employee?->status === 'terminated')>
                <i class="bi bi-check2"></i> Save changes
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const input = document.getElementById('contract_end');
    const hint  = document.getElementById('contractHint');
    if (!input || !hint) return;

    const fmt = d => d.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });

    const update = () => {
        hint.className = 'mt-1 hidden text-xs font-medium';

        if (!input.value) { hint.textContent = ''; return; }

        const end  = new Date(input.value + 'T00:00:00');
        const today = new Date(); today.setHours(0, 0, 0, 0);

        if (isNaN(end)) return;

        const days = Math.round((end - today) / 86400000);
        const plural = n => n === 1 ? '' : 's';

        if (days < 0) {
            hint.textContent = `⚠ Expired ${Math.abs(days)} day${plural(Math.abs(days))} ago (${fmt(end)})`;
            hint.className = 'mt-1 text-xs font-medium text-red-600 dark:text-red-400';
        } else if (days === 0) {
            hint.textContent = `⚠ Contract ends today (${fmt(end)})`;
            hint.className = 'mt-1 text-xs font-medium text-orange-600 dark:text-orange-400';
        } else if (days <= 7) {
            hint.textContent = `⚠ ${days} day${plural(days)} remaining (${fmt(end)})`;
            hint.className = 'mt-1 text-xs font-medium text-orange-600 dark:text-orange-400';
        } else if (days <= 30) {
            hint.textContent = `${days} days remaining (${fmt(end)})`;
            hint.className = 'mt-1 text-xs font-medium text-amber-600 dark:text-amber-400';
        } else {
            hint.textContent = `${days} days remaining (${fmt(end)})`;
            hint.className = 'mt-1 text-xs text-gray-500 dark:text-gray-400';
        }
    };

    input.addEventListener('input', update);
    input.addEventListener('change', update);
    update();
})();
</script>
@endpush