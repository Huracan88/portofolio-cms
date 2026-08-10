@props([
    'level' => 1,
    'size' => null,
    'weight' => '600',
])

@php
    $weightClass = match ($weight) {
        '400' => 'font-normal',
        '500' => 'font-medium',
        '600' => 'font-semibold',
        '700' => 'font-bold',
        default => 'font-semibold',
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
            '4xl' => 'text-4xl',
            default => 'text-base',
        },
        $level === 1 => 'text-3xl sm:text-4xl',
        $level === 2 => 'text-2xl sm:text-3xl',
        $level === 3 => 'text-xl sm:text-2xl',
        default => 'text-base',
    };

    $tag = 'h' . min(max($level, 1), 3);
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => "font-display tracking-tight text-neutral-900 dark:text-white $sizeClass $weightClass"]) }}>
    {{ $slot }}
</{{ $tag }}>
