{{-- Translucent button used inside the banner. Renders <a> when href is given, otherwise <button>. --}}
@props(['href' => null, 'icon' => null])
@php $cls = 'inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-sm rounded-lg transition-all duration-200 border border-white/20'; @endphp
@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $cls]) }}>@if ($icon)<i class="bi {{ $icon }} mr-2"></i>@endif{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['type' => 'button', 'class' => $cls]) }}>@if ($icon)<i class="bi {{ $icon }} mr-2"></i>@endif{{ $slot }}</button>
@endif