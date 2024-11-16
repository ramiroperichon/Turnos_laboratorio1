@props([
    'text' => '',
    'position' => 'top'
])

@php
    $positionClasses = [
        'top' => 'bottom-full left-1/2 -translate-x-1/2 mb-2',
        'bottom' => 'top-full left-1/2 -translate-x-1/2 mt-2',
        'left' => 'right-full top-1/2 -translate-y-1/2 mr-2',
        'right' => 'left-full top-1/2 -translate-y-1/2 ml-2',
    ];

    $arrowClasses = [
        'top' => 'top-full left-1/2 -translate-x-1/2 -translate-y-1/2 border-t-gray-800',
        'bottom' => 'bottom-full left-1/2 -translate-x-1/2 translate-y-1/2 border-b-gray-800',
        'left' => 'left-full top-1/2 -translate-y-1/2 -translate-x-1/2 border-l-gray-800',
        'right' => 'right-full top-1/2 -translate-y-1/2 translate-x-1/2 border-r-gray-800',
    ];
@endphp

<div class="relative inline-block group min-w-fit">
    {{ $slot }}
    <div role="tooltip" class="absolute scale-0 z-10 px-3 py-2 text-sm font-medium whitespace-normal break-words rounded-lg bg-body-secondary text-white opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:scale-100 transition-all duration-200 {{ $positionClasses[$position] }}">
        <p class="p-0 m-0 text-nowrap">{{ $text }}</p>
        <div class="absolute w-2 h-2 bg-body-secondary transform rotate-45 {{ $arrowClasses[$position] }}"></div>
    </div>
</div>
