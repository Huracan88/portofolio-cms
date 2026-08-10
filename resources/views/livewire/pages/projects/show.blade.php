<div>
    @section('meta_description', $project->excerpt ?? strip_tags($project->description ?? ''))
    @if($project->image_url)
        @section('meta_image', asset($project->image_url))
    @endif
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <a href="{{ route('projects.index') }}" wire:navigate class="inline-flex items-center gap-1.5 text-sm text-neutral-500 dark:text-neutral-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors mb-8">
            <x-svg-icon name="chevron-left" class="w-4 h-4" />
            {{ __('Back to Projects') }}
        </a>

        @if ($project->image_url)
            <img src="{{ asset($project->image_url) }}" alt="{{ $project->title }}" class="w-full max-h-96 object-cover rounded-xl shadow-lg mb-8" loading="eager" fetchpriority="high">
        @else
            <div class="w-full h-64 sm:h-80 rounded-xl bg-gradient-to-br from-primary-100 to-accent-100 dark:from-primary-900/30 dark:to-accent-900/30 flex items-center justify-center mb-8">
                <x-svg-icon name="code" class="w-16 h-16 text-primary-400 dark:text-primary-600" />
            </div>
        @endif

        <div class="flex flex-wrap items-center gap-3 mb-6">
            @if ($project->sector)
                <x-badge variant="primary" class="capitalize">{{ __(ucfirst($project->sector)) }}</x-badge>
            @endif
            @if ($project->is_featured)
                <x-badge variant="accent">{{ __('Featured') }}</x-badge>
            @endif
        </div>

        <h1 class="font-display text-3xl sm:text-4xl font-bold text-neutral-900 dark:text-white tracking-tight mb-4">
            {{ $project->title }}
        </h1>

        @if ($project->description)
            <div class="text-neutral-700 dark:text-neutral-300 leading-relaxed mb-8 text-base">
                {!! nl2br(e($project->description)) !!}
            </div>
        @endif

        @if ($project->skills->isNotEmpty())
            <div class="mb-8">
                <h2 class="font-display font-semibold text-sm uppercase tracking-wider text-neutral-500 dark:text-neutral-400 mb-3">{{ __('Technologies Used') }}</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach ($project->skills as $skill)
                        <x-badge variant="neutral">{{ $skill->name }}</x-badge>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-neutral-200 dark:border-neutral-800">
            @if ($project->project_url)
                <x-button variant="primary" :href="$project->project_url" target="_blank" icon="external-link">
                    {{ __('View Live Project') }}
                </x-button>
            @endif
            @if ($project->repo_url)
                <x-button variant="secondary" :href="$project->repo_url" target="_blank" icon="github">
                    {{ __('View Source Code') }}
                </x-button>
            @endif
        </div>
    </section>
</div>