{{-- resources/views/pds/_records.blade.php
     Shared table for every list section. Receives: $key, $c (section config), $heading (h2|h3) --}}
@php
    $items = $c['items'] ?? collect();
    $add = 'inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700';
@endphp

<div class="mb-4 flex items-start justify-between gap-3">
    <div>
        <{{ $heading }} class="text-base font-semibold text-gray-900 dark:text-white">{{ $c['title'] }}</{{ $heading }}>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $c['help'] ?? '' }}</p>
    </div>
    @if(isset($c['addUrl']))
        <a href="{{ $c['addUrl'] }}" class="{{ $add }}"><i class="bi bi-plus-lg"></i> Add {{ $c['noun'] }}</a>
    @else
        <button type="button" onclick="openCrud('{{ $key }}')" class="{{ $add }}"><i class="bi bi-plus-lg"></i> Add {{ $c['noun'] }}</button>
    @endif
</div>

@if($items && $items->count())
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900/30">
                <tr>
                    @foreach(array_keys($c['cols']) as $th)
                        <th scope="col" class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-400">{{ $th }}</th>
                    @endforeach
                    <th scope="col" class="px-4 py-3 text-right font-medium text-gray-600 dark:text-gray-400"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                @foreach($items as $row)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                        @foreach($c['cols'] as $render)
                            <td class="px-4 py-3 align-top text-gray-700 dark:text-gray-300">{!! $render($row) !!}</td>
                        @endforeach
                        <td class="whitespace-nowrap px-4 py-3 text-right align-top">
                            <button type="button" onclick="openCrud('{{ $key }}', {{ $row->id }})" class="rounded p-1.5 text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-900/30" aria-label="Edit {{ $c['noun'] }}"><i class="bi bi-pencil-square"></i></button>
                            <button type="button" onclick="deleteCrud('{{ $key }}', {{ $row->id }})" class="rounded p-1.5 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/30" aria-label="Delete {{ $c['noun'] }}"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="rounded-lg border border-dashed border-gray-300 p-8 text-center dark:border-gray-600">
        <p class="font-medium text-gray-900 dark:text-white">No {{ $c['noun'] }}s yet</p>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Use “Add {{ $c['noun'] }}” to create your first one.</p>
    </div>
@endif