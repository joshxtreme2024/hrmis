{{-- Page header banner: same pattern as System Settings / Roles & Permissions --}}
@props(['title', 'subtitle' => null, 'icon' => 'bi-calendar2-check'])

<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8 border px-6 py-6 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg">
    <div class="flex items-center gap-4">
        <div class="flex-shrink-0">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                <i class="bi {{ $icon }} text-3xl text-indigo-600"></i>
            </div>
        </div>
        <div>
            <h1 class="text-3xl font-bold text-white">{{ $title }}</h1>
            @if ($subtitle)
                <p class="text-sm text-gray-100 mt-1">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
    @isset($actions)
        <div class="flex flex-wrap items-center gap-2">{{ $actions }}</div>
    @endisset
</div>