@props([
    'level' => 1,
    'size' => null,
    'weight' => 'heavy',
])

@php
    $fontClass = match ($weight) {
        'heavy' => $level >= 3 ? 'font-display font-bold' : 'font-display-heavy',
        '400' => 'font-display font-normal',
        '500' => 'font-display font-medium',
        '600' => 'font-display font-semibold',
        '700' => 'font-display font-bold',
        default => $level >= 3 ? 'font-display font-bold' : 'font-display-heavy',
    };

    $sizeClass = match (true) {
        $size !== null => match ($size) {
            'xs' => 'text-xs',
            'sm' => 'text-sm',
            'md' => 'text-base',
            'lg' => 'text-lg',
            'xl' => 'text-xl',
            '2xl' => 'text-2xl',
            '3xl' => 'text-3xl',
            '4xl' => 'text-4xl sm:text-5xl',
            '5xl' => 'text-5xl sm:text-6xl lg:text-7xl',
            'hero' => 'text-6xl sm:text-7xl lg:text-8xl',
            default => 'text-base',
        },
        $level === 1 => 'text-4xl sm:text-5xl',
        $level === 2 => 'text-3xl sm:text-4xl',
        $level === 3 => 'text-xl sm:text-2xl',
        default => 'text-base',
    };

    $tag = 'h' . min(max($level, 1), 3);
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => "tracking-tight text-neo-text uppercase leading-[0.95] $sizeClass $fontClass"]) }}>
    {{ $slot }}
</{{ $tag }}>
