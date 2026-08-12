<div>
    @section('meta_description', __('Thoughts, tutorials, and insights on web development, architecture, and software engineering.'))
    <x-section :title="__('ARTICLES')" :eyebrow="__('BLOG')" align="center">
        <p class="max-w-2xl mx-auto mt-4 text-center text-neo-muted">
            {{ __('Thoughts, tutorials, and insights on web development, architecture, and software engineering.') }}
        </p>
    </x-section>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        @if ($posts->isNotEmpty())
            <div class="space-y-8">
                @foreach ($posts as $post)
                    <x-card hover class="group">
                        <div class="flex flex-col sm:flex-row gap-6">
                            <div class="sm:w-40 shrink-0 text-center sm:text-left">
                                <time datetime="{{ $post->published_at?->format('Y-m-d') }}" class="block font-mono text-[10px] font-bold uppercase tracking-widest text-neo-muted">
                                    {{ $post->published_at?->format('M d, Y') }}
                                </time>
                                @if ($post->category)
                                    <span class="inline-block mt-2">
                                        <x-badge variant="primary">{{ $post->category->name }}</x-badge>
                                    </span>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <h2 class="font-display font-bold text-xl text-neo-text group-hover:underline">
                                    <a href="{{ route('blog.show', $post) }}" wire:navigate>{{ $post->title }}</a>
                                </h2>
                                @if ($post->excerpt)
                                    <p class="mt-2 text-sm text-neo-muted line-clamp-2">{{ $post->excerpt }}</p>
                                @endif
                                <div class="mt-3 flex items-center gap-4">
                                    <span class="font-mono text-[10px] text-neo-muted">{{ $post->author?->name ?? 'Andrés Pinto' }}</span>
                                    <x-button variant="ghost" size="sm" :href="route('blog.show', $post)" icon="arrow-right">
                                        {{ __('READ MORE') }}
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
            <div class="mt-12 border-2 border-neo-text bg-neo-panel p-10 text-center shadow-neo">
                <x-svg-icon name="edit" class="w-8 h-8 text-neo-muted mx-auto mb-4" />
                <p class="font-mono text-xs font-bold uppercase tracking-widest text-neo-muted">{{ __('EMPTY BLOG LOG') }}</p>
            </div>
        @endif
    </div>
</div>
