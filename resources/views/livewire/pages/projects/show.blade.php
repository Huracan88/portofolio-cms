<div>
    @section('meta_description', $project->excerpt ?? strip_tags($project->description ?? ''))
    @if($project->image_url)
        @section('meta_image', asset($project->image_url))
    @endif
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <a href="{{ route('projects.index') }}" wire:navigate class="inline-flex items-center gap-1.5 font-mono text-xs uppercase tracking-widest text-neo-muted hover:text-neo-text transition-colors mb-8">
            <x-svg-icon name="chevron-left" class="w-4 h-4" />
            {{ __('BACK TO PROJECTS') }}
        </a>

        @if ($project->image_url)
            <div class="border-2 border-neo-text shadow-neo-sm mb-8">
                <img src="{{ asset($project->image_url) }}" alt="{{ $project->title }}" class="w-full max-h-96 object-cover grayscale hover:grayscale-0 transition-all duration-300" loading="eager" fetchpriority="high">
            </div>
        @else
            <div class="h-64 sm:h-80 border-2 border-neo-text bg-neo-panel-deep shadow-neo-sm mb-8 flex items-center justify-center">
                <div class="grid grid-cols-4 gap-2 opacity-70" aria-hidden="true">
                    @for ($i = 0; $i < 4; $i++)
                        <span class="h-3 w-3 bg-neo-text"></span>
                    @endfor
                </div>
            </div>
        @endif

        <div class="flex flex-wrap items-center gap-3 mb-6">
            @if ($project->sector)
                <x-badge variant="primary" class="capitalize">{{ __(ucfirst($project->sector)) }}</x-badge>
            @endif
            @if ($project->is_featured)
                <x-badge variant="secondary">{{ __('FEATURED') }}</x-badge>
            @endif
        </div>

        <x-heading level="1" size="4xl" class="mb-4">
            {{ $project->title }}
        </x-heading>

        @if ($project->description)
            <div class="text-neo-muted leading-relaxed mb-8 text-base">
                {!! nl2br(e($project->description)) !!}
            </div>
        @endif

        @if ($project->skills->isNotEmpty())
            <div class="mb-8">
                <p class="font-mono text-xs font-bold tracking-[0.3em] text-neo-muted uppercase mb-3">// {{ __('TECHNOLOGIES USED') }}</p>
                <div class="flex flex-wrap gap-2">
                    @foreach ($project->skills as $skill)
                        <x-badge variant="secondary">{{ $skill->name }}</x-badge>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="border-t-2 border-neo-line pt-6 flex flex-wrap items-center gap-3">
            @if ($project->project_url)
                <x-button variant="primary" :href="$project->project_url" target="_blank" icon="external-link">
                    {{ __('VIEW LIVE PROJECT') }}
                </x-button>
            @endif
            @if ($project->repo_url)
                <x-button variant="secondary" :href="$project->repo_url" target="_blank" icon="github">
                    {{ __('VIEW SOURCE CODE') }}
                </x-button>
            @endif
        </div>
    </section>
</div>
