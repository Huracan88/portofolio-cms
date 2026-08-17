<div class="relative overflow-hidden bg-neo-bg text-neo-text">
    @section('meta_description', $profile?->title ?? __('Senior Fullstack Developer & Software Engineer — 17+ years building custom web solutions.'))
    @section('meta_type', 'profile')
    @if($profile?->photo_url)
        @section('meta_image', asset($profile->photo_url))
    @endif

    @php
        $ticker = ['LARAVEL', 'LIVEWIRE', 'FILAMENT', 'TAILWIND', 'MARIADB', 'DOCKER', 'PHP', 'PEST', 'VITE'];
        $sprite = [
            '...WW...WW....',
            '..WGGW..WGG...',
            '.WGGGG..WGGG..',
            '.WGGGG..WGGG..',
            'WGGGGGW.WGGG..',
            'WGGWWGGWWGGG..',
            'WGGWWGGWWWGG..',
            'WGGGGGW..WGG..',
            'WGGGGG...WGGG.',
            'WGGGG....WGGG.',
            'WGGG.....WGGG.',
            'WGGG.....WGGGW',
            'WGG......WWWW.',
        ];
        $spriteColors = [
            'W' => 'bg-neo-text',
            'G' => 'bg-neo-muted',
        ];
        $portraits = [];
        foreach (range(1, 9) as $portraitNumber) {
            $png = public_path('images/gemini-portraits/gemini-portrait-'.$portraitNumber.'.png');
            $extension = is_file($png) ? 'png' : 'jpg';
            $portraits[] = ['number' => $portraitNumber, 'extension' => $extension];
        }
    @endphp

    {{-- ============================== HERO ============================== --}}
    <section class="relative">
        {{-- Background grid + decorations --}}
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <div class="absolute inset-0 opacity-[0.05]"
                 style="background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px); background-size: 44px 44px;"></div>
            <span class="absolute top-10 left-4 sm:left-8 font-mono text-lg text-neo-text/20">+</span>
            <span class="absolute top-24 right-6 font-mono text-sm text-neo-text/20">×</span>
            <span class="absolute bottom-16 left-[12%] font-mono text-sm text-neo-text/20">#</span>
            <span class="absolute bottom-24 right-[8%] font-mono text-lg text-neo-text/20">+</span>
        </div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-16 pb-20 sm:pt-24 lg:pt-28 lg:pb-28">
            <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
                {{-- Hero copy --}}
                <div class="text-center lg:text-left">
                    {{-- Status badge --}}
                    <div class="inline-flex items-center gap-2 border-2 border-neo-text bg-neo-panel px-3 py-1.5 font-mono text-[10px] sm:text-xs font-bold tracking-widest text-neo-text shadow-neo-sm">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping bg-neo-text opacity-60"></span>
                            <span class="relative inline-flex h-2 w-2 bg-neo-text"></span>
                        </span>
                        [ {{ __('STATUS: READY FOR PRODUCTION') }} ]
                    </div>

                    {{-- Headline --}}
                    <h1 class="mt-6 font-display-heavy text-6xl leading-[0.92] tracking-tight text-neo-text uppercase sm:text-7xl lg:text-8xl">
                        <span class="block">{{ __('FULLSTACK') }}</span>
                        <span class="block">
                            <span class="inline-block bg-neo-text px-3 text-neo-bg shadow-neo-sm">{{ __('DEVELOPER') }}</span>
                        </span>
                        <span class="block">{{ __('& ENGINEER') }}</span>
                    </h1>

                    <p class="mt-6 font-mono text-xs tracking-widest text-neo-muted uppercase sm:text-sm">
                        [ {{ $profile?->full_name ?? 'Andrés Pinto' }} ] — {{ $profile?->location ?? __('Chetumal, Quintana Roo, México') }}
                    </p>

                    <p class="mx-auto mt-4 max-w-xl text-base text-neo-muted lg:mx-0">
                        {{ __('Building custom web solutions since 2008 across government, enterprise and fintech sectors.') }}
                    </p>

                    {{-- CTAs --}}
                    <div class="mt-8 flex flex-wrap items-center justify-center gap-4 lg:justify-start">
                        <a href="{{ route('contact') }}" wire:navigate class="group inline-flex items-center gap-2 bg-neo-text px-6 py-3.5 font-mono text-xs font-bold uppercase tracking-widest text-neo-bg border-2 border-neo-text shadow-neo transition-all duration-150 hover:-translate-x-1 hover:-translate-y-1 active:translate-x-0 active:translate-y-0 active:shadow-none">
                            {{ __('START A PROJECT') }}
                            <span class="transition-transform duration-150 group-hover:translate-x-1">→</span>
                        </a>
                        <a href="{{ route('projects.index') }}" wire:navigate class="inline-flex items-center gap-2 border-2 border-neo-text px-6 py-3.5 font-mono text-xs font-bold uppercase tracking-widest text-neo-text shadow-neo-sm transition-all duration-150 hover:-translate-x-1 hover:-translate-y-1 hover:bg-neo-panel active:translate-x-0 active:translate-y-0 active:shadow-none">
                            {{ __('VIEW PROJECTS') }}
                        </a>
                    </div>

                    {{-- Mini stats --}}
                    <div class="mx-auto mt-10 grid max-w-md grid-cols-3 gap-3 lg:mx-0">
                        <div class="border-2 border-neo-text bg-neo-panel p-3 text-center shadow-neo-sm">
                            <p class="font-display-heavy text-2xl text-neo-text">17+</p>
                            <p class="mt-1 font-mono text-[9px] font-bold tracking-widest text-neo-muted uppercase">{{ __('YEARS') }}</p>
                        </div>
                        <div class="border-2 border-neo-text bg-neo-panel p-3 text-center shadow-neo-sm">
                            <p class="font-display-heavy text-2xl text-neo-text">{{ $featuredProjects->count() }}+</p>
                            <p class="mt-1 font-mono text-[9px] font-bold tracking-widest text-neo-muted uppercase">{{ __('Projects') }}</p>
                        </div>
                        <div class="border-2 border-neo-text bg-neo-panel p-3 text-center shadow-neo-sm">
                            <p class="font-display-heavy text-2xl text-neo-text">{{ $skills->flatten()->count() }}</p>
                            <p class="mt-1 font-mono text-[9px] font-bold tracking-widest text-neo-muted uppercase">{{ __('Skills') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Pixel sprite terminal card --}}
                <div class="relative mx-auto w-full max-w-[26rem] lg:mx-0 lg:justify-self-end">
                    <span class="absolute -top-2 -left-2 z-10 flex h-8 w-8 items-center justify-center border-2 border-neo-text bg-neo-bg font-mono text-sm font-bold text-neo-text shadow-neo-sm">+</span>
                    <span class="absolute -top-2 -right-2 z-10 h-8 w-8 border-2 border-neo-text bg-neo-bg shadow-neo-sm"></span>
                    <span class="absolute -bottom-2 -left-2 z-10 h-8 w-8 border-2 border-neo-text bg-neo-bg shadow-neo-sm"></span>

                    <div class="relative border-2 border-neo-text bg-neo-panel shadow-neo-lg">
                        {{-- Header bar --}}
                        <div class="flex items-center justify-between border-b-2 border-neo-text bg-neo-panel-deep px-4 py-2.5">
                            <span class="font-pixel text-[9px] tracking-widest text-neo-text">AP://SYS.INFO</span>
                            <div class="flex gap-1.5">
                                <span class="h-2 w-2 bg-neo-text"></span>
                                <span class="h-2 w-2 bg-neo-line"></span>
                                <span class="h-2 w-2 bg-neo-muted"></span>
                            </div>
                        </div>

                        {{-- Portrait Carousel — CRT Scanline Style --}}
                        <div
                            class="bg-neo-panel-deep"
                            x-data="{
                                current: 0,
                                total: 9,
                                transitioning: false,
                                autoplayTimer: null,
                                paused: false,

                                init() {
                                    this.startAutoplay();
                                },

                                startAutoplay() {
                                    this.stopAutoplay();
                                    this.autoplayTimer = setInterval(() => {
                                        if (!this.paused && !this.transitioning) {
                                            this.go((this.current + 1) % this.total);
                                        }
                                    }, 5000);
                                },

                                stopAutoplay() {
                                    if (this.autoplayTimer) {
                                        clearInterval(this.autoplayTimer);
                                        this.autoplayTimer = null;
                                    }
                                },

                                go(index) {
                                    if (this.transitioning || index === this.current) return;
                                    this.transitioning = true;
                                    this.current = index;
                                    this.startAutoplay();
                                    setTimeout(() => { this.transitioning = false; }, 350);
                                },

                                next() { this.go((this.current + 1) % this.total); },
                                prev() { this.go((this.current - 1 + this.total) % this.total); },

                                label(i) {
                                    return '{{ __("Portrait") }} ' + (i + 1);
                                }
                            }"
                            @keydown.arrow-right.prevent="next()"
                            @keydown.arrow-left.prevent="prev()"
                            @keydown.home.prevent="go(0)"
                            @keydown.end.prevent="go(total - 1)"
                            @mouseenter="paused = true"
                            @mouseleave="paused = false"
                            @focusin="paused = true"
                            @focusout="paused = false"
                            role="region"
                            aria-roledescription="{{ __('carousel') }}"
                            aria-label="{{ __('AI generated portraits') }}"
                        >
                            {{-- Carousel viewport --}}
                            <div class="relative aspect-[4/3] max-h-64 sm:max-h-72 overflow-hidden">
                                {{-- Slides --}}
                                @foreach ($portraits as $portrait)
                                    <div
                                        class="absolute inset-0 transition-opacity duration-200"
                                        :class="current === {{ $portrait['number'] - 1 }} ? 'opacity-100 z-10' : 'opacity-0 z-0'"
                                        role="group"
                                        aria-roledescription="{{ __('slide') }}"
                                        :aria-label="label({{ $portrait['number'] - 1 }})"
                                        :aria-hidden="current !== {{ $portrait['number'] - 1 }}"
                                        x-show="current === {{ $portrait['number'] - 1 }} || transitioning"
                                    >
                                        <img
                                            src="{{ asset('images/gemini-portraits/gemini-portrait-' . $portrait['number'] . '.' . $portrait['extension']) }}"
                                            alt="{{ __('AI generated pixel portrait :n', ['n' => $portrait['number']]) }}"
                                            class="h-full w-full object-cover"
                                            width="1195"
                                            height="896"
                                            loading="{{ $portrait['number'] === 1 ? 'eager' : 'lazy' }}"
                                            decoding="async"
                                        >
                                    </div>
                                @endforeach

                                {{-- CRT Scanline overlay (always visible, intensifies during transition) --}}
                                <div
                                    class="pointer-events-none absolute inset-0 z-20 opacity-[0.07] animate-scanline-pass"
                                    style="background-image: repeating-linear-gradient(0deg, #fff 0 1px, transparent 1px 3px);"
                                    aria-hidden="true"
                                ></div>

                                {{-- CRT flicker flash during transition --}}
                                <div
                                    class="pointer-events-none absolute inset-0 z-30 bg-neo-text transition-opacity duration-100"
                                    :class="transitioning ? 'opacity-[0.12]' : 'opacity-0'"
                                    aria-hidden="true"
                                ></div>

                                {{-- Previous button --}}
                                <button
                                    type="button"
                                    class="absolute left-2 top-1/2 z-40 flex h-8 w-8 -translate-y-1/2 items-center justify-center border-2 border-neo-text bg-neo-bg/90 font-mono text-sm font-bold text-neo-text shadow-neo-sm transition-all duration-150 hover:-translate-x-0.5 hover:-translate-y-[calc(50%+0.5px)] hover:bg-neo-text hover:text-neo-bg active:translate-x-0 active:translate-y-[-50%] active:shadow-none"
                                    @click="prev()"
                                    aria-label="{{ __('Previous portrait') }}"
                                >◀</button>

                                {{-- Next button --}}
                                <button
                                    type="button"
                                    class="absolute right-2 top-1/2 z-40 flex h-8 w-8 -translate-y-1/2 items-center justify-center border-2 border-neo-text bg-neo-bg/90 font-mono text-sm font-bold text-neo-text shadow-neo-sm transition-all duration-150 hover:-translate-x-0.5 hover:-translate-y-[calc(50%+0.5px)] hover:bg-neo-text hover:text-neo-bg active:translate-x-0 active:translate-y-[-50%] active:shadow-none"
                                    @click="next()"
                                    aria-label="{{ __('Next portrait') }}"
                                >▶</button>

                                {{-- Counter "01 / 09" --}}
                                <div class="absolute top-2 right-2 z-40 border-2 border-neo-text bg-neo-bg/90 px-2 py-0.5 font-mono text-[10px] font-bold tracking-widest text-neo-text">
                                    <span x-text="String(current + 1).padStart(2, '0')">01</span>
                                    <span class="text-neo-muted">/</span>
                                    <span x-text="String(total).padStart(2, '0')">09</span>
                                </div>

                                {{-- Autoplay indicator --}}
                                <div class="absolute top-2 left-2 z-40 flex items-center gap-1.5 border-2 border-neo-text bg-neo-bg/90 px-2 py-0.5 font-mono text-[9px] font-bold tracking-widest text-neo-muted">
                                    <span class="inline-block h-1.5 w-1.5" :class="paused ? 'bg-neo-muted' : 'animate-blink bg-neo-text'"></span>
                                    <span x-text="paused ? '{{ __("PAUSED") }}' : '{{ __("AUTO") }}'">AUTO</span>
                                </div>
                            </div>

                            {{-- Dot indicators --}}
                            <div class="flex items-center justify-center gap-2 border-t-2 border-neo-text bg-neo-panel-deep px-4 py-2.5" role="tablist" aria-label="{{ __('Portrait navigation') }}">
                                @foreach ($portraits as $portrait)
                                    <button
                                        type="button"
                                        class="border-2 px-2 py-0.5 font-pixel text-[8px] transition-all duration-150"
                                        :class="current === {{ $portrait['number'] - 1 }}
                                            ? 'border-neo-text bg-neo-text text-neo-bg shadow-neo-sm animate-dot-pop'
                                            : 'border-neo-muted bg-neo-panel text-neo-muted hover:border-neo-text hover:text-neo-text'"
                                        @click="go({{ $portrait['number'] - 1 }})"
                                        role="tab"
                                        :aria-selected="current === {{ $portrait['number'] - 1 }}"
                                        aria-label="{{ __('Go to portrait :n', ['n' => $portrait['number']]) }}"
                                    >{{ str_pad($portrait['number'], 2, '0', STR_PAD_LEFT) }}</button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Footer bar --}}
                        <div class="border-t-2 border-neo-text bg-neo-panel-deep px-4 py-3">
                            <div class="flex items-center justify-between font-mono text-[10px] tracking-widest">
                                <span class="text-neo-muted">SIG: AP#{{ $profile?->license_number ?? '000000' }}</span>
                                <span class="flex items-center gap-1.5 text-neo-text">
                                    <span class="inline-block h-2 w-2 animate-blink bg-neo-text"></span>
                                    {{ __('OPEN FOR WORK') }}
                                </span>
                            </div>
                            <div class="mt-3 h-8 w-full opacity-80" aria-hidden="true"
                                 style="background-image: repeating-linear-gradient(90deg, #fff 0 2px, transparent 2px 4px, #fff 4px 5px, transparent 5px 9px, #fff 9px 12px, transparent 12px 13px);">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================== MARQUEE DIVIDER ============================== --}}
    <div class="overflow-hidden border-y-2 border-neo-text bg-neo-panel py-3" aria-hidden="true">
        <div class="flex w-max animate-marquee">
            <div class="flex shrink-0 items-center">
                @foreach ($ticker as $tag)
                    <span class="px-4 font-mono text-xs font-bold tracking-[0.2em] text-neo-text">[ {{ $tag }} ]</span>
                @endforeach
            </div>
            <div class="flex shrink-0 items-center">
                @foreach ($ticker as $tag)
                    <span class="px-4 font-mono text-xs font-bold tracking-[0.2em] text-neo-text">[ {{ $tag }} ]</span>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ============================== PROJECTS GRID ============================== --}}
    <section class="relative mx-auto max-w-7xl scroll-mt-20 px-4 sm:px-6 lg:px-8 py-16 sm:py-24" id="projects">
        <div class="flex flex-col items-start gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="font-mono text-xs font-bold tracking-[0.3em] text-neo-muted uppercase">// {{ __('PORTFOLIO') }}</p>
                <h2 class="mt-3 font-display-heavy text-4xl tracking-tight text-neo-text uppercase sm:text-5xl">
                    {{ __('FEATURED WORK') }}
                </h2>
                <p class="mt-3 max-w-xl text-neo-muted">{{ __('A selection of my best projects across government, enterprise and fintech.') }}</p>
            </div>
            <a href="{{ route('projects.index') }}" wire:navigate class="inline-flex shrink-0 items-center gap-2 border-2 border-neo-text px-5 py-3 font-mono text-xs font-bold uppercase tracking-widest text-neo-text shadow-neo-sm transition-all duration-150 hover:-translate-x-1 hover:-translate-y-1 hover:bg-neo-text hover:text-neo-bg active:translate-x-0 active:translate-y-0 active:shadow-none">
                {{ __('VIEW ALL PROJECTS') }} →
            </a>
        </div>

        @if ($featuredProjects->isNotEmpty())
            <div class="mt-12 grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredProjects as $project)
                    <a href="{{ route('projects.show', $project) }}" wire:navigate class="group relative flex h-full flex-col border-2 border-neo-text bg-neo-panel shadow-neo transition-all duration-150 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-neo-lg active:translate-x-0 active:translate-y-0 active:shadow-none focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neo-text">
                        {{-- Corner index chip --}}
                        <span class="absolute -top-2 -left-2 z-10 flex h-7 w-7 items-center justify-center border-2 border-neo-text bg-neo-bg font-mono text-[10px] font-bold text-neo-text shadow-neo-sm transition-all duration-150 group-hover:-translate-x-0.5 group-hover:-translate-y-0.5">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>

                        {{-- Media --}}
                        <div class="relative h-48 overflow-hidden border-b-2 border-neo-text bg-neo-panel-deep">
                            @if ($project->image_url)
                                <img src="{{ asset($project->image_url) }}" alt="{{ $project->title }}" class="h-full w-full object-cover grayscale transition-all duration-300 group-hover:scale-105 group-hover:grayscale-0" loading="lazy" decoding="async">
                            @else
                                <div class="flex h-full w-full items-center justify-center">
                                    <div class="grid grid-cols-4 gap-2 opacity-70" aria-hidden="true">
                                        @for ($i = 0; $i < 4; $i++)
                                            <span class="h-3 w-3 bg-neo-text"></span>
                                        @endfor
                                    </div>
                                </div>
                            @endif
                            <span class="absolute top-2 right-2 border-2 border-neo-text bg-neo-bg px-2 py-0.5 font-mono text-[9px] font-bold tracking-widest text-neo-text">LV.{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>

                        {{-- Body --}}
                        <div class="flex flex-1 flex-col p-5">
                            <div class="flex items-center justify-between gap-2">
                                @if ($project->sector)
                                    <span class="border-2 border-neo-text bg-neo-bg px-2 py-0.5 font-mono text-[9px] font-bold uppercase tracking-widest text-neo-text">{{ $project->sector }}</span>
                                @endif
                                <span class="font-pixel text-[8px] text-neo-muted">// PROJECT</span>
                            </div>

                            <h3 class="mt-3 font-display text-xl font-bold text-neo-text group-hover:underline">{{ $project->title }}</h3>
                            <p class="mt-2 text-sm text-neo-muted line-clamp-3">{{ $project->excerpt }}</p>

                            {{-- Tech power-ups --}}
                            @if ($project->skills->isNotEmpty())
                                <div class="mt-4 border-t-2 border-dashed border-neo-muted/40 pt-4">
                                    <p class="font-mono text-[9px] font-bold uppercase tracking-widest text-neo-muted">{{ __('POWER-UPS') }}</p>
                                    <div class="mt-2 flex flex-wrap gap-1.5">
                                        @foreach ($project->skills as $skill)
                                            <span class="inline-flex items-center gap-1 border-2 border-neo-text bg-neo-bg px-2 py-0.5 font-mono text-[10px] font-bold text-neo-text transition-transform duration-150 hover:-translate-y-0.5">
                                                <span class="text-neo-muted">+</span>{{ $skill->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <span class="mt-5 inline-flex items-center gap-2 self-start font-mono text-[10px] font-bold uppercase tracking-widest text-neo-muted transition-colors group-hover:text-neo-text">
                                {{ __('VIEW') }}
                                <span class="inline-block transition-transform duration-150 group-hover:translate-x-1">►</span>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="mt-12 border-2 border-neo-text bg-neo-panel p-10 text-center shadow-neo">
                <p class="font-mono text-xs font-bold uppercase tracking-widest text-neo-muted">{{ __('EMPTY WORK LOG') }}</p>
            </div>
        @endif
    </section>

    {{-- ============================== STATS & SKILLS (ATTRIBUTES) ============================== --}}
    <section class="relative scroll-mt-20 border-t-2 border-neo-text bg-neo-panel-deep py-16 sm:py-24" id="attributes">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="font-mono text-xs font-bold tracking-[0.3em] text-neo-muted uppercase">// {{ __('ATTRIBUTES') }}</p>
                <h2 class="mt-3 font-display-heavy text-4xl tracking-tight text-neo-text uppercase sm:text-5xl">{{ __('TECH & SKILLS') }}</h2>
                <p class="mx-auto mt-3 max-w-xl text-neo-muted">{{ __('My arsenal, sharpened over 17+ years of production deployments.') }}</p>
            </div>

            {{-- Stat blocks --}}
            <div class="mx-auto mt-10 grid max-w-4xl grid-cols-2 gap-4 sm:grid-cols-4">
                <div class="border-2 border-neo-text bg-neo-panel p-4 text-center shadow-neo-sm">
                    <p class="font-display-heavy text-3xl text-neo-text">17+</p>
                    <p class="mt-1 font-mono text-[9px] font-bold tracking-widest text-neo-muted uppercase">{{ __('YEARS') }}</p>
                </div>
                <div class="border-2 border-neo-text bg-neo-panel p-4 text-center shadow-neo-sm">
                    <p class="font-display-heavy text-3xl text-neo-text">{{ $featuredProjects->count() }}+</p>
                    <p class="mt-1 font-mono text-[9px] font-bold tracking-widest text-neo-muted uppercase">{{ __('Projects') }}</p>
                </div>
                <div class="border-2 border-neo-text bg-neo-panel p-4 text-center shadow-neo-sm">
                    <p class="font-display-heavy text-3xl text-neo-text">{{ $skills->flatten()->count() }}</p>
                    <p class="mt-1 font-mono text-[9px] font-bold tracking-widest text-neo-muted uppercase">{{ __('Skills') }}</p>
                </div>
                <div class="border-2 border-neo-text bg-neo-panel p-4 text-center shadow-neo-sm">
                    <p class="font-mono text-sm font-bold leading-tight text-neo-text">SIG<br>{{ $profile?->license_number ?? '000000' }}</p>
                    <p class="mt-1 font-mono text-[9px] font-bold tracking-widest text-neo-muted uppercase">{{ __('License') }}</p>
                </div>
            </div>

            {{-- Skill panels --}}
            @if ($skills->isNotEmpty())
                <div class="mt-12 grid grid-cols-1 gap-6 lg:grid-cols-3">
                    @foreach ($skills as $group => $groupSkills)
                        <div class="relative border-2 border-neo-line bg-neo-bg shadow-neo-panel transition-all duration-150 hover:-translate-x-1 hover:-translate-y-1">
                            <div class="flex items-center justify-between border-b-2 border-neo-line bg-neo-panel px-4 py-2.5">
                                <h3 class="font-mono text-[10px] font-bold uppercase tracking-widest text-neo-text">▸ {{ $group }}</h3>
                                <span class="font-pixel text-[8px] text-neo-muted">{{ str_pad($groupSkills->count(), 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <ul class="space-y-4 p-5">
                                @foreach ($groupSkills as $skill)
                                    <li>
                                        <div class="flex items-center justify-between gap-3">
                                            <span class="text-sm font-medium text-neo-text">{{ $skill->name }}</span>
                                            <span class="border-2 border-neo-line px-1.5 py-0.5 font-mono text-[9px] font-bold text-neo-text">LV.{{ $skill->level }}</span>
                                        </div>
                                        <div class="mt-2 h-3.5 border-2 border-neo-line bg-neo-panel">
                                            <div class="h-full bg-neo-text transition-all duration-500" style="width: {{ min($skill->level * 20, 100) }}%;"></div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ============================== CONTACT CTA ============================== --}}
    <section class="relative mx-auto max-w-7xl scroll-mt-20 px-4 sm:px-6 lg:px-8 py-16 sm:py-24" id="contact">
        <div class="relative overflow-hidden border-2 border-neo-text bg-neo-panel p-8 text-center shadow-neo-lg sm:p-14">
            <span class="absolute top-3 left-3 h-5 w-5 border-t-4 border-l-4 border-neo-text"></span>
            <span class="absolute top-3 right-3 h-5 w-5 border-t-4 border-r-4 border-neo-text"></span>
            <span class="absolute bottom-3 left-3 h-5 w-5 border-b-4 border-l-4 border-neo-text"></span>
            <span class="absolute bottom-3 right-3 h-5 w-5 border-b-4 border-r-4 border-neo-text"></span>

            {{-- Decorative "AP" sprite background --}}
            <div class="pointer-events-none absolute inset-0 z-0 flex items-center justify-center opacity-[0.04]" aria-hidden="true">
                <div class="flex flex-col gap-[3px]">
                    @foreach ($sprite as $row)
                        <div class="flex gap-[3px]">
                            @foreach (str_split($row) as $cell)
                                <span class="h-2.5 w-2.5 sm:h-3 sm:w-3 {{ $spriteColors[$cell] ?? '' }}"></span>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            <p class="font-mono text-xs font-bold tracking-[0.3em] text-neo-muted uppercase">// {{ __('Contact') }}</p>
            <h2 class="mx-auto mt-4 max-w-3xl font-display-heavy text-4xl tracking-tight text-neo-text uppercase sm:text-5xl">
                {{ __('READY TO BUILD SOMETHING AMAZING') }}
            </h2>
            <p class="mx-auto mt-4 max-w-2xl text-neo-muted">{{ __('Let\'s turn your idea into production-ready software. No bureaucracy, just clean engineering.') }}</p>

            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('contact') }}" wire:navigate class="group inline-flex items-center gap-2 bg-neo-text px-8 py-4 font-mono text-xs font-bold uppercase tracking-widest text-neo-bg border-2 border-neo-text shadow-neo transition-all duration-150 hover:-translate-x-1 hover:-translate-y-1 active:translate-x-0 active:translate-y-0 active:shadow-none">
                    {{ __('START A PROJECT') }}
                    <span class="transition-transform duration-150 group-hover:translate-x-1">→</span>
                </a>
                @if ($profile?->email)
                    <a href="mailto:{{ $profile->email }}" class="inline-flex items-center gap-2 border-2 border-neo-text px-8 py-4 font-mono text-xs font-bold uppercase tracking-widest text-neo-text shadow-neo-sm transition-all duration-150 hover:-translate-x-1 hover:-translate-y-1 hover:bg-neo-panel-deep active:translate-x-0 active:translate-y-0 active:shadow-none">
                        {{ __('SAY HELLO') }} ✉
                    </a>
                @endif
            </div>
        </div>
    </section>
</div>