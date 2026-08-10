@props([
    'hover' => false,
    'padded' => true,
])

@php
    $baseClasses = 'bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl';

    $hoverClasses = $hover
        ? 'transition-shadow duration-200 hover:shadow-lg dark:hover:shadow-neutral-900/50 hover:border-neutral-300 dark:hover:border-neutral-700'
        : '';

    $paddingClasses = $padded ? 'p-6' : '';

    $classes = "$baseClasses $hoverClasses $paddingClasses";
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
