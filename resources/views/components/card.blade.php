@props([
    'hover' => false,
    'padded' => true,
])

@php
    $baseClasses = 'border-2 border-neo-text bg-neo-panel';

    $hoverClasses = $hover
        ? 'shadow-neo transition-all duration-150 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-neo-lg active:translate-x-0 active:translate-y-0 active:shadow-none'
        : 'shadow-neo-sm';

    $paddingClasses = $padded ? 'p-5 sm:p-6' : '';

    $classes = "$baseClasses $hoverClasses $paddingClasses";
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
