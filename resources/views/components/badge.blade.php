@props([
    'variant' => 'neutral',
])

@php
    $classes = match ($variant) {
        'primary' => 'bg-primary-100 text-primary-800 dark:bg-primary-900/40 dark:text-primary-300',
        'accent' => 'bg-accent-100 text-accent-800 dark:bg-accent-900/40 dark:text-accent-300',
        'neutral' => 'bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300',
        'success' => 'bg-success-50 text-success-700 dark:bg-success-500/20 dark:text-success-400',
        default => 'bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full $classes"]) }}>
    {{ $slot }}
</span>
