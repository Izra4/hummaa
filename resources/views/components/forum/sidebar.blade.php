@props([
    'active' => false,
    'iconInactive',
    'iconActive'
])

@php
    $baseClasses = 'flex w-full items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-colors';
    $activeClasses = 'bg-sidebar-active text-main-bg border-l-4 border-main-bg';
    $inactiveClasses = 'text-gray-700 hover:bg-gray-50';
@endphp

<a {{ $attributes->merge(['class' => $baseClasses . ' ' . ($active ? $activeClasses : $inactiveClasses)]) }}>
    {{-- Di sini kita gunakan kondisi untuk memilih source gambar --}}
    <img src="{{ asset('images/' . ($active ? $iconActive : $iconInactive)) }}" class="h-6 w-6 shrink-0">
    <span>{{ $slot }}</span>
</a>
