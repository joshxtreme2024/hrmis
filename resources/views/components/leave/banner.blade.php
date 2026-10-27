{{-- resources/views/components/leave/banner.blade.php --}}
@props(['title', 'subtitle' => null, 'icon' => 'bi-calendar2-check'])

<div {{ $attributes->merge([
        'class' => 'mb-6 flex flex-col gap-4 overflow-hidden rounded-xl text-blue-100 shadow-sm sm:flex-row sm:items-center sm:justify-between',
    ]) }}
     style="background-image: linear-gradient(to bottom right, #0a2463, #06173f);">
    <div class="flex items-center gap-4 p-6">
        <span class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-white/10 ring-1 ring-white/20">
            <i class="bi {{ $icon }} text-3xl text-white" aria-hidden="true"></i>
        </span>
        <div class="min-w-0">
            <h1 class="text-2xl font-semibold text-white sm:text-3xl">{{ $title }}</h1>
            @if ($subtitle)
                <p class="mt-1 text-sm text-blue-200">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
    @isset($actions)
        <div class="flex flex-wrap items-center gap-2 px-6 pb-6 sm:pb-0 sm:pr-6">{{ $actions }}</div>
    @endisset
</div>