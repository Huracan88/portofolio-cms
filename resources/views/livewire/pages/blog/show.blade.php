<div>
    @section('meta_description', $post->excerpt)
    @section('meta_type', 'article')
    @if($post->cover_image_url)
        @section('meta_image', asset($post->cover_image_url))
    @endif
    <article class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <a href="{{ route('blog.index') }}" wire:navigate class="inline-flex items-center gap-1.5 text-sm text-neutral-500 dark:text-neutral-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors mb-8">
            <x-svg-icon name="chevron-left" class="w-4 h-4" />
            {{ __('Back to Blog') }}
        </a>

        @if ($post->cover_image_url)
            <img src="{{ asset($post->cover_image_url) }}" alt="{{ $post->title }}" class="w-full max-h-96 object-cover rounded-xl shadow-lg mb-8" loading="eager" fetchpriority="high">
        @endif

        <div class="flex flex-wrap items-center gap-3 mb-4">
            @if ($post->category)
                <x-badge variant="primary">{{ $post->category->name }}</x-badge>
            @endif
            @if ($post->tags->isNotEmpty())
                @foreach ($post->tags as $tag)
                    <x-badge variant="neutral">{{ $tag->name }}</x-badge>
                @endforeach
            @endif
        </div>

        <h1 class="font-display text-3xl sm:text-4xl font-bold text-neutral-900 dark:text-white tracking-tight mb-2">
            {{ $post->title }}
        </h1>

        <div class="flex items-center gap-2 text-sm text-neutral-500 dark:text-neutral-400 mb-8">
            <x-svg-icon name="user" class="w-4 h-4" />
            <span>{{ $post->author?->name ?? 'Andrés Pinto' }}</span>
            <span class="text-neutral-300 dark:text-neutral-600">&middot;</span>
            <x-svg-icon name="calendar" class="w-4 h-4" />
            <time datetime="{{ $post->published_at?->format('Y-m-d') }}">{{ $post->published_at?->format('M d, Y') }}</time>
        </div>

        @if ($post->body)
            <div class="text-neutral-700 dark:text-neutral-300 leading-relaxed text-base">
                {!! nl2br(e($post->body)) !!}
            </div>
        @endif
    </article>
</div>