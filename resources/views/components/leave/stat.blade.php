{{-- resources/views/components/leave/stat.blade.php --}}
@props([
    'label',
    'value',
    'gradient' => 'bg-gradient-to-r from-indigo-500 to-indigo-700',
    'icon' => 'bi-bar-chart',
    'iconBg' => 'bg-white/20',
    'iconColor' => 'text-white',
    'unit' => null,
    'hint' => null,
])

<div {{ $attributes->merge(['class' => trim($gradient . ' overflow-hidden rounded-xl border border-white/10 p-4 shadow-sm')]) }}>
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="text-sm font-medium text-white/90">{{ $label }}</p>
            <p class="mt-1 text-2xl font-bold tabular-nums text-white">
                {{ $value }}@if ($unit)<span class="ml-1 text-sm font-medium text-white/80">{{ $unit }}</span>@endif
            </p>
            @if ($hint)
                <p class="mt-0.5 text-xs text-white/80">{{ $hint }}</p>
            @endif
        </div>
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ $iconBg }}">
            <i class="bi {{ $icon }} text-lg {{ $iconColor }}" aria-hidden="true"></i>
        </span>
    </div>
</div>