@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
    'target' => null,
    'icon' => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-md transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-neutral-950 disabled:opacity-50 disabled:cursor-not-allowed';

    $variantClasses = match ($variant) {
        'primary' => 'bg-primary-600 text-white hover:bg-primary-700 dark:bg-primary-600 dark:hover:bg-primary-500',
        'secondary' => 'bg-neutral-100 text-neutral-800 hover:bg-neutral-200 dark:bg-neutral-800 dark:text-neutral-200 dark:hover:bg-neutral-700',
        'ghost' => 'bg-transparent text-neutral-700 hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800',
        default => 'bg-primary-600 text-white hover:bg-primary-700',
    };

    $sizeClasses = match ($size) {
        'sm' => 'text-sm px-3 py-1.5 gap-1.5',
        'md' => 'text-sm px-4 py-2 gap-2',
        'lg' => 'text-base px-6 py-2.5 gap-2',
        default => 'text-sm px-4 py-2 gap-2',
    };

    $classes = "$baseClasses $variantClasses $sizeClasses";
@endphp

@if ($href)
    <a
        href="{{ $href }}"
        {{ $attributes->merge(['class' => $classes]) }}
        @if ($target) target="{{ $target }}" @endif
        @if (str_starts_with($href, url('/'))) wire:navigate @endif
    >
        @if ($icon)
            <x-svg-icon :name="$icon" class="w-4 h-4" />
        @endif
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $attributes->merge(['class' => $classes]) }}
    >
        @if ($icon)
            <x-svg-icon :name="$icon" class="w-4 h-4" />
        @endif
        {{ $slot }}
    </button>
@endif
