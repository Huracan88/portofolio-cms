<div>
    @section('meta_description', __('A selection of projects built over 17+ years of experience across government, enterprise, and fintech sectors.'))
    <x-section :title="__('Projects')" :eyebrow="__('Latest Work')" align="center">
        <p class="mt-4 text-neutral-500 dark:text-neutral-400 max-w-2xl mx-auto text-center">
            {{ __('A selection of projects built over 17+ years of experience across government, enterprise, and fintech sectors.') }}
        </p>
    </x-section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-6">
        <div class="flex flex-wrap items-center justify-center gap-2">
            <button
                wire:click="setSector(null)"
                @class([
                    'px-4 py-1.5 text-sm font-medium rounded-full transition-colors',
                    'bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300' => !$selectedSector,
                    'bg-neutral-100 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-400 hover:bg-neutral-200 dark:hover:bg-neutral-700' => $selectedSector,
                ])
            >
                {{ __('All') }}
            </button>
            @foreach (['government', 'enterprise', 'fintech'] as $sector)
                <button
                    wire:click="setSector('{{ $sector }}')"
                    @class([
                        'px-4 py-1.5 text-sm font-medium rounded-full transition-colors capitalize',
                        'bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300' => $selectedSector === $sector,
                        'bg-neutral-100 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-400 hover:bg-neutral-200 dark:hover:bg-neutral-700' => $selectedSector !== $sector,
                    ])
                >
                    {{ __(ucfirst($sector)) }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        @if ($projects->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($projects as $project)
                    <x-card hover class="flex flex-col h-full">
                        @if ($project->image_url)
                            <div class="-mx-6 -mt-6 mb-4 overflow-hidden rounded-t-xl">
                                <img src="{{ asset($project->image_url) }}" alt="{{ $project->title }}" class="w-full h-48 object-cover" loading="lazy" decoding="async">
                            </div>
                        @else
                            <div class="-mx-6 -mt-6 mb-4 h-48 rounded-t-xl bg-gradient-to-br from-primary-100 to-accent-100 dark:from-primary-900/30 dark:to-accent-900/30 flex items-center justify-center">
                                <x-svg-icon name="code" class="w-10 h-10 text-primary-400 dark:text-primary-600" />
                            </div>
                        @endif

                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                @if ($project->sector)
                                    <x-badge variant="primary" class="capitalize">{{ __(ucfirst($project->sector)) }}</x-badge>
                                @endif
                            </div>
                            <h3 class="font-display font-semibold text-lg text-neutral-900 dark:text-white mb-2">{{ $project->title }}</h3>
                            <p class="text-sm text-neutral-600 dark:text-neutral-300 line-clamp-3">{{ $project->excerpt }}</p>
                        </div>

                        <div class="mt-4 pt-4 border-t border-neutral-100 dark:border-neutral-800">
                            <x-button variant="ghost" size="sm" :href="route('projects.show', $project)" wire:navigate icon="arrow-right">
                                {{ __('View Project') }}
                            </x-button>
                        </div>
                    </x-card>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $projects->links() }}
            </div>
        @else
            <x-card class="text-center py-12">
                <x-svg-icon name="search" class="w-12 h-12 text-neutral-300 dark:text-neutral-600 mx-auto mb-4" />
                <p class="text-neutral-500 dark:text-neutral-400">{{ __('No projects found') }}</p>
            </x-card>
        @endif
    </div>
</div>