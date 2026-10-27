@extends('layouts.app')

@section('title', 'Employee Management')
@section('header', 'Employee Management')
@section('subheader', 'Find employees, review their status and manage separations')

@php
    use Illuminate\Support\Facades\Route;

    $btnPrimary = 'inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700';
    $btnGhost   = 'inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700';
    $field      = 'w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white';
    $label      = 'mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200';
    $statusLabels = ['active' => 'Active', 'inactive' => 'Inactive', 'on_leave' => 'On leave', 'resigned' => 'Resigned', 'retired' => 'Retired', 'suspended' => 'Suspended', 'terminated' => 'Terminated'];
    $hasFilters = collect(['search', 'department', 'status', 'position'])->contains(fn ($k) => filled(request($k)));
    $addUrl = Route::has('employees.create') ? route('employees.create') : null;
@endphp

@section('header-actions')
    @if($addUrl)
        <a href="{{ $addUrl }}" class="{{ $btnPrimary }}"><i class="bi bi-person-plus"></i> Add employee</a>
    @endif
    <button type="button" onclick="window.print()" class="{{ $btnGhost }}"><i class="bi bi-printer"></i> Print list</button>
@endsection

@section('breadcrumbs')
    <li class="flex items-center">
        <i class="bi bi-chevron-right mx-2 text-xs text-gray-300 dark:text-gray-600"></i>
        <span class="font-medium text-gray-800 dark:text-gray-200">Employee management</span>
    </li>
@endsection

@section('content')
<style>
    :is(a, button, input, select, textarea, summary):focus-visible { outline: 2px solid #6366f1; outline-offset: 2px; }
    #terminateModal::backdrop { background: rgb(17 24 39 / .6); }
    @media (prefers-reduced-motion: reduce) { * { transition: none !important; } }
</style>

<div class="space-y-6 p-6">

    {{-- ===== Key numbers (click to filter) ===== --}}
    <section class="grid grid-cols-2 gap-4 lg:grid-cols-4" aria-label="Summary">
        @foreach([
            ['Total employees', $stats['total'] ?? 0, route('employees.index'), 'text-gray-900 dark:text-white'],
            ['Active', $stats['active'] ?? 0, route('employees.index', ['status' => 'active']), 'text-emerald-600 dark:text-emerald-400'],
            ['On leave', $stats['on_leave'] ?? 0, route('employees.index', ['status' => 'on_leave']), 'text-amber-600 dark:text-amber-400'],
            ['New this month', $stats['new_this_month'] ?? 0, null, 'text-indigo-600 dark:text-indigo-400'],
        ] as [$name, $value, $url, $color])
            @php $tag = $url ? 'a' : 'div'; @endphp
            <{{ $tag }} @if($url) href="{{ $url }}" @endif class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800 {{ $url ? 'hover:border-indigo-300 dark:hover:border-indigo-700' : '' }}">
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $name }}</p>
                <p class="mt-1 text-3xl font-semibold tabular-nums {{ $color }}">{{ $value }}</p>
            </{{ $tag }}>
        @endforeach
    </section>

    {{-- ===== Filters ===== --}}
    <form id="filterForm" action="{{ route('employees.index') }}" method="GET" role="search" aria-label="Filter employees"
          class="grid gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:grid-cols-2 lg:grid-cols-4 print:hidden">
        <div>
            <label for="search" class="{{ $label }}">Search</label>
            <div class="relative">
                <i class="bi bi-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                <input id="search" type="search" name="search" value="{{ request('search') }}" placeholder="Name, email or employee ID" class="{{ $field }} pl-9">
            </div>
        </div>
        <div>
            <label for="department" class="{{ $label }}">Department</label>
            <select id="department" name="department" class="{{ $field }}">
                <option value="">All departments</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" @selected(request('department') == $department->id)>{{ $department->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="status" class="{{ $label }}">Employment status</label>
            <select id="status" name="status" class="{{ $field }}">
                <option value="">All statuses</option>
                @foreach($statusLabels as $value => $text)
                    <option value="{{ $value }}" @selected(request('status') == $value)>{{ $text }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="position" class="{{ $label }}">Position</label>
            <select id="position" name="position" class="{{ $field }}">
                <option value="">All positions</option>
                @foreach($positions as $position)
                    <option value="{{ $position->id }}" @selected(request('position') == $position->id)>{{ $position->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center gap-3 sm:col-span-2 lg:col-span-4">
            <button type="submit" class="{{ $btnPrimary }}"><i class="bi bi-funnel"></i> Apply filters</button>
            @if($hasFilters)
                <a href="{{ route('employees.index') }}" class="text-sm font-medium text-indigo-600 hover:underline dark:text-indigo-400">Clear all filters</a>
            @endif
        </div>
    </form>

    {{-- ===== Employee table ===== --}}
    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800" aria-label="Employee list">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Employees</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400" role="status">
                {{ $employees->total() }} {{ \Illuminate\Support\Str::plural('result', $employees->total()) }}{{ $hasFilters ? ' for your filters' : '' }}
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                <caption class="sr-only">Employees with department, position, status and date hired</caption>
                <thead class="bg-gray-50 dark:bg-gray-900/30">
                    <tr class="text-left text-gray-600 dark:text-gray-400">
                        <th scope="col" class="px-4 py-3 font-medium">Employee</th>
                        <th scope="col" class="px-4 py-3 font-medium">Department</th>
                        <th scope="col" class="px-4 py-3 font-medium">Position</th>
                        <th scope="col" class="px-4 py-3 font-medium">Status</th>
                        <th scope="col" class="px-4 py-3 font-medium">Date hired</th>
                        <th scope="col" class="px-4 py-3 text-right font-medium print:hidden"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($employees as $employee)
                        @php
                            $emp = $employee->employment;
                            $first = $employee->personalData?->first_name ?? '';
                            $last = $employee->personalData?->last_name ?? '';
                            $initials = strtoupper(substr($first, 0, 1) . substr($last, 0, 1)) ?: '?';
                            $isTerminated = $emp?->status === 'terminated';
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="px-4 py-3">
                                <a href="{{ route('employees.showProfile', $employee) }}" class="flex items-center gap-3">
                                    @if($employee->profile_picture)
                                        <img src="{{ asset('storage/' . $employee->profile_picture) }}" alt="" class="h-10 w-10 shrink-0 rounded-full object-cover">
                                    @else
                                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-50 text-sm font-semibold text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300" aria-hidden="true">{{ $initials }}</span>
                                    @endif
                                    <span class="min-w-0">
                                        <span class="block font-medium text-gray-900 hover:text-indigo-600 dark:text-white dark:hover:text-indigo-400">{{ $employee->display_name }}</span>
                                        <span class="block truncate text-gray-600 dark:text-gray-400">{{ $employee->email }}</span>
                                        @if($emp?->employee_id)<span class="block text-xs text-gray-500 dark:text-gray-400">ID {{ $emp->employee_id }}</span>@endif
                                    </span>
                                </a>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $emp?->department->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                {{ $emp?->position->title ?? '—' }}
                                @if($emp?->employment_type_label)
                                    <span class="mt-1 block"><span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $emp->employment_type_color ?? 'bg-gray-100 text-gray-800' }}">{{ $emp->employment_type_label }}</span></span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $emp ? $emp->status_badge : 'bg-gray-100 text-gray-800' }}">{{ $emp ? $emp->status_label : 'No record' }}</span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-gray-700 dark:text-gray-300">{{ $emp?->formatted_hire_date ?? '—' }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-right print:hidden">
                                <a href="{{ route('employees.showProfile', $employee) }}" class="inline-flex rounded p-2 text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-900/30" aria-label="View profile of {{ $employee->display_name }}" title="View profile"><i class="bi bi-person-lines-fill"></i></a>
                                @if($emp)
                                    @if(!$isTerminated)
                                        <button type="button" onclick="openTerminate({{ $employee->id }}, @js($employee->display_name))" class="inline-flex rounded p-2 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/30" aria-label="Terminate {{ $employee->display_name }}" title="Terminate employment"><i class="bi bi-person-x"></i></button>
                                    @else
                                        <button type="button" onclick="confirmReactivate('reactivate-{{ $employee->id }}', @js($employee->display_name))" class="inline-flex rounded p-2 text-emerald-700 hover:bg-emerald-50 dark:text-emerald-400 dark:hover:bg-emerald-900/30" aria-label="Reactivate {{ $employee->display_name }}" title="Reactivate employment"><i class="bi bi-arrow-counterclockwise"></i></button>
                                        <form id="reactivate-{{ $employee->id }}" action="{{ route('employees.termination.cancel', $employee) }}" method="POST" class="hidden">@csrf @method('PATCH')</form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-14 text-center">
                                <i class="bi bi-people text-4xl text-gray-300 dark:text-gray-600" aria-hidden="true"></i>
                                <p class="mt-2 font-medium text-gray-900 dark:text-white">{{ $hasFilters ? 'No employees match your filters' : 'No employees yet' }}</p>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $hasFilters ? 'Try a different search word, or clear the filters.' : 'Employees will appear here once they are added.' }}
                                </p>
                                @if($hasFilters)<a href="{{ route('employees.index') }}" class="{{ $btnGhost }} mt-4">Clear all filters</a>@endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col items-center justify-between gap-3 border-t border-gray-200 px-6 py-4 dark:border-gray-700 sm:flex-row print:hidden">
            <p class="text-sm text-gray-600 dark:text-gray-400">Showing {{ $employees->firstItem() ?? 0 }}–{{ $employees->lastItem() ?? 0 }} of {{ $employees->total() }}</p>
            {{ $employees->withQueryString()->links() }}
        </div>
    </section>
</div>

{{-- ===== Terminate dialog ===== --}}
<dialog id="terminateModal" class="m-auto w-full max-w-md rounded-xl bg-white p-0 text-gray-900 shadow-2xl dark:bg-gray-800 dark:text-white" aria-labelledby="terminateTitle">
    <form id="terminateForm" method="POST">
        @csrf
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
            <h2 id="terminateTitle" class="text-lg font-semibold">Terminate employment</h2>
            <button type="button" onclick="terminateModal.close()" class="rounded p-1 text-gray-500 hover:text-gray-900 dark:hover:text-white" aria-label="Close"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="space-y-4 px-6 py-5">
            <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-300" role="alert">
                You are ending the employment of <strong id="employeeNameDisplay"></strong>. Their account will lose access to the system. You can reactivate it later if this was a mistake.
            </div>
            <div>
                <label for="termination_date" class="{{ $label }}">Termination date <span class="text-red-600" aria-hidden="true">*</span></label>
                <input type="date" id="termination_date" name="termination_date" required class="{{ $field }}">
            </div>
            <div>
                <label for="termination_reason" class="{{ $label }}">Reason <span class="text-red-600" aria-hidden="true">*</span></label>
                <select id="termination_reason" name="termination_reason" required class="{{ $field }}">
                    <option value="">Select a reason</option>
                    @foreach(['resignation' => 'Resignation', 'retirement' => 'Retirement', 'death' => 'Death', 'dismissal' => 'Dismissal', 'drop_from_roll' => 'Dropped from the rolls', 'end_of_term' => 'End of term', 'laid_off' => 'Laid off', 'separation' => 'Separation', 'termination' => 'Termination', 'other' => 'Other'] as $v => $t)
                        <option value="{{ $v }}">{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="termination_details" class="{{ $label }}">Details <span id="detailsReq" class="hidden text-red-600" aria-hidden="true">*</span></label>
                <textarea id="termination_details" name="termination_details" rows="3" class="{{ $field }}" placeholder="Add any notes for the record"></textarea>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Required when the reason is “Other”.</p>
            </div>
        </div>
        <div class="flex justify-end gap-3 border-t border-gray-200 px-6 py-4 dark:border-gray-700">
            <button type="button" onclick="terminateModal.close()" class="{{ $btnGhost }}">Cancel</button>
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700"><i class="bi bi-person-x"></i> Terminate employment</button>
        </div>
    </form>
</dialog>
@endsection

@push('scripts')
<script>
const terminateModal = document.getElementById('terminateModal'),
      terminateForm = document.getElementById('terminateForm'),
      reasonSel = document.getElementById('termination_reason'),
      detailsBox = document.getElementById('termination_details');

function openTerminate(id, name) {
    terminateForm.reset();
    terminateForm.action = `{{ url('employees') }}/${id}/terminate`;
    document.getElementById('employeeNameDisplay').textContent = name;
    document.getElementById('termination_date').value = new Date().toISOString().slice(0, 10);
    detailsBox.required = false;
    document.getElementById('detailsReq').classList.add('hidden');
    terminateModal.showModal();
}
reasonSel.addEventListener('change', () => {
    const other = reasonSel.value === 'other';
    detailsBox.required = other;
    document.getElementById('detailsReq').classList.toggle('hidden', !other);
});
terminateModal.addEventListener('click', e => { if (e.target === terminateModal) terminateModal.close(); });

function confirmReactivate(formId, name) {
    const text = `${name}'s account will be reactivated and their employment status set back to active.`;
    const go = () => document.getElementById(formId).submit();
    if (window.Swal) {
        Swal.fire({ title: 'Reactivate employment?', text, icon: 'question', showCancelButton: true,
            confirmButtonColor: '#059669', confirmButtonText: 'Reactivate', cancelButtonText: 'Cancel' })
            .then(r => r.isConfirmed && go());
    } else if (confirm(text)) { go(); }
}

// Dropdown filters apply as soon as you change them
document.querySelectorAll('#filterForm select').forEach(s => s.addEventListener('change', () => s.form.submit()));
</script>
@endpush