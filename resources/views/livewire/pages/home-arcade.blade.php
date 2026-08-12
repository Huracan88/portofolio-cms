<div class="relative overflow-hidden bg-arcade-bg text-arcade-text">
    @section('meta_description', __('Senior Fullstack Developer & Software Engineer — 17+ years building custom web solutions.'))
    @section('meta_type', 'profile')
    @if($profile?->photo_url)
        @section('meta_image', asset($profile->photo_url))
    @endif

    {{-- Floating background particles / stars --}}
    <div class="pointer-events-none absolute inset-0 opacity-60" aria-hidden="true">
        <div class="absolute top-1/4 left-[8%] h-1 w-1 rounded-full bg-arcade-cyan animate-pulse"></div>
        <div class="absolute top-1/3 left-[85%] h-1.5 w-1.5 rounded-full bg-arcade-electric animate-pulse" style="animation-delay: 400ms"></div>
        <div class="absolute top-2/3 left-[12%] h-1 w-1 rounded-full bg-arcade-cyan animate-pulse" style="animation-delay: 800ms"></div>
        <div class="absolute top-3/4 left-[75%] h-1 w-1 rounded-full bg-arcade-electric animate-pulse" style="animation-delay: 600ms"></div>
        <div class="absolute top-[15%] left-[40%] h-1.5 w-1.5 rounded-full bg-arcade-cyan/60 animate-pulse" style="animation-delay: 200ms"></div>
    </div>

    {{-- ============================== HERO ============================== --}}
    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-20 sm:pt-24 lg:pt-32 lg:pb-28">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            {{-- Hero copy --}}
            <div class="text-center lg:text-left">
                {{-- Retro status badge --}}
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded border border-arcade-cyan/60 bg-arcade-panel/60 font-pixel text-[10px] sm:text-xs tracking-wider text-arcade-cyan shadow-[0_0_15px_rgba(0,240,255,0.25)]">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-arcade-cyan opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-arcade-cyan"></span>
                    </span>
                    [ {{ __('STATUS: ONLINE') }} / {{ __('AVAILABLE FOR PROJECTS') }} ]
                </div>

                <h1 class="mt-6 font-display text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight leading-tight">
                    <span class="text-arcade-text">{{ __('Hi, I\'m') }}</span>
                    <span class="block bg-gradient-to-r from-arcade-cyan via-arcade-electric to-arcade-cyan bg-clip-text text-transparent drop-shadow-[0_0_20px_rgba(0,240,255,0.35)]">
                        {{ $profile?->full_name ?? 'Andrés Pinto' }}
                    </span>
                </h1>

                <p class="mt-5 text-lg sm:text-xl text-arcade-muted font-medium">
                    {{ $profile?->title ?? __('Fullstack Developer & Software Engineer') }}
                </p>

                <p class="mt-3 text-sm text-arcade-muted/80 flex items-center justify-center lg:justify-start gap-1.5">
                    <span class="font-pixel text-arcade-cyan text-[10px]">LV. 17</span>
                    <span class="text-arcade-muted/50">/</span>
                    <span>{{ __('Years of experience') }}</span>
                </p>

                {{-- CTAs --}}
                <div class="mt-8 flex flex-wrap items-center gap-4 justify-center lg:justify-start">
                    <a href="{{ route('projects.index') }}" wire:navigate class="group inline-flex items-center gap-3 px-6 py-3 font-pixel text-xs sm:text-sm bg-arcade-cyan text-arcade-bg font-bold rounded border-2 border-arcade-cyan shadow-[0_0_20px_rgba(0,240,255,0.4)] transition-all duration-200 hover:shadow-[0_0_35px_rgba(0,240,255,0.65)] hover:-translate-y-0.5 hover:bg-white hover:border-white active:translate-y-0">
                        <span class="text-arcade-bg">▶</span>
                        {{ __('PRESS START') }}
                    </a>
                    <a href="{{ route('contact') }}" wire:navigate class="inline-flex items-center gap-3 px-6 py-3 font-pixel text-xs sm:text-sm text-arcade-cyan rounded border-2 border-arcade-electric/70 bg-arcade-panel/60 transition-all duration-200 hover:border-arcade-cyan hover:shadow-[0_0_20px_rgba(0,240,255,0.35)] hover:-translate-y-0.5 active:translate-y-0">
                        <span class="font-display">✉</span>
                        {{ __('CONTACT') }}
                    </a>
                </div>

                {{-- Availability mini-stats --}}
                <div class="mt-10 grid grid-cols-3 gap-4 max-w-md mx-auto lg:mx-0">
                    <div class="text-center lg:text-left">
                        <p class="font-pixel text-arcade-cyan text-base sm:text-lg">17+</p>
                        <p class="mt-1 text-[10px] sm:text-xs text-arcade-muted font-pixel">{{ __('YEARS') }}</p>
                    </div>
                    <div class="text-center lg:text-left">
                        <p class="font-pixel text-arcade-cyan text-base sm:text-lg">{{ $featuredProjects->count() }}</p>
                        <p class="mt-1 text-[10px] sm:text-xs text-arcade-muted font-pixel">{{ __('FEATURED') }}</p>
                    </div>
                    <div class="text-center lg:text-left">
                        <p class="font-pixel text-arcade-cyan text-base sm:text-lg">{{ $skills->flatten()->count() }}</p>
                        <p class="mt-1 text-[10px] sm:text-xs text-arcade-muted font-pixel">{{ __('SKILLS') }}</p>
                    </div>
                </div>
            </div>

            {{-- Pixel-art sprite placeholder --}}
            <div class="flex justify-center lg:justify-end relative">
                <div class="relative w-72 h-72 sm:w-80 sm:h-80">
                    {{-- CRT screen frame --}}
                    <div class="absolute inset-0 rounded-lg border-2 border-arcade-electric/60 bg-arcade-panel-deep shadow-[0_0_40px_rgba(59,130,246,0.25),inset_0_0_40px_rgba(0,240,255,0.06)] p-4">
                        {{-- Scanlines overlay --}}
                        <div class="absolute inset-0 rounded-lg pointer-events-none opacity-20" style="background-image: repeating-linear-gradient(0deg, rgba(0,240,255,0.15) 0 1px, transparent 1px 4px);"></div>

                        {{-- CRT header bar --}}
                        <div class="absolute top-0 inset-x-0 h-8 flex items-center justify-between px-3 border-b border-arcade-electric/40 bg-arcade-panel rounded-t-lg">
                            <span class="font-pixel text-[8px] text-arcade-cyan">AP://ARCADE</span>
                            <div class="flex gap-1.5">
                                <span class="h-2 w-2 rounded-full bg-arcade-cyan/80"></span>
                                <span class="h-2 w-2 rounded-full bg-arcade-electric/80"></span>
                                <span class="h-2 w-2 rounded-full bg-arcade-muted/60"></span>
                            </div>
                        </div>

                        {{-- Sprite (pixel-art character built with CSS grid) --}}
                        <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 flex justify-center animate-float">
                            <div class="grid grid-rows-[repeat(12,10px)] gap-[1px]" aria-hidden="true">
                                @php
                                    $pixels = [
                                        [1, 0, 6, 'cyan'], [2, 1, 4, 'cyan'], [1, 1, 1, 'cyan'], [6, 1, 1, 'cyan'],
                                        [1, 2, 1, 'cyan'], [2, 2, 4, 'dark'], [6, 2, 1, 'cyan'],
                                        [2, 3, 4, 'dark'],
                                        [2, 4, 1, 'cyan'], [3, 4, 1, 'text'], [4, 4, 1, 'text'], [5, 4, 1, 'cyan'],
                                        [2, 5, 1, 'cyan'], [5, 5, 1, 'cyan'],
                                        [1, 6, 6, 'electric'],
                                        [0, 7, 8, 'electric'],
                                        [1, 8, 2, 'cyan'], [2, 8, 4, 'electric'], [5, 8, 2, 'cyan'],
                                        [0, 9, 8, 'electric'],
                                        [1, 10, 6, 'electric'],
                                        [0, 11, 8, 'cyan'],
                                    ];
                                    $palette = [
                                        'cyan' => 'bg-arcade-cyan shadow-[0_0_6px_rgba(0,240,255,0.6)]',
                                        'electric' => 'bg-arcade-electric',
                                        'dark' => 'bg-arcade-bg',
                                        'text' => 'bg-arcade-text',
                                    ];
                                @endphp
                                @foreach ($pixels as [$px, $py, $pw, $color])
                                    <div class="{{ $palette[$color] }}" style="grid-column: {{ $px + 1 }} / span {{ $pw }}; grid-row: {{ $py + 1 }};"></div>
                                @endforeach
                            </div>
                        </div>

                        {{-- CRT footer bar --}}
                        <div class="absolute bottom-0 inset-x-0 h-8 flex items-center justify-between px-3 border-t border-arcade-electric/40 bg-arcade-panel rounded-b-lg">
                            <span class="font-pixel text-[8px] text-arcade-muted">INSERT COIN</span>
                            <span class="font-pixel text-[8px] text-arcade-cyan animate-blink">▮</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================== DIVIDER ============================== --}}
    <div class="relative border-y-2 border-dashed border-arcade-electric/40 bg-arcade-panel-deep/60 py-3 overflow-hidden">
        <div class="flex whitespace-nowrap font-pixel text-[10px] sm:text-xs tracking-widest text-arcade-muted/80 gap-10 px-4">
            <span class="text-arcade-cyan">► LARAVEL</span>
            <span class="text-arcade-electric">► LIVEWIRE</span>
            <span class="text-arcade-cyan">► FILAMENT</span>
            <span class="text-arcade-electric">► TAILWIND</span>
            <span class="text-arcade-cyan">► MARIADB</span>
            <span class="text-arcade-electric">► DOCKER</span>
            <span class="text-arcade-cyan">► PHP</span>
            <span class="text-arcade-electric">► PEST</span>
        </div>
    </div>

    {{-- ============================== PROJECTS GRID ============================== --}}
    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24" id="projects">
        <div class="text-center mb-12">
            <p class="font-pixel text-[10px] sm:text-xs text-arcade-cyan tracking-widest">// {{ __('QUEST LOG') }}</p>
            <h2 class="mt-3 font-display text-3xl sm:text-4xl font-bold text-arcade-text">
                {{ __('Featured Projects') }}
            </h2>
            <p class="mt-3 text-arcade-muted max-w-xl mx-auto">{{ __('A curated selection of my completed quests across government, enterprise and fintech sectors.') }}</p>
        </div>

        @if ($featuredProjects->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach ($featuredProjects as $project)
                    <a href="{{ route('projects.show', $project) }}" wire:navigate class="group relative flex flex-col h-full rounded border-2 border-arcade-electric/40 bg-arcade-panel/80 transition-all duration-200 hover:border-arcade-cyan hover:shadow-[0_0_25px_rgba(0,240,255,0.25)] hover:-translate-y-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-arcade-cyan overflow-hidden">
                        {{-- Corner brackets --}}
                        <span class="absolute top-2 left-2 h-3 w-3 border-t-2 border-l-2 border-arcade-cyan/70 group-hover:border-arcade-cyan transition-colors z-10"></span>
                        <span class="absolute top-2 right-2 h-3 w-3 border-t-2 border-r-2 border-arcade-cyan/70 group-hover:border-arcade-cyan transition-colors z-10"></span>
                        <span class="absolute bottom-2 left-2 h-3 w-3 border-b-2 border-l-2 border-arcade-cyan/70 group-hover:border-arcade-cyan transition-colors z-10"></span>
                        <span class="absolute bottom-2 right-2 h-3 w-3 border-b-2 border-r-2 border-arcade-cyan/70 group-hover:border-arcade-cyan transition-colors z-10"></span>

                        {{-- Sprite / image header --}}
                        <div class="relative h-44 border-b-2 border-arcade-electric/30 bg-arcade-panel-deep overflow-hidden">
                            @if ($project->image_url)
                                <img src="{{ asset($project->image_url) }}" alt="{{ $project->title }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy" decoding="async">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <div class="grid grid-cols-4 gap-1 opacity-80" aria-hidden="true">
                                        @for ($i = 0; $i < 4; $i++)
                                            <div class="h-2 w-2 rounded-sm bg-arcade-cyan" style="box-shadow: 0 0 6px rgba(0,240,255,0.5);"></div>
                                        @endfor
                                    </div>
                                </div>
                            @endif
                            {{-- level tag --}}
                            <div class="absolute top-2 right-2 font-pixel text-[8px] px-2 py-1 rounded bg-arcade-bg/90 border border-arcade-cyan/60 text-arcade-cyan">
                                LV.{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </div>
                        </div>

                        {{-- Card body --}}
                        <div class="flex-1 p-5">
                            <div class="flex items-center justify-between gap-2 mb-2">
                                @if ($project->sector)
                                    <x-badge variant="primary">{{ __(ucfirst($project->sector)) }}</x-badge>
                                @endif
                                <span class="font-pixel text-[8px] text-arcade-muted">{{ __('QUESTS') }}</span>
                            </div>

                            <h3 class="font-display font-semibold text-lg text-arcade-text group-hover:text-arcade-cyan transition-colors">
                                {{ $project->title }}
                            </h3>
                            <p class="mt-2 text-sm text-arcade-muted line-clamp-3">{{ $project->excerpt }}</p>

                            {{-- Tech tags as inventory items --}}
                            @if ($project->skills->isNotEmpty())
                                <div class="mt-4 pt-4 border-t-2 border-dashed border-arcade-electric/30">
                                    <p class="font-pixel text-[8px] text-arcade-electric tracking-wider mb-2">{{ __('INVENTORY') }}</p>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach ($project->skills as $skill)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-medium bg-arcade-panel border border-arcade-electric/50 text-arcade-cyan/90">
                                                <span class="text-arcade-electric">+</span>{{ $skill->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Footer CTA --}}
                        <div class="px-5 pb-5">
                            <span class="inline-flex items-center gap-2 font-pixel text-[9px] text-arcade-cyan/80 group-hover:text-arcade-cyan transition-colors">
                                {{ __('PRESS A ► VIEW') }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('projects.index') }}" wire:navigate class="inline-flex items-center gap-2 font-pixel text-xs text-arcade-cyan border-2 border-arcade-electric/60 px-5 py-2.5 rounded transition-all duration-200 hover:border-arcade-cyan hover:shadow-[0_0_20px_rgba(0,240,255,0.3)]">
                    {{ __('VIEW ALL QUESTS') }} →
                </a>
            </div>
        @else
            <x-card class="text-center py-12 bg-arcade-panel border-2 border-arcade-electric/40">
                <p class="text-arcade-muted font-pixel text-xs">{{ __('EMPTY QUEST LOG') }}</p>
            </x-card>
        @endif
    </section>

    {{-- ============================== STATS & SKILLS (CHARACTER SHEET) ============================== --}}
    <section class="relative border-t-2 border-dashed border-arcade-electric/40 bg-arcade-panel-deep/40 py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <p class="font-pixel text-[10px] sm:text-xs text-arcade-cyan tracking-widest">// {{ __('CHARACTER STATUS') }}</p>
                <h2 class="mt-3 font-display text-3xl sm:text-4xl font-bold text-arcade-text">
                    {{ __('Tech Stack & Skills') }}
                </h2>
                <p class="mt-3 text-arcade-muted max-w-xl mx-auto">{{ __('My attributes, leveled up over 17+ years of real-world quests.') }}</p>
            </div>

            @if ($skills->isNotEmpty())
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">
                    @foreach ($skills as $group => $groupSkills)
                        <div class="rounded-lg border-2 border-arcade-electric/40 bg-arcade-panel/80 p-5">
                            <h3 class="font-pixel text-[10px] sm:text-xs text-arcade-cyan tracking-wider uppercase mb-4 flex items-center gap-2">
                                <span class="text-arcade-electric">▸</span>{{ $group }}
                            </h3>
                            <ul class="space-y-4">
                                @foreach ($groupSkills as $skill)
                                    <li>
                                        <div class="flex items-center justify-between gap-3 mb-1.5">
                                            <span class="text-sm text-arcade-text">{{ $skill->name }}</span>
                                            <span class="font-pixel text-[9px] text-arcade-muted">LV.{{ $skill->level }}</span>
                                        </div>
                                        {{-- Pixel-style HP bar --}}
                                        <div class="h-3 rounded-sm bg-arcade-panel-deep border border-arcade-electric/50 overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-arcade-electric to-arcade-cyan shadow-[0_0_10px_rgba(0,240,255,0.4)] transition-all duration-500"
                                                 style="width: {{ $skill->level * 20 }}%;"></div>
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
    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
        <div class="rounded-lg border-2 border-arcade-cyan/50 bg-gradient-to-br from-arcade-panel to-arcade-panel-deep p-8 sm:p-14 text-center relative overflow-hidden shadow-[0_0_40px_rgba(0,240,255,0.15)]">
            <div class="pointer-events-none absolute inset-0 opacity-30" aria-hidden="true" style="background-image: radial-gradient(circle at 20% 20%, rgba(0,240,255,0.25) 0, transparent 45%), radial-gradient(circle at 80% 80%, rgba(59,130,246,0.3) 0, transparent 45%);"></div>

            <p class="relative font-pixel text-[10px] sm:text-xs text-arcade-cyan tracking-widest">// {{ __('FINAL BOSS: YOUR PROJECT') }}</p>
            <h2 class="relative mt-4 font-display text-3xl sm:text-4xl font-bold text-arcade-text">
                {{ __('Ready to start a new quest?') }}
            </h2>
            <p class="relative mt-3 text-arcade-muted max-w-xl mx-auto">
                {{ __('Have a project in mind or just want to say hello? Fill out the form below and I\'ll get back to you as soon as possible.') }}
            </p>

            <div class="relative mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('contact') }}" wire:navigate class="inline-flex items-center gap-3 px-8 py-3.5 font-pixel text-xs sm:text-sm bg-arcade-cyan text-arcade-bg font-bold rounded border-2 border-arcade-cyan shadow-[0_0_25px_rgba(0,240,255,0.4)] transition-all duration-200 hover:shadow-[0_0_40px_rgba(0,240,255,0.7)] hover:-translate-y-0.5 active:translate-y-0">
                    ▶ {{ __('CONTACT ME') }}
                </a>
                @if($profile?->email)
                    <a href="mailto:{{ $profile->email }}" class="inline-flex items-center gap-3 px-8 py-3.5 font-pixel text-xs sm:text-sm text-arcade-cyan rounded border-2 border-arcade-electric/70 transition-all duration-200 hover:border-arcade-cyan hover:shadow-[0_0_20px_rgba(0,240,255,0.3)] hover:-translate-y-0.5 active:translate-y-0">
                        ✉ {{ $profile->email }}
                    </a>
                @endif
            </div>
        </div>
    </section>
</div>
