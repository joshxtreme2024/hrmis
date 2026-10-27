@extends('layouts.app')

@section('title', 'My 201 File')
@section('header', 'My 201 File')
@section('subheader', 'Your employee documents and what is still missing')

@php
    use Illuminate\Support\Str;

    $btnPrimary = 'inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700';
    $btnGhost   = 'inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700';
    $chipBase   = 'inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium ';
    $statusChip = [
        'approved' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
        'pending'  => 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
        'rejected' => 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-300',
    ];
    $neutral = 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200';

    $employeeName = isset($employee)
        ? trim(($employee->first_name ?? '') . ' ' . ($employee->last_name ?? ''))
        : (auth()->user()->name ?? 'User');

    $pct       = (int) ($completeness['percentage'] ?? 0);
    $barColor  = $pct >= 100 ? 'bg-emerald-500' : ($pct >= 50 ? 'bg-amber-500' : 'bg-red-500');
    $items     = collect($completeness['items'] ?? []);
    $missing   = $items->reject(fn ($i) => $i['is_completed']);
    $done      = $items->filter(fn ($i) => $i['is_completed']);
@endphp

@section('header-actions')
    <button type="button" onclick="history.back()" class="{{ $btnGhost }}"><i class="bi bi-arrow-left"></i> Back</button>
@endsection

@section('breadcrumbs')
    <li class="flex items-center">
        <i class="bi bi-chevron-right mx-2 text-xs text-gray-300 dark:text-gray-600"></i>
        @if(isset($employee))
            <a href="{{ route('myprofile.show', $employee) }}" class="font-medium text-gray-800 hover:text-indigo-600 dark:text-gray-200 dark:hover:text-indigo-400">PDS</a>
        @else
            <span class="font-medium text-gray-800 dark:text-gray-200">PDS</span>
        @endif
    </li>
    <li class="flex items-center">
        <i class="bi bi-chevron-right mx-2 text-xs text-gray-300 dark:text-gray-600"></i>
        <span class="font-medium text-gray-800 dark:text-gray-200">My 201 file</span>
    </li>
@endsection

@section('content')
<style>
    :is(a, button, input, select, summary):focus-visible { outline: 2px solid #6366f1; outline-offset: 2px; }
    @media (prefers-reduced-motion: reduce) { * { transition: none !important; } }
</style>

<div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">

    {{-- ===== Title bar ===== --}}
    <section class="flex flex-col gap-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-50 text-xl text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-300"><i class="bi bi-folder2-open" aria-hidden="true"></i></div>
            <div>
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">201 file: {{ $employeeName }}</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Upload, review and track your employment documents.</p>
            </div>
        </div>
        <div class="flex flex-wrap gap-3 print:hidden">
            <button type="button" onclick="window.print()" class="{{ $btnGhost }}"><i class="bi bi-printer"></i> Print</button>
            @isset($employee)
                <a href="{{ route('mydocuments.create', $employee) }}" class="{{ $btnPrimary }}"><i class="bi bi-upload"></i> Upload document</a>
            @endisset
        </div>
    </section>

    @if(!isset($employee))
        {{-- ===== No profile yet ===== --}}
        <section class="rounded-xl border border-gray-200 bg-white p-12 text-center shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-amber-50 text-3xl text-amber-600 dark:bg-amber-900/20"><i class="bi bi-person-exclamation" aria-hidden="true"></i></div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Finish your PDS profile first</h2>
            <p class="mx-auto mt-1 max-w-md text-sm text-gray-600 dark:text-gray-400">You can upload documents once your Personal Data Sheet profile exists.</p>
            <a href="{{ route('myprofile.show') }}" class="{{ $btnPrimary }} mt-5"><i class="bi bi-person-plus"></i> Go to my profile</a>
        </section>
    @else

        {{-- ===== Key numbers ===== --}}
        <section class="grid grid-cols-2 gap-4 lg:grid-cols-4" aria-label="Summary">
            @foreach([
                ['201 file completeness', $pct . '%', ($completeness['completed'] ?? 0) . ' of ' . ($completeness['total_required'] ?? 0) . ' required types', 'text-indigo-600 dark:text-indigo-400'],
                ['Total documents', $totalDocuments ?? 0, ($approvedDocuments ?? 0) . ' approved', 'text-gray-900 dark:text-white'],
                ['Pending review', $pendingDocuments ?? 0, 'Waiting for HR approval', ($pendingDocuments ?? 0) > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-gray-900 dark:text-white'],
                ['Expiring soon', $expiringDocuments ?? 0, 'Within the next 30 days', ($expiringDocuments ?? 0) > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white'],
            ] as [$label, $value, $note, $color])
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $label }}</p>
                    <p class="mt-1 text-3xl font-semibold tabular-nums {{ $color }}">{{ $value }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $note }}</p>
                </div>
            @endforeach
        </section>

        {{-- ===== Required documents ===== --}}
        <section class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800" aria-labelledby="req-title">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h2 id="req-title" class="text-base font-semibold text-gray-900 dark:text-white">Required documents</h2>
                    <span class="text-sm tabular-nums text-gray-600 dark:text-gray-400">{{ $done->count() }} of {{ $items->count() }} uploaded</span>
                </div>
                <div class="mt-3 h-2 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700" role="progressbar" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100" aria-label="201 file completeness">
                    <div class="h-2 rounded-full {{ $barColor }} transition-all duration-500" style="width: {{ $pct }}%"></div>
                </div>
            </div>
            <div class="p-6">
                @if($items->isNotEmpty())
                    @if($missing->isNotEmpty())
                        <div class="mb-5 rounded-lg border border-amber-200 bg-amber-50 p-4 dark:border-amber-800 dark:bg-amber-900/20" role="status">
                            <p class="text-sm font-medium text-amber-900 dark:text-amber-200">
                                {{ $missing->count() }} required {{ Str::plural('document', $missing->count()) }} still to upload
                            </p>
                            <ul class="mt-2 flex flex-wrap gap-2">
                                @foreach($missing as $m)
                                    <li class="{{ $chipBase }} bg-white text-amber-800 ring-1 ring-inset ring-amber-200 dark:bg-gray-800 dark:text-amber-300 dark:ring-amber-800"><i class="bi bi-x-circle" aria-hidden="true"></i>{{ $m['name'] }}</li>
                                @endforeach
                            </ul>
                            <a href="{{ route('mydocuments.create', $employee) }}" class="mt-3 inline-block text-sm font-medium text-amber-900 underline dark:text-amber-200">Upload a document</a>
                        </div>
                    @else
                        <div class="mb-5 flex items-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-300" role="status">
                            <i class="bi bi-check-circle-fill" aria-hidden="true"></i> Your 201 file is complete. All required documents are uploaded.
                        </div>
                    @endif

                    <ul class="grid gap-2 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach($done as $d)
                            <li class="flex items-center gap-2 rounded-lg bg-gray-50 px-3 py-2 text-sm text-gray-800 dark:bg-gray-900/30 dark:text-gray-200">
                                <i class="bi bi-check-circle-fill text-emerald-500" aria-hidden="true"></i>
                                <span class="sr-only">Uploaded:</span>{{ $d['name'] }}
                                @if(($d['version_count'] ?? 0) > 1)<span class="text-xs text-gray-500 dark:text-gray-400">({{ $d['version_count'] }} versions)</span>@endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="py-4 text-center text-sm text-gray-600 dark:text-gray-400">No required document types have been set up yet.</p>
                @endif
            </div>
        </section>

        {{-- ===== Documents ===== --}}
        @if(isset($documents) && $documents->count() > 0)
            <section class="flex flex-col gap-3 rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:flex-row print:hidden">
                <div class="relative flex-1">
                    <label for="docSearch" class="sr-only">Search documents</label>
                    <i class="bi bi-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                    <input id="docSearch" type="search" placeholder="Search by title, type or year"
                           class="w-full rounded-lg border border-gray-300 bg-white py-2 pl-9 pr-3 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="docStatus" class="sr-only">Filter by status</label>
                    <select id="docStatus" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:w-48">
                        <option value="">All statuses</option>
                        <option value="approved">Approved</option>
                        <option value="pending">Pending</option>
                        <option value="rejected">Rejected</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
            </section>

            @foreach($documents as $category => $docs)
                <section class="doc-category overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800" aria-label="{{ $category }}">
                    <div class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-6 py-3 dark:border-gray-700 dark:bg-gray-900/30">
                        <h2 class="text-base font-semibold text-gray-900 dark:text-white"><i class="bi bi-folder mr-2 text-indigo-600 dark:text-indigo-400" aria-hidden="true"></i>{{ $category }}</h2>
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $docs->count() }} {{ Str::plural('document', $docs->count()) }}</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                            <thead class="bg-white dark:bg-gray-800">
                                <tr class="text-left text-gray-600 dark:text-gray-400">
                                    <th scope="col" class="px-4 py-3 font-medium">Type</th>
                                    <th scope="col" class="px-4 py-3 font-medium">Document</th>
                                    <th scope="col" class="px-4 py-3 font-medium">Date</th>
                                    <th scope="col" class="px-4 py-3 font-medium">Expires</th>
                                    <th scope="col" class="px-4 py-3 font-medium">Status</th>
                                    <th scope="col" class="px-4 py-3 text-right font-medium"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($docs as $doc)
                                    @php $status = $doc->status ?? 'draft'; @endphp
                                    <tr class="doc-row hover:bg-gray-50 dark:hover:bg-gray-700/40" data-status="{{ $status }}">
                                        <td class="whitespace-nowrap px-4 py-3 align-top">
                                            <span class="{{ $chipBase }} {{ $neutral }}" title="{{ $doc->documentType->name ?? '' }}">{{ $doc->documentType->code ?? 'N/A' }}</span>
                                        </td>
                                        <td class="px-4 py-3 align-top">
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $doc->title }}</p>
                                            <div class="mt-1 flex flex-wrap gap-1">
                                                @if($doc->version_number)<span class="{{ $chipBase }} {{ $neutral }}">v{{ $doc->version_number }}</span>@endif
                                                @if($doc->document_year)<span class="{{ $chipBase }} {{ $neutral }}">{{ $doc->document_year }}</span>@endif
                                                <span class="{{ $chipBase }} {{ $doc->is_original ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300' : $neutral }}">{{ $doc->is_original ? 'Original' : 'Copy' }}</span>
                                                @if($doc->is_confidential)<span class="{{ $chipBase }} bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300"><i class="bi bi-lock" aria-hidden="true"></i>Confidential</span>@endif
                                                @if($doc->is_latest_version ?? false)<span class="{{ $chipBase }} bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300">Latest</span>@endif
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 align-top text-gray-700 dark:text-gray-300">{{ $doc->document_date ? $doc->document_date->format('M d, Y') : '—' }}</td>
                                        <td class="whitespace-nowrap px-4 py-3 align-top">
                                            @if($doc->expiry_date)
                                                @php
                                                    $exp = $doc->isExpired() ? ['bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-300', 'Expired']
                                                        : ($doc->isExpiringSoon() ? ['bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300', 'Expiring soon'] : [$neutral, null]);
                                                @endphp
                                                <span class="{{ $chipBase }} {{ $exp[0] }}">{{ $doc->expiry_date->format('M d, Y') }}@if($exp[1]) <span class="sr-only">({{ $exp[1] }})</span><i class="bi bi-exclamation-circle" aria-hidden="true"></i>@endif</span>
                                                @if($exp[1])<p class="mt-1 text-xs text-gray-600 dark:text-gray-400">{{ $exp[1] }}</p>@endif
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 align-top">
                                            <span class="{{ $chipBase }} {{ $statusChip[$status] ?? $neutral }}">{{ ucfirst($status) }}</span>
                                            @if($status === 'rejected' && $doc->remarks)
                                                <p class="mt-1 max-w-[14rem] text-xs text-red-700 dark:text-red-400" title="{{ $doc->remarks }}">Reason: {{ Str::limit($doc->remarks, 60) }}</p>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 text-right align-top print:hidden">
                                            @if($doc->file_path)
                                                <a href="{{ route('mydocuments.download', $doc) }}" class="inline-flex rounded p-2 text-emerald-700 hover:bg-emerald-50 dark:text-emerald-400 dark:hover:bg-emerald-900/30" aria-label="Download {{ $doc->title }}" title="Download"><i class="bi bi-download"></i></a>
                                            @endif
                                            <a href="{{ route('mydocuments.edit', ['employee' => $employee, 'document' => $doc]) }}" class="inline-flex rounded p-2 text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-900/30" aria-label="Edit {{ $doc->title }}" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                            <button type="button" onclick="deleteDocument({{ $doc->id }}, @js($doc->title))" class="inline-flex rounded p-2 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/30" aria-label="Delete {{ $doc->title }}" title="Delete"><i class="bi bi-trash"></i></button>
                                            <form id="delete-form-{{ $doc->id }}" action="{{ route('mydocuments.destroy', ['employee' => $employee, 'document' => $doc]) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            @endforeach

            <p id="noMatches" class="hidden rounded-xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-600 dark:border-gray-600 dark:text-gray-400" role="status">
                No documents match your search. Try a different word or clear the status filter.
            </p>
        @else
            <section class="rounded-xl border border-dashed border-gray-300 bg-white p-12 text-center dark:border-gray-600 dark:bg-gray-800">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-indigo-50 text-3xl text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-300"><i class="bi bi-inbox" aria-hidden="true"></i></div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">No documents uploaded yet</h2>
                <p class="mx-auto mt-1 max-w-md text-sm text-gray-600 dark:text-gray-400">Start with the required documents listed above. Accepted files will go to HR for approval.</p>
                <a href="{{ route('mydocuments.create', $employee) }}" class="{{ $btnPrimary }} mt-5"><i class="bi bi-upload"></i> Upload your first document</a>
            </section>
        @endif
    @endif
</div>
@endsection

@push('scripts')
<script>
function deleteDocument(id, title) {
    const msg = `“${title}” will be removed from your 201 file. This can’t be undone.`;
    const go = () => document.getElementById('delete-form-' + id).submit();
    if (window.Swal) {
        Swal.fire({ title: 'Delete this document?', text: msg, icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#dc2626', confirmButtonText: 'Delete', cancelButtonText: 'Cancel' })
            .then(r => r.isConfirmed && go());
    } else if (confirm(msg)) { go(); }
}

/* Search + status filter */
(function () {
    const q = document.getElementById('docSearch'), s = document.getElementById('docStatus');
    if (!q) return;
    const rows = [...document.querySelectorAll('.doc-row')], cats = [...document.querySelectorAll('.doc-category')];
    const apply = () => {
        const term = q.value.trim().toLowerCase(), st = s.value;
        rows.forEach(r => r.classList.toggle('hidden',
            !(r.textContent.toLowerCase().includes(term) && (!st || r.dataset.status === st))));
        cats.forEach(c => c.classList.toggle('hidden', !c.querySelector('.doc-row:not(.hidden)')));
        document.getElementById('noMatches').classList.toggle('hidden', cats.some(c => !c.classList.contains('hidden')));
    };
    q.addEventListener('input', apply); s.addEventListener('change', apply);
})();
</script>
@endpush