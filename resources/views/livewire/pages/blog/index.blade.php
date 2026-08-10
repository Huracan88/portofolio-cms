<div>
    @section('meta_description', __('Thoughts, tutorials, and insights on web development, architecture, and software engineering.'))
    <x-section :title="__('Blog')" :eyebrow="__('Recent Articles')" align="center">
        <p class="mt-4 text-neutral-500 dark:text-neutral-400 max-w-2xl mx-auto text-center">
            {{ __('Thoughts, tutorials, and insights on web development, architecture, and software engineering.') }}
        </p>
    </x-section>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        @if ($posts->isNotEmpty())
            <div class="space-y-8">
                @foreach ($posts as $post)
                    <x-card hover class="group">
                        <div class="flex flex-col sm:flex-row gap-6">
                            <div class="sm:w-32 shrink-0 text-center sm:text-left">
                                <time datetime="{{ $post->published_at?->format('Y-m-d') }}" class="block text-sm font-medium text-neutral-500 dark:text-neutral-400">
                                    {{ $post->published_at?->format('M d, Y') }}
                                </time>
                                @if ($post->category)
                                    <span class="inline-block mt-1">
                                        <x-badge variant="primary" class="text-xs">{{ $post->category->name }}</x-badge>
                                    </span>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <h2 class="font-display font-semibold text-xl text-neutral-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors mb-2">
                                    <a href="{{ route('blog.show', $post) }}" wire:navigate>{{ $post->title }}</a>
                                </h2>
                                @if ($post->excerpt)
                                    <p class="text-sm text-neutral-600 dark:text-neutral-300 line-clamp-2 mb-3">{{ $post->excerpt }}</p>
                                @endif
                                <div class="flex items-center gap-4">
                                    <span class="text-xs text-neutral-400 dark:text-neutral-500">{{ $post->author?->name ?? 'Andrés Pinto' }}</span>
                                    <x-button variant="ghost" size="sm" :href="route('blog.show', $post)" icon="arrow-right">
                                        {{ __('Read More') }}
                                    </x-button>
                                </div>
                            </div>
                        </div>
                    </x-card>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $posts->links() }}
            </div>
        @else
            <x-card class="text-center py-12">
                <x-svg-icon name="edit" class="w-12 h-12 text-neutral-300 dark:text-neutral-600 mx-auto mb-4" />
                <p class="text-neutral-500 dark:text-neutral-400">{{ __('No articles found') }}</p>
            </x-card>
        @endif
    </div>
</div>