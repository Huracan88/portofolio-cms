@props(['project'])

@if ($project->galleryImages->isNotEmpty())
    @php
        $galleryItems = $project->galleryImages
            ->filter(fn (App\Models\ProjectGalleryImage $image): bool => $image->media !== null)
            ->map(fn (App\Models\ProjectGalleryImage $image): array => [
                'url' => $image->media->url,
                'caption' => $image->caption,
                'alt' => $image->alt ?? $project->title,
            ])
            ->values()
            ->all();

        $galleryDataJson = Illuminate\Support\Js::encode($galleryItems);
    @endphp

    @if (! empty($galleryItems))
        <div class="mt-8">
            <p class="font-mono text-xs font-bold tracking-[0.3em] text-neo-muted uppercase">// {{ __('GALLERY') }}</p>
            <x-heading level="2" size="2xl" class="mt-3 mb-6">{{ __('PROJECT GALLERY') }}</x-heading>

            <div
                x-data="{
                    items: [],
                    current: 0,
                    open: false,

                    init() {
                        this.items = JSON.parse(this.$refs.galleryData.textContent);
                    },

                    openAt(index) {
                        this.current = index;
                        this.open = true;
                    },

                    next() {
                        if (! this.open || ! this.items.length) return;
                        this.current = (this.current + 1) % this.items.length;
                    },

                    prev() {
                        if (! this.open || ! this.items.length) return;
                        this.current = (this.current - 1 + this.items.length) % this.items.length;
                    },

                    get total() {
                        return this.items.length;
                    },
                }"
            >
                <script type="application/json" x-ref="galleryData">{!! $galleryDataJson !!}</script>

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4">
                    @foreach ($galleryItems as $index => $item)
                        <button
                            type="button"
                            class="group relative block aspect-[4/3] w-full overflow-hidden border-2 border-neo-text bg-neo-panel-deep shadow-neo-sm transition-all duration-150 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-neo active:translate-x-0 active:translate-y-0 active:shadow-none focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neo-text"
                            @click="openAt({{ $index }})"
                            aria-label="{{ __('Open image') }} {{ $index + 1 }}"
                        >
                            <img
                                src="{{ $item['url'] }}"
                                alt="{{ $item['alt'] }}"
                                loading="lazy"
                                decoding="async"
                                class="h-full w-full object-cover grayscale transition-all duration-300 group-hover:grayscale-0"
                            >
                            <span class="absolute bottom-2 right-2 border-2 border-neo-text bg-neo-bg px-1.5 py-0.5 font-mono text-[9px] font-bold tracking-widest text-neo-text transition-colors group-hover:bg-neo-text group-hover:text-neo-bg">↗</span>
                        </button>
                    @endforeach
                </div>

                {{-- Lightbox --}}
                <div
                    class="fixed inset-0 z-50 bg-neo-bg/90 backdrop-blur-sm"
                    x-cloak
                    x-show="open"
                    role="dialog"
                    aria-modal="true"
                    aria-label="{{ __('Project gallery') }}"
                    @keydown.escape.window="open = false"
                    @keydown.arrow-right.window="next()"
                    @keydown.arrow-left.window="prev()"
                    x-effect="document.body.style.overflow = open ? 'hidden' : ''"
                    @navigate.window="document.body.style.overflow = ''"
                    wire:ignore
                >
                    <div class="absolute inset-x-0 top-0 flex items-center justify-between border-b-2 border-neo-text bg-neo-bg/70 px-4 py-3 backdrop-blur-sm">
                        <span class="border-2 border-neo-text bg-neo-bg/70 px-2 py-0.5 font-mono text-[10px] font-bold tracking-widest text-neo-text">
                            <span x-text="String(current + 1).padStart(2, '0')">01</span>
                            <span class="text-neo-muted">/</span>
                            <span x-text="String(total).padStart(2, '0')">00</span>
                        </span>
                        <button
                            type="button"
                            class="flex h-10 w-10 items-center justify-center border-2 border-neo-text bg-neo-bg font-mono text-lg font-bold text-neo-text shadow-neo-sm transition-all duration-150 hover:-translate-x-1 hover:-translate-y-1 hover:bg-neo-text hover:text-neo-bg active:translate-x-0 active:translate-y-0 active:shadow-none"
                            @click="open = false"
                            aria-label="{{ __('Close gallery') }}"
                        >×</button>
                    </div>

                    <div class="absolute left-2 top-1/2 z-10 -translate-y-1/2">
                        <button
                            type="button"
                            class="flex h-12 w-12 items-center justify-center border-2 border-neo-text bg-neo-bg font-mono text-lg font-bold text-neo-text shadow-neo-sm transition-all duration-150 hover:-translate-x-1 hover:-translate-y-1 hover:bg-neo-text hover:text-neo-bg active:translate-x-0 active:translate-y-0 active:shadow-none"
                            @click="prev()"
                            aria-label="{{ __('Previous image') }}"
                        >◀</button>
                    </div>
                    <div class="absolute right-2 top-1/2 z-10 -translate-y-1/2">
                        <button
                            type="button"
                            class="flex h-12 w-12 items-center justify-center border-2 border-neo-text bg-neo-bg font-mono text-lg font-bold text-neo-text shadow-neo-sm transition-all duration-150 hover:-translate-x-1 hover:-translate-y-1 hover:bg-neo-text hover:text-neo-bg active:translate-x-0 active:translate-y-0 active:shadow-none"
                            @click="next()"
                            aria-label="{{ __('Next image') }}"
                        >▶</button>
                    </div>

                    <div class="flex h-full w-full items-center justify-center p-4 pb-24 pt-20">
                        <img
                            :src="items[current].url"
                            :alt="items[current].alt"
                            class="max-h-[80vh] max-w-[90vw] border-2 border-neo-text object-contain shadow-neo-lg"
                        >
                    </div>

                    <div class="absolute inset-x-0 bottom-0 border-t-2 border-neo-text bg-neo-bg/70 px-4 py-3 backdrop-blur-sm" x-show="items[current].caption">
                        <p class="font-mono text-xs font-bold uppercase tracking-widest text-neo-text" x-text="items[current].caption"></p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif