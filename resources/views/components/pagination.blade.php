@props(['paginator'])

@php
    /** @var \Illuminate\Contracts\Pagination\Paginator $paginator */

    $navLinkClasses = 'border-2 border-neo-text bg-neo-panel px-3 py-2 font-mono text-[10px] font-bold uppercase tracking-widest text-neo-text shadow-neo-sm transition-all duration-150 hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-neo active:translate-x-0 active:translate-y-0 active:shadow-none';
    $navDisabledClasses = 'border-2 border-neo-line px-3 py-2 font-mono text-[10px] font-bold uppercase tracking-widest text-neo-muted opacity-40 cursor-not-allowed';
    $pageLinkClasses = 'border-2 border-neo-line bg-neo-panel px-3 py-2 font-mono text-[10px] font-bold uppercase tracking-widest text-neo-muted shadow-neo-sm transition-all duration-150 hover:-translate-x-0.5 hover:-translate-y-0.5 hover:border-neo-text hover:text-neo-text hover:shadow-neo active:translate-x-0 active:translate-y-0 active:shadow-none';
    $pageCurrentClasses = 'border-2 border-neo-text bg-neo-text px-3 py-2 font-mono text-[10px] font-bold uppercase tracking-widest text-neo-bg';
    $ellipsisClasses = 'px-3 py-2 font-mono text-[10px] font-bold tracking-widest text-neo-muted';
@endphp

@if ($paginator->hasPages())
    <nav class="flex items-center justify-center gap-2" aria-label="Pagination">
        {{-- Previous page --}}
        @if ($paginator->onFirstPage())
            <span class="{{ $navDisabledClasses }}" aria-disabled="true">← PREV</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" wire:navigate rel="prev" class="{{ $navLinkClasses }}">← PREV</a>
        @endif

        {{-- Page numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="{{ $ellipsisClasses }}" aria-hidden="true">[ ... ]</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="{{ $pageCurrentClasses }}" aria-current="page">[ {{ $page }} ]</span>
                    @else
                        <a href="{{ $url }}" wire:navigate class="{{ $pageLinkClasses }}">[ {{ $page }} ]</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next page --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" wire:navigate rel="next" class="{{ $navLinkClasses }}">NEXT →</a>
        @else
            <span class="{{ $navDisabledClasses }}" aria-disabled="true">NEXT →</span>
        @endif
    </nav>
@endif
