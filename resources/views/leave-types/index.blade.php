@extends('layouts.app')

@section('title', 'Leave Types')
@section('breadcrumbs')
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">Leave Management</span>
    </li>
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">Leave Types</span>
    </li>
@endsection
@section('content')
<div class="py-6">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <x-leave.banner title="Leave Types" icon="bi-tags" subtitle="Types of leave offered on CS Form No. 6. Inactive types are hidden from the application form.">
        <x-slot:actions>
            <x-leave.banner-action id="openCreate" icon="bi-plus-lg">Add leave type</x-leave.banner-action>
        </x-slot:actions>
    </x-leave.banner>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
        <x-leave.stat label="Total Types" :value="\App\Models\LeaveType::count()" icon="bi-tags" gradient="bg-gradient-to-r from-[#0e7490] via-[#3b82f6] to-[#4f46e5]" />
        <x-leave.stat label="Active" :value="\App\Models\LeaveType::where('is_active', true)->count()" icon="bi-check-circle" gradient="bg-gradient-to-bl from-[#84cc16] via-[#16a34a] to-[#0f766e]" iconBg="bg-green-100" iconColor="text-green-600" />
        <x-leave.stat label="Deduct Credits" :value="\App\Models\LeaveType::where('is_credit_based', true)->count()" icon="bi-wallet2" gradient="bg-gradient-to-tl from-[#1e293b] via-[#6366f1] to-[#71717a]" iconBg="bg-purple-100" iconColor="text-purple-600" />
        <x-leave.stat label="Inactive" :value="\App\Models\LeaveType::where('is_active', false)->count()" icon="bi-slash-circle" gradient="bg-[radial-gradient(ellipse_at_bottom_left,_var(--tw-gradient-stops))] from-[#ea580c] via-[#fb923c] to-[#fed7aa]" iconBg="bg-amber-100" iconColor="text-amber-600" />
    </div>

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="mt-6 rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-800" role="status">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mt-6 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">{{ session('error') }}</div>
    @endif

    {{-- Search --}}
    <form method="GET" action="{{ route('leave-types.index') }}" class="mt-6 flex gap-2">
        <div class="relative flex-1 sm:max-w-sm">
            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Search code, name, or legal basis"
                   class="w-full rounded-lg border border-gray-300 bg-white py-2 pl-9 pr-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        </div>
        <button class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800">Search</button>
        @if (request('q'))
            <a href="{{ route('leave-types.index') }}" class="px-2 py-2 text-sm text-indigo-600 hover:underline dark:text-indigo-400">Clear</a>
        @endif
    </form>

    {{-- Table --}}
    <div class="mt-6 overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-gray-200 bg-gray-50 text-xs uppercase tracking-wide text-gray-600 dark:border-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                <tr>
                    <th class="px-4 py-3 font-semibold">Code</th>
                    <th class="px-4 py-3 font-semibold">Leave type</th>
                    <th class="px-4 py-3 font-semibold">Legal basis</th>
                    <th class="px-4 py-3 font-semibold">Credits</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                    <th class="px-4 py-3 text-right font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($leaveTypes as $type)
                    <tr class="{{ $type->is_active ? '' : 'bg-gray-50 text-gray-500 dark:bg-gray-950' }}">
                        <td class="whitespace-nowrap px-4 py-3 font-mono text-xs font-semibold">{{ $type->code }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $type->name }}</td>
                        <td class="max-w-xs px-4 py-3 text-gray-600 dark:text-gray-400">{{ $type->legal_basis ?? '—' }}</td>
                        <td class="whitespace-nowrap px-4 py-3">
                            @if ($type->is_credit_based)
                                <span class="rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-medium text-indigo-800">Deducts credits</span>
                            @else
                                <span class="text-gray-400">None</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-4 py-3">
                            <form method="POST" action="{{ route('leave-types.toggle', $type) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $type->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}"
                                        title="Click to {{ $type->is_active ? 'deactivate' : 'activate' }}">
                                    {{ $type->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 text-right">
                            <button type="button"
                                    class="edit-btn rounded p-1.5 text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-gray-800"
                                    aria-label="Edit {{ $type->name }}"
                                    data-id="{{ $type->id }}"
                                    data-code="{{ $type->code }}"
                                    data-name="{{ $type->name }}"
                                    data-legal="{{ $type->legal_basis }}"
                                    data-credit="{{ $type->is_credit_based ? 1 : 0 }}"
                                    data-active="{{ $type->is_active ? 1 : 0 }}">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <form method="POST" action="{{ route('leave-types.destroy', $type) }}" class="inline"
                                  onsubmit="return confirm('Delete {{ addslashes($type->name) }}? This cannot be undone.');">
                                @csrf @method('DELETE')
                                <button type="submit" class="rounded p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-gray-800" aria-label="Delete {{ $type->name }}">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                            No leave types found. Run <code class="rounded bg-gray-100 px-1 dark:bg-gray-800">php artisan db:seed --class=LeaveTypeSeeder</code> or add one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>

{{-- Add / Edit modal --}}
<div id="modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-600 bg-opacity-50 backdrop-blur-sm p-4" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl dark:bg-gray-800">
        <div class="flex items-start justify-between">
            <h2 id="modalTitle" class="text-xl font-semibold text-gray-900 dark:text-white">Add leave type</h2>
            <button type="button" id="closeModal" class="rounded p-1 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200" aria-label="Close">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        @if ($errors->any())
            <div class="mt-4 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form id="leaveForm" method="POST" action="{{ route('leave-types.store') }}" class="mt-5 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="methodField" value="POST" disabled>
            <input type="hidden" name="edit_id" id="editId" value="{{ old('edit_id') }}">

            <div class="grid gap-4 sm:grid-cols-[8rem_1fr]">
                <div>
                    <label for="f_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Code</label>
                    <input id="f_code" name="code" type="text" maxlength="20" required value="{{ old('code') }}"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm uppercase focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="f_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Leave type name</label>
                    <input id="f_name" name="name" type="text" maxlength="255" required value="{{ old('name') }}"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
            </div>

            <div>
                <label for="f_legal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Legal basis <span class="font-normal text-gray-500">(optional)</span></label>
                <input id="f_legal" name="legal_basis" type="text" maxlength="255" value="{{ old('legal_basis') }}"
                       placeholder="e.g. R.A. No. 11210 / IRR issued by CSC, DOLE and SSS"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>

            <div class="space-y-2">
                <label class="flex items-center gap-2 text-sm dark:text-gray-200">
                    <input id="f_credit" type="checkbox" name="is_credit_based" value="1" class="h-4 w-4 rounded accent-indigo-600"
                           {{ old('is_credit_based') ? 'checked' : '' }}>
                    Deducts from leave credits (like Vacation and Sick Leave)
                </label>
                <label class="flex items-center gap-2 text-sm dark:text-gray-200">
                    <input id="f_active" type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded accent-indigo-600"
                           {{ old('is_active', $errors->any() ? null : 1) ? 'checked' : '' }}>
                    Active (available on the leave form)
                </label>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" id="cancelModal" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800">Cancel</button>
                <button type="submit" class="rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-700 px-5 py-2 text-sm font-semibold text-white hover:from-indigo-700 hover:to-indigo-800">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    var modal = document.getElementById('modal');
    var form = document.getElementById('leaveForm');
    var title = document.getElementById('modalTitle');
    var method = document.getElementById('methodField');
    var editId = document.getElementById('editId');
    var base = @json(url('leave-types'));
    var storeUrl = @json(route('leave-types.store'));

    function open() { modal.classList.remove('hidden'); modal.classList.add('flex'); document.getElementById('f_code').focus(); }
    function close() { modal.classList.add('hidden'); modal.classList.remove('flex'); }

    function setCreate() {
        title.textContent = 'Add leave type';
        form.action = storeUrl;
        method.disabled = true; editId.value = '';
        form.reset();
        document.getElementById('f_active').checked = true;
    }

    function setEdit(id) {
        title.textContent = 'Edit leave type';
        form.action = base + '/' + id;
        method.value = 'PUT'; method.disabled = false; editId.value = id;
    }

    document.getElementById('openCreate').addEventListener('click', function () { setCreate(); open(); });
    document.getElementById('closeModal').addEventListener('click', close);
    document.getElementById('cancelModal').addEventListener('click', close);
    modal.addEventListener('click', function (e) { if (e.target === modal) close(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });

    document.querySelectorAll('.edit-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var d = btn.dataset;
            setEdit(d.id);
            document.getElementById('f_code').value = d.code;
            document.getElementById('f_name').value = d.name;
            document.getElementById('f_legal').value = d.legal || '';
            document.getElementById('f_credit').checked = d.credit === '1';
            document.getElementById('f_active').checked = d.active === '1';
            open();
        });
    });

    // Re-open the modal with the user's input if validation failed
    @if ($errors->any())
        @if (old('edit_id'))
            setEdit(@json(old('edit_id')));
        @else
            title.textContent = 'Add leave type';
        @endif
        open();
    @endif
})();
</script>
@endsection