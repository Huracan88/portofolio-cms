@props([
    'variant' => 'neutral',
])

@php
    $classes = match ($variant) {
        'primary' => 'border-2 border-neo-text bg-neo-text text-neo-bg',
        'secondary' => 'border-2 border-neo-text bg-neo-panel text-neo-text',
        'muted' => 'border-2 border-neo-line bg-neo-bg text-neo-muted',
        'accent' => 'border-2 border-neo-text bg-neo-text text-neo-bg',
        default => 'border-2 border-neo-line bg-neo-bg text-neo-text',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2 py-0.5 font-mono text-[10px] font-bold uppercase tracking-widest $classes"]) }}>
    {{ $slot }}
</span>
