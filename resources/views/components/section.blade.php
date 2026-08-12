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

<section {{ $attributes->merge(['class' => 'relative mx-auto max-w-7xl scroll-mt-20 px-4 sm:px-6 lg:px-8 py-16 sm:py-24']) }}>
    @if ($title || $eyebrow)
        <div class="mb-10 sm:mb-14 {{ $alignClasses }}">
            @if ($eyebrow)
                <p class="font-mono text-xs font-bold tracking-[0.3em] text-neo-muted uppercase mb-3">// {{ $eyebrow }}</p>
            @endif
            @if ($title)
                <x-heading level="2">{{ $title }}</x-heading>
            @endif
        </div>
    @endif

    {{ $slot }}
</section>
