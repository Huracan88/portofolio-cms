<div>
    @section('meta_description', __('A selection of projects built over 17+ years of experience across government, enterprise, and fintech sectors.'))
    <x-section :title="__('ALL PROJECTS')" :eyebrow="__('PORTFOLIO')" align="center">
        <p class="max-w-2xl mx-auto mt-4 text-center text-neo-muted">
            {{ __('A selection of projects built over 17+ years of experience across government, enterprise, and fintech sectors.') }}
        </p>
    </x-section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-6">
        <div class="flex flex-wrap items-center justify-center gap-2">
            <button
                wire:click="setSector(null)"
                @class([
                    'border-2 border-neo-text bg-neo-text text-neo-bg px-4 py-1.5 font-mono text-xs font-bold uppercase tracking-widest shadow-neo-sm transition-all duration-150 hover:-translate-x-0.5 hover:-translate-y-0.5' => ! $selectedSector,
                    'border-2 border-neo-line bg-neo-panel text-neo-muted px-4 py-1.5 font-mono text-xs font-bold uppercase tracking-widest shadow-neo-sm transition-all duration-150 hover:-translate-x-0.5 hover:-translate-y-0.5 hover:border-neo-text hover:text-neo-text' => $selectedSector,
                ])
            >
                {{ __('All') }}
            </button>
            @foreach (['government', 'enterprise', 'fintech'] as $sector)
                <button
                    wire:click="setSector('{{ $sector }}')"
                    @class([
                        'border-2 border-neo-text bg-neo-text text-neo-bg px-4 py-1.5 font-mono text-xs font-bold uppercase tracking-widest shadow-neo-sm transition-all duration-150 hover:-translate-x-0.5 hover:-translate-y-0.5' => $selectedSector === $sector,
                        'border-2 border-neo-line bg-neo-panel text-neo-muted px-4 py-1.5 font-mono text-xs font-bold uppercase tracking-widest shadow-neo-sm transition-all duration-150 hover:-translate-x-0.5 hover:-translate-y-0.5 hover:border-neo-text hover:text-neo-text' => $selectedSector !== $sector,
                    ])
                >
                    {{ __(ucfirst($sector)) }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        @if ($projects->isNotEmpty())
            <div class="mt-12 grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($projects as $project)
                    <a href="{{ route('projects.show', $project) }}" wire:navigate class="group relative flex h-full flex-col border-2 border-neo-text bg-neo-panel shadow-neo transition-all duration-150 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-neo-lg active:translate-x-0 active:translate-y-0 active:shadow-none focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neo-text">
                        <span class="absolute -top-2 -left-2 z-10 flex h-7 w-7 items-center justify-center border-2 border-neo-text bg-neo-bg font-mono text-[10px] font-bold text-neo-text shadow-neo-sm transition-all duration-150 group-hover:-translate-x-0.5 group-hover:-translate-y-0.5">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>

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

                        <div class="flex flex-1 flex-col p-5">
                            <div class="flex items-center justify-between gap-2">
                                @if ($project->sector)
                                    <span class="border-2 border-neo-text bg-neo-bg px-2 py-0.5 font-mono text-[9px] font-bold uppercase tracking-widest text-neo-text">{{ __(ucfirst($project->sector)) }}</span>
                                @endif
                                <span class="font-pixel text-[8px] text-neo-muted">// PROJECT</span>
                            </div>

                            <h3 class="mt-3 font-display text-xl font-bold text-neo-text group-hover:underline">{{ $project->title }}</h3>
                            <p class="mt-2 text-sm text-neo-muted line-clamp-3">{{ $project->excerpt }}</p>

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

            <div class="mt-10">
                {{ $projects->links() }}
            </div>
        @else
            <div class="mt-12 border-2 border-neo-text bg-neo-panel p-10 text-center shadow-neo">
                <p class="font-mono text-xs font-bold uppercase tracking-widest text-neo-muted">{{ __('EMPTY WORK LOG') }}</p>
            </div>
        @endif
    </div>
</div>
