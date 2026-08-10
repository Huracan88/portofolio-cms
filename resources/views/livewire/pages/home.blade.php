<div class="overflow-hidden">
    @section('meta_description', $profile?->title ?? __('Senior Fullstack Developer & Software Engineer — 17+ years building custom web solutions.'))
    @section('meta_type', 'profile')
    @if($profile?->photo_url)
        @section('meta_image', asset($profile->photo_url))
    @endif
    {{-- Hero --}}
    <section class="relative bg-gradient-to-br from-primary-50 via-white to-neutral-50 dark:from-neutral-950 dark:via-neutral-900 dark:to-primary-950/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 lg:py-32">
            <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
                <div class="shrink-0">
                    @if ($profile?->photo_url)
                        <img src="{{ asset($profile->photo_url) }}" alt="{{ $profile->full_name }}" class="w-40 h-40 sm:w-48 sm:h-48 rounded-2xl object-cover shadow-xl ring-4 ring-white dark:ring-neutral-800" loading="eager" fetchpriority="high" width="192" height="192">
                    @else
                        <div class="w-40 h-40 sm:w-48 sm:h-48 rounded-2xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center shadow-xl ring-4 ring-white dark:ring-neutral-800">
                            <span class="text-5xl font-bold text-white font-display">{{ collect(explode(' ', $profile?->full_name ?? 'AP'))->map(fn($n) => mb_substr($n, 0, 1))->take(2)->join('') }}</span>
                        </div>
                    @endif
                </div>

                <div class="flex-1 text-center lg:text-left">
                    <h1 class="font-display text-4xl sm:text-5xl font-bold text-neutral-900 dark:text-white tracking-tight">
                        {{ $profile?->full_name ?? 'Andrés Pinto' }}
                    </h1>
                    <p class="mt-3 text-lg sm:text-xl text-neutral-600 dark:text-neutral-300 font-medium">
                        {{ $profile?->title ?? __('Fullstack Developer & Software Engineer') }}
                    </p>
                    <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400 flex items-center justify-center lg:justify-start gap-1.5">
                        <x-svg-icon name="location" class="w-4 h-4" />
                        {{ $profile?->location ?? __('Chetumal, Quintana Roo, México') }}
                    </p>
                    @if ($profile?->availability)
                        <p class="mt-3 inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-success-50 text-success-700 dark:bg-success-500/20 dark:text-success-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-success-500 dark:bg-success-400"></span>
                            {{ $profile->availability }}
                        </p>
                    @endif

                    <div class="mt-6 flex flex-wrap items-center gap-3 justify-center lg:justify-start">
                        <x-button variant="primary" size="lg" :href="route('projects.index')" icon="eye">
                            {{ __('View Projects') }}
                        </x-button>
                        <x-button variant="secondary" size="lg" :href="route('contact')" icon="mail">
                            {{ __('Contact Me') }}
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- About / Bio --}}
    @if ($profile?->bio)
        <x-section :title="__('About Me')" :eyebrow="__('Who I am')">
            <div class="max-w-3xl mx-auto">
                <div class="text-neutral-700 dark:text-neutral-300 leading-relaxed text-base">
                    {!! nl2br(e($profile->bio)) !!}
                </div>

                <dl class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @if ($profile->location)
                        <div class="flex items-center gap-3 p-4 rounded-lg bg-neutral-50 dark:bg-neutral-900">
                            <x-svg-icon name="location" class="w-5 h-5 text-primary-500 shrink-0" />
                            <div>
                                <dt class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Location') }}</dt>
                                <dd class="text-sm text-neutral-800 dark:text-neutral-200">{{ $profile->location }}</dd>
                            </div>
                        </div>
                    @endif
                    @if ($profile->email)
                        <div class="flex items-center gap-3 p-4 rounded-lg bg-neutral-50 dark:bg-neutral-900">
                            <x-svg-icon name="mail" class="w-5 h-5 text-primary-500 shrink-0" />
                            <div>
                                <dt class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Email') }}</dt>
                                <dd class="text-sm text-neutral-800 dark:text-neutral-200">{{ $profile->email }}</dd>
                            </div>
                        </div>
                    @endif
                    @if ($profile->license_number)
                        <div class="flex items-center gap-3 p-4 rounded-lg bg-neutral-50 dark:bg-neutral-900">
                            <x-svg-icon name="graduation-cap" class="w-5 h-5 text-primary-500 shrink-0" />
                            <div>
                                <dt class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('License') }}</dt>
                                <dd class="text-sm text-neutral-800 dark:text-neutral-200">{{ $profile->license_number }}</dd>
                            </div>
                        </div>
                    @endif
                    @if ($profile->availability)
                        <div class="flex items-center gap-3 p-4 rounded-lg bg-neutral-50 dark:bg-neutral-900">
                            <x-svg-icon name="briefcase" class="w-5 h-5 text-primary-500 shrink-0" />
                            <div>
                                <dt class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Availability') }}</dt>
                                <dd class="text-sm text-neutral-800 dark:text-neutral-200">{{ $profile->availability }}</dd>
                            </div>
                        </div>
                    @endif
                </dl>
            </div>
        </x-section>
    @endif

    {{-- Tech Stack --}}
    @if ($skills->isNotEmpty())
        <x-section :title="__('Tech Stack')" :eyebrow="__('Skills')">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($skills as $group => $groupSkills)
                    <div>
                        <h3 class="font-display font-semibold text-sm uppercase tracking-wider text-neutral-500 dark:text-neutral-400 mb-4">{{ $group }}</h3>
                        <ul class="space-y-3">
                            @foreach ($groupSkills as $skill)
                                <li class="flex items-center justify-between gap-3">
                                    <span class="text-sm text-neutral-800 dark:text-neutral-200">{{ $skill->name }}</span>
                                    <div class="flex items-center gap-0.5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span class="w-2 h-2 rounded-full {{ $i <= $skill->level ? 'bg-primary-500 dark:bg-primary-400' : 'bg-neutral-200 dark:bg-neutral-700' }}"></span>
                                        @endfor
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </x-section>
    @endif

    {{-- Experience --}}
    @if ($experiences->isNotEmpty())
        <x-section :title="__('Experience')" :eyebrow="__('Career')">
            <div class="max-w-3xl mx-auto relative">
                <div class="absolute left-4 sm:left-6 top-2 bottom-2 w-px bg-neutral-200 dark:bg-neutral-700"></div>
                <div class="space-y-10">
                    @foreach ($experiences as $exp)
                        <div class="relative pl-12 sm:pl-16">
                            <div class="absolute left-[10px] sm:left-[18px] top-1.5 w-3 h-3 rounded-full border-2 {{ $exp->is_current ? 'border-primary-500 bg-primary-500' : 'border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-900' }}"></div>
                            <div>
                                <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                                    <h3 class="font-display font-semibold text-lg text-neutral-900 dark:text-white">{{ $exp->position }}</h3>
                                    <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ $exp->started_at->format('M Y') }} — {{ $exp->is_current ? __('Present') : $exp->ended_at?->format('M Y') }}</span>
                                </div>
                                <p class="text-sm font-medium text-primary-600 dark:text-primary-400">{{ $exp->company }}</p>
                                @if ($exp->description)
                                    <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-300 leading-relaxed">{{ $exp->description }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </x-section>
    @endif

    {{-- Featured Projects --}}
    @if ($featuredProjects->isNotEmpty())
        <x-section :title="__('Featured Projects')" :eyebrow="__('Portfolio')">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($featuredProjects as $project)
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
                                    <x-badge variant="primary">{{ $project->sector }}</x-badge>
                                @endif
                                <x-badge variant="accent">{{ __('Featured') }}</x-badge>
                            </div>
                            <h3 class="font-display font-semibold text-lg text-neutral-900 dark:text-white mb-2">{{ $project->title }}</h3>
                            <p class="text-sm text-neutral-600 dark:text-neutral-300 line-clamp-3">{{ $project->excerpt }}</p>
                        </div>

                        <div class="mt-4 pt-4 border-t border-neutral-100 dark:border-neutral-800">
                            <x-button variant="ghost" size="sm" :href="route('projects.show', $project)" icon="arrow-right">
                                {{ __('View Project') }}
                            </x-button>
                        </div>
                    </x-card>
                @endforeach
            </div>

            @if (\App\Models\Project::where('is_visible', true)->count() > $featuredProjects->count())
                <div class="mt-8 text-center">
                    <x-button variant="secondary" :href="route('projects.index')">
                        {{ __('View All Projects') }}
                    </x-button>
                </div>
            @endif
        </x-section>
    @endif

    {{-- Education --}}
    @if ($educations->isNotEmpty())
        <x-section :title="__('Education')" :eyebrow="__('Academic')">
            <div class="max-w-2xl mx-auto grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach ($educations as $edu)
                    <x-card>
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-primary-50 dark:bg-primary-900/30 rounded-lg shrink-0">
                                <x-svg-icon name="graduation-cap" class="w-5 h-5 text-primary-600 dark:text-primary-400" />
                            </div>
                            <div>
                                <h3 class="font-display font-semibold text-neutral-900 dark:text-white">{{ $edu->degree }}</h3>
                                <p class="text-sm text-primary-600 dark:text-primary-400">{{ $edu->institution }}</p>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">{{ $edu->started_at }} — {{ $edu->ended_at ?? __('Present') }}</p>
                                @if ($edu->license_number)
                                    <p class="text-xs text-neutral-400 dark:text-neutral-500 mt-1">{{ __('License') }}: {{ $edu->license_number }}</p>
                                @endif
                            </div>
                        </div>
                    </x-card>
                @endforeach
            </div>
        </x-section>
    @endif

    {{-- Languages --}}
    @if ($languages->isNotEmpty())
        <x-section :title="__('Languages')" :eyebrow="__('Communication')">
            <div class="flex flex-wrap justify-center gap-4">
                @foreach ($languages as $lang)
                    <x-card class="text-center px-8 py-4">
                        <span class="font-display font-semibold text-lg text-neutral-900 dark:text-white">{{ $lang->language }}</span>
                        <span class="block mt-1 text-sm text-neutral-500 dark:text-neutral-400">{{ $lang->proficiency }}</span>
                    </x-card>
                @endforeach
            </div>
        </x-section>
    @endif
</div>