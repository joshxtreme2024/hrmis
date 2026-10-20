{{-- Gradient stat card, same look as the Roles & Permissions page --}}
@props(['label', 'value', 'gradient', 'icon' => 'bi-bar-chart', 'iconBg' => 'bg-indigo-100', 'iconColor' => 'text-indigo-600', 'unit' => null, 'hint' => null])

<div class="{{ $gradient }} rounded-xl p-4 border border-indigo-100">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-white font-medium">{{ $label }}</p>
            <p class="text-2xl font-bold text-white">{{ $value }}@if ($unit)<span class="ml-1 text-sm font-medium text-white/80">{{ $unit }}</span>@endif</p>
            @if ($hint)<p class="mt-0.5 text-xs text-white/80">{{ $hint }}</p>@endif
        </div>
        <div class="w-10 h-10 {{ $iconBg }} rounded-lg flex items-center justify-center">
            <i class="bi {{ $icon }} text-lg {{ $iconColor }}"></i>
        </div>
    </div>
</div>