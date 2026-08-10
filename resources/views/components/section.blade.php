@props([
    'title' => null,
    'eyebrow' => null,
    'align' => 'left',
])

@php
    $alignClasses = match ($align) {
        'center' => 'text-center',
        default => 'text-left',
    };
@endphp

<section {{ $attributes->merge(['class' => 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16']) }}>
    @if ($title || $eyebrow)
        <div class="mb-8 sm:mb-12 {{ $alignClasses }}">
            @if ($eyebrow)
                <p class="text-sm font-medium text-primary-600 dark:text-primary-400 uppercase tracking-wider mb-2">
                    {{ $eyebrow }}
                </p>
            @endif
            @if ($title)
                <x-heading level="2">{{ $title }}</x-heading>
            @endif
        </div>
    @endif

    {{ $slot }}
</section>
