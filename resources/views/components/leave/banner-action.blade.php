{{-- resources/views/components/leave/banner-action.blade.php --}}
@props(['href' => null, 'icon' => null, 'variant' => 'default'])
@php
    $base = 'inline-flex items-center gap-2 rounded-lg border px-4 py-2 text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-300 print:hidden';
    $variants = [
        'default' => 'bg-white/10 hover:bg-white/20 text-white border-white/20',
        'primary' => 'bg-sky-500 hover:bg-sky-400 text-white border-sky-400',
        'danger'  => 'bg-red-500/90 hover:bg-red-500 text-white border-red-400',
        'subtle'  => 'bg-transparent hover:bg-white/10 text-blue-100 border-white/10',
    ];
    $cls = $base . ' ' . ($variants[$variant] ?? $variants['default']);
@endphp
@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $cls]) }}>
        @if ($icon)<i class="bi {{ $icon }}" aria-hidden="true"></i>@endif{{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'button', 'class' => $cls]) }}>
        @if ($icon)<i class="bi {{ $icon }}" aria-hidden="true"></i>@endif{{ $slot }}
    </button>
@endif