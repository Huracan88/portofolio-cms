@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
    'target' => null,
    'icon' => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-mono text-xs font-bold uppercase tracking-widest border-2 transition-all duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neo-text focus-visible:ring-offset-2 focus-visible:ring-offset-neo-bg disabled:opacity-40 disabled:cursor-not-allowed';

    $variantClasses = match ($variant) {
        'primary' => 'bg-neo-text text-neo-bg border-neo-text shadow-neo hover:-translate-x-1 hover:-translate-y-1 hover:shadow-neo-lg active:translate-x-0 active:translate-y-0 active:shadow-none',
        'secondary' => 'bg-neo-panel text-neo-text border-neo-text shadow-neo-sm hover:-translate-x-1 hover:-translate-y-1 hover:bg-neo-panel-deep hover:shadow-neo active:translate-x-0 active:translate-y-0 active:shadow-none',
        'ghost' => 'bg-transparent text-neo-muted border-neo-line hover:bg-neo-panel hover:text-neo-text hover:border-neo-text hover:shadow-neo-sm',
        default => 'bg-neo-text text-neo-bg border-neo-text shadow-neo hover:-translate-x-1 hover:-translate-y-1 hover:shadow-neo-lg active:translate-x-0 active:translate-y-0 active:shadow-none',
    };

    $sizeClasses = match ($size) {
        'sm' => 'px-4 py-2 gap-1.5',
        'md' => 'px-5 py-3 gap-2',
        'lg' => 'px-6 py-3.5 gap-2',
        default => 'px-5 py-3 gap-2',
    };

    $classes = "$baseClasses $variantClasses $sizeClasses";
@endphp

@if ($href)
    <a
        href="{{ $href }}"
        {{ $attributes->merge(['class' => $classes]) }}
        @if ($target) target="{{ $target }}" @endif
        @if ($target === '_blank') rel="noopener noreferrer" @endif
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
