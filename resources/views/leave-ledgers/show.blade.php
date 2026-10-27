@extends('layouts.app')

@section('title', 'Leave ledger - ' . \App\Support\EmployeeProfile::name($employee))
@section('header', 'Leave card')
@section('subheader', \App\Support\EmployeeProfile::name($employee) . ' · ' . ($employment?->position?->title ?? '—') . ' · ' . ($employment?->department?->name ?? '—'))

@php
    $fmt = fn ($n) => is_null($n) ? '—' : rtrim(rtrim(number_format($n, 3), '0'), '.');

    $btnPrimary = 'inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700';
    $btnGhost   = 'inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700';
    $btnDanger  = 'inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700';
    $field      = 'w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white';
    $lbl        = 'mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200';
    $card       = 'overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800';
    $stat       = 'rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800';

    $period = function ($e) {
        if (! $e->period_start) return '—';
        if (! $e->period_end || $e->period_start->eq($e->period_end)) return $e->period_start->format('M d, Y');
        if ($e->period_start->day === 1 && $e->period_end->isSameDay($e->period_start->copy()->endOfMonth())) return $e->period_start->format('M Y');
        return $e->period_start->format('M d') . ' – ' . $e->period_end->format('M d, Y');
    };

    $current     = $tabs->firstWhere('id', $typeId) ?? $types->firstWhere('id', $typeId);
    $totalEarned = $entries->sum('earned');
    $totalUsed   = $entries->sum('days_with_pay');

    $statConfig = [
        ['Earned (' . ($current->code ?? '') . ')',  $fmt($totalEarned), 'bi-plus-circle',  'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300', 'text-emerald-700 dark:text-emerald-400'],
        ['Used (' . ($current->code ?? '') . ')',    $fmt($totalUsed),   'bi-dash-circle',  'bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-300',       'text-amber-700 dark:text-amber-400'],
    ];
@endphp

@section('header-actions')
    <a href="{{ route('leave-ledgers.index') }}" class="{{ $btnGhost }}"><i class="bi bi-arrow-left"></i> Back</a>
    <button type="button" id="openEntry" class="{{ $btnPrimary }}"><i class="bi bi-plus-circle"></i> Post entry</button>
@endsection

@section('breadcrumbs')
    <li class="flex items-center"><i class="bi bi-chevron-right mx-2 text-xs text-gray-300 dark:text-gray-600"></i>
        <span class="font-medium text-gray-800 dark:text-gray-200">Leave management</span></li>
    <li class="flex items-center"><i class="bi bi-chevron-right mx-2 text-xs text-gray-300 dark:text-gray-600"></i>
        <a href="{{ route('leave-ledgers.index') }}" class="font-medium text-gray-800 hover:text-indigo-600 dark:text-gray-200 dark:hover:text-indigo-400">Leave ledger</a></li>
    <li class="flex items-center"><i class="bi bi-chevron-right mx-2 text-xs text-gray-300 dark:text-gray-600"></i>
        <span class="font-medium text-gray-800 dark:text-gray-200">Leave card</span></li>
@endsection

@section('content')
<div class="space-y-6 p-6">

    {{-- ===== Flash / errors ===== --}}
    @if (session('success'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-300" role="status">
            <i class="bi bi-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-300" role="alert">
            <ul class="list-inside list-disc space-y-1">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- ===== Key numbers ===== --}}
    <section class="grid grid-cols-2 gap-4 lg:grid-cols-4" aria-label="Summary">
        @foreach ($tabs->take(2) as $t)
            @php
                $chip = $loop->first
                    ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-300'
                    : 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300';
                $icon = $loop->first ? 'bi-wallet2' : 'bi-heart-pulse';
            @endphp
            <div class="{{ $stat }}">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $t->name }} balance</p>
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg {{ $chip }}">
                        <i class="bi {{ $icon }}" aria-hidden="true"></i>
                    </span>
                </div>
                <p class="mt-2 text-3xl font-semibold tabular-nums text-gray-900 dark:text-white">
                    {{ $fmt($balances[$t->id] ?? null) }}
                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">days</span>
                </p>
            </div>
        @endforeach

        @foreach ($statConfig as [$name, $value, $icon, $chip, $color])
            <div class="{{ $stat }}">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $name }}</p>
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg {{ $chip }}">
                        <i class="bi {{ $icon }}" aria-hidden="true"></i>
                    </span>
                </div>
                <p class="mt-2 text-3xl font-semibold tabular-nums {{ $color }}">{{ $value }}</p>
            </div>
        @endforeach
    </section>

    {{-- ===== Leave type tabs ===== --}}
    <nav class="flex flex-wrap gap-2" aria-label="Filter by leave type">
        @foreach ($tabs as $t)
            @php $active = $typeId === $t->id; @endphp
            <a href="{{ route('leave-ledgers.show', [$employee, 'type' => $t->id]) }}"
               @if($active) aria-current="page" @endif
               class="inline-flex items-center gap-1.5 rounded-full border px-3.5 py-1.5 text-sm font-medium transition-colors
                      {{ $active ? 'border-indigo-600 bg-indigo-600 text-white' : 'border-gray-300 bg-white text-gray-700 hover:bg-indigo-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                {{ $t->name }}
            </a>
        @endforeach
    </nav>

    {{-- ===== Ledger table ===== --}}
    <section class="{{ $card }}" aria-label="Leave ledger entries">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">
                {{ $current->name ?? 'Ledger' }} entries
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400" role="status">
                {{ $entries->count() }} {{ \Illuminate\Support\Str::plural('entry', $entries->count()) }}
            </p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                <caption class="sr-only">Leave ledger entries for {{ \App\Support\EmployeeProfile::name($employee) }}</caption>
                <thead class="bg-gray-50 dark:bg-gray-900/30">
                    <tr class="text-left text-gray-600 dark:text-gray-400">
                        <th scope="col" class="px-4 py-3 font-medium">Posted</th>
                        <th scope="col" class="px-4 py-3 font-medium">Period</th>
                        <th scope="col" class="px-4 py-3 font-medium">Particulars</th>
                        <th scope="col" class="px-4 py-3 text-right font-medium">Earned</th>
                        <th scope="col" class="px-4 py-3 text-right font-medium">Used (w/ pay)</th>
                        <th scope="col" class="px-4 py-3 text-right font-medium">Used (w/o pay)</th>
                        <th scope="col" class="px-4 py-3 text-right font-medium">Balance</th>
                        <th scope="col" class="px-4 py-3 font-medium">Action taken</th>
                        <th scope="col" class="px-4 py-3 font-medium">By</th>
                        <th scope="col" class="px-4 py-3 text-right font-medium"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($entries as $e)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 {{ $e->reversal ? 'bg-gray-50 text-gray-500 dark:bg-gray-900/40 dark:text-gray-400' : '' }}">
                            <td class="whitespace-nowrap px-4 py-3 align-top text-gray-700 dark:text-gray-300">
                                {{ $e->transaction_date->format('M d, Y') }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 align-top text-gray-700 dark:text-gray-300">
                                {{ $period($e) }}
                            </td>
                            <td class="px-4 py-3 align-top text-gray-700 dark:text-gray-300">
                                <span class="mr-1.5 inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $e->typeBadge() }}">{{ $e->typeLabel() }}</span>
                                {{ $e->particulars }}
                                @if ($e->reversal)
                                    <span class="ml-1 text-xs italic text-gray-500 dark:text-gray-400">(reversed)</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right align-top tabular-nums text-emerald-700 dark:text-emerald-400">
                                {{ $e->earned ? $fmt($e->earned) : '' }}
                            </td>
                            <td class="px-4 py-3 text-right align-top tabular-nums text-red-600 dark:text-red-400">
                                {{ $e->days_with_pay ? $fmt($e->days_with_pay) : '' }}
                            </td>
                            <td class="px-4 py-3 text-right align-top tabular-nums text-gray-700 dark:text-gray-300">
                                {{ $e->days_without_pay ? $fmt($e->days_without_pay) : '' }}
                            </td>
                            <td class="px-4 py-3 text-right align-top font-semibold tabular-nums {{ $e->balance < 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                                {{ $fmt($e->balance) }}
                            </td>
                            <td class="px-4 py-3 align-top text-gray-700 dark:text-gray-300">{{ $e->action_taken ?? '—' }}</td>
                            <td class="whitespace-nowrap px-4 py-3 align-top text-xs text-gray-600 dark:text-gray-400">{{ $e->recorder?->display_name ?? '—' }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-right align-top">
                                @if ($e->isReversible())
                                    <form method="POST" action="{{ route('leave-ledgers.reverse', $e) }}" class="js-confirm"
                                          data-title="Reverse this entry?"
                                          data-message="A cancelling entry will be posted against the original. This cannot be undone.">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex rounded p-2 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/30"
                                                aria-label="Reverse entry" title="Reverse">
                                            <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-14 text-center">
                                <i class="bi bi-journal-x text-4xl text-gray-300 dark:text-gray-600" aria-hidden="true"></i>
                                <p class="mt-2 font-medium text-gray-900 dark:text-white">
                                    No entries yet for {{ $current->name ?? 'this leave type' }}
                                </p>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Use “Post entry” to add an opening balance or credits.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-gray-200 px-6 py-3 text-xs text-gray-500 dark:border-gray-700 dark:text-gray-400">
            <i class="bi bi-info-circle mr-1" aria-hidden="true"></i>
            Entries are never edited or deleted. To correct a mistake, reverse the entry or post an adjustment.
        </div>
    </section>
</div>

{{-- ===== Post entry dialog ===== --}}
<dialog id="entryModal" class="m-auto w-full max-w-lg rounded-xl bg-white p-0 text-gray-900 shadow-2xl dark:bg-gray-800 dark:text-white" aria-labelledby="entryTitle">
    <form method="POST" action="{{ route('leave-ledgers.store', $employee) }}">
        @csrf
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
            <h2 id="entryTitle" class="text-lg font-semibold">Post ledger entry</h2>
            <button type="button" id="closeEntry" class="rounded p-1 text-gray-500 hover:text-gray-900 dark:hover:text-white" aria-label="Close">
                <i class="bi bi-x-lg" aria-hidden="true"></i>
            </button>
        </div>

        <div class="max-h-[70vh] space-y-4 overflow-y-auto px-6 py-5">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="leave_type_id" class="{{ $lbl }}">Leave type <span class="text-red-600" aria-hidden="true">*</span></label>
                    <select id="leave_type_id" name="leave_type_id" required class="{{ $field }}">
                        @foreach ($types as $t)
                            <option value="{{ $t->id }}" @selected(old('leave_type_id', $typeId) == $t->id)>{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="entry_type" class="{{ $lbl }}">Entry type <span class="text-red-600" aria-hidden="true">*</span></label>
                    <select id="entry_type" name="entry_type" required class="{{ $field }}">
                        <option value="opening_balance" @selected(old('entry_type') === 'opening_balance')>Opening balance</option>
                        <option value="earned" @selected(old('entry_type', 'earned') === 'earned')>Credits earned (month)</option>
                        <option value="adjustment" @selected(old('entry_type') === 'adjustment')>Adjustment</option>
                        <option value="monetization" @selected(old('entry_type') === 'monetization')>Monetization</option>
                        <option value="forfeiture" @selected(old('entry_type') === 'forfeiture')>Forfeiture</option>
                    </select>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="transaction_date" class="{{ $lbl }}">Date posted <span class="text-red-600" aria-hidden="true">*</span></label>
                    <input id="transaction_date" type="date" name="transaction_date" required
                           value="{{ old('transaction_date', now()->toDateString()) }}" class="{{ $field }}">
                </div>
                <div>
                    <label for="amount" class="{{ $lbl }}">Amount (days) <span class="text-red-600" aria-hidden="true">*</span></label>
                    <input id="amount" type="number" step="0.001" min="0.001" name="amount" required
                           value="{{ old('amount') }}" class="{{ $field }} tabular-nums">
                </div>
            </div>

            <div data-for="earned">
                <label for="period_month" class="{{ $lbl }}">Month credited</label>
                <input id="period_month" type="month" name="period_month"
                       value="{{ old('period_month', now()->format('Y-m')) }}" class="{{ $field }}">
            </div>

            <div data-for="adjustment">
                <label for="direction" class="{{ $lbl }}">Adjustment</label>
                <select id="direction" name="direction" class="{{ $field }}">
                    <option value="add" @selected(old('direction') === 'add')>Add credits</option>
                    <option value="deduct" @selected(old('direction') === 'deduct')>Deduct credits</option>
                </select>
            </div>

            <div>
                <label for="particulars" class="{{ $lbl }}">
                    Particulars
                    <span id="partReq" class="hidden text-red-600" aria-hidden="true">*</span>
                    <span id="partOpt" class="font-normal text-gray-500 dark:text-gray-400">(optional)</span>
                </label>
                <input id="particulars" type="text" name="particulars" maxlength="255"
                       value="{{ old('particulars') }}" placeholder="Reason or description" class="{{ $field }}">
            </div>
        </div>

        <div class="flex justify-end gap-3 border-t border-gray-200 px-6 py-4 dark:border-gray-700">
            <button type="button" id="cancelEntry" class="{{ $btnGhost }}">Cancel</button>
            <button type="submit" class="{{ $btnPrimary }}"><i class="bi bi-check2"></i> Post entry</button>
        </div>
    </form>
</dialog>
@endsection

@push('scripts')
<script>
(() => {
    const modal = document.getElementById('entryModal');
    if (!modal) return;

    const typeSel   = document.getElementById('entry_type');
    const partReq   = document.getElementById('partReq');
    const partOpt   = document.getElementById('partOpt');
    const particulars = document.getElementById('particulars');

    function sync() {
        modal.querySelectorAll('[data-for]').forEach(el => {
            const show = el.dataset.for === typeSel.value;
            el.classList.toggle('hidden', !show);
            el.querySelectorAll('input,select').forEach(i => { i.disabled = !show; });
        });
        const req = typeSel.value === 'adjustment';
        partReq.classList.toggle('hidden', !req);
        partOpt.classList.toggle('hidden', req);
        particulars.required = req;
    }

    document.getElementById('openEntry')?.addEventListener('click', () => modal.showModal());
    document.getElementById('closeEntry')?.addEventListener('click', () => modal.close());
    document.getElementById('cancelEntry')?.addEventListener('click', () => modal.close());
    modal.addEventListener('click', e => { if (e.target === modal) modal.close(); });

    typeSel.addEventListener('change', sync);
    sync();

    {{-- Re-open the dialog with entered values if validation failed --}}
    @if ($errors->any() && old('entry_type'))
        modal.showModal();
    @endif
})();

/* Confirm-on-submit pattern (matches leave-applications index) */
document.querySelectorAll('form.js-confirm').forEach(form => {
    form.addEventListener('submit', e => {
        if (form.dataset.ok) return;
        e.preventDefault();
        const title = form.dataset.title || 'Are you sure?';
        const msg   = form.dataset.message || 'This cannot be undone.';
        const go    = () => { form.dataset.ok = '1'; form.submit(); };
        if (window.Swal) {
            Swal.fire({ title, text: msg, icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#dc2626', confirmButtonText: 'Reverse', cancelButtonText: 'Cancel' })
                .then(r => r.isConfirmed && go());
        } else if (confirm(msg)) { go(); }
    });
});
</script>
@endpush