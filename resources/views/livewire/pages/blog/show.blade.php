<div>
    @section('meta_description', $post->excerpt)
    @section('meta_type', 'article')
    @if($post->cover_image_url)
        @section('meta_image', asset($post->cover_image_url))
    @endif
    <article class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <a href="{{ route('blog.index') }}" wire:navigate class="inline-flex items-center gap-1.5 font-mono text-xs uppercase tracking-widest text-neo-muted hover:text-neo-text transition-colors mb-8">
            <x-svg-icon name="chevron-left" class="w-4 h-4" />
            {{ __('BACK TO BLOG') }}
        </a>

        @if ($post->cover_image_url)
            <div class="border-2 border-neo-text shadow-neo-sm mb-8">
                <img src="{{ asset($post->cover_image_url) }}" alt="{{ $post->title }}" class="w-full max-h-96 object-cover" loading="eager" fetchpriority="high">
            </div>
        @endif

        <div class="flex flex-wrap items-center gap-3 mb-4">
            @if ($post->category)
                <x-badge variant="primary">{{ $post->category->name }}</x-badge>
            @endif
            @if ($post->tags->isNotEmpty())
                @foreach ($post->tags as $tag)
                    <x-badge variant="muted">{{ $tag->name }}</x-badge>
                @endforeach
            @endif
        </div>

        <x-heading level="1" size="4xl" class="mb-2">
            {{ $post->title }}
        </x-heading>

        <div class="flex flex-wrap items-center gap-2 font-mono text-[10px] font-bold uppercase tracking-widest text-neo-muted mb-8">
            <span>{{ $post->author?->name ?? 'Andrés Pinto' }}</span>
            <span class="text-neo-line">·</span>
            <time datetime="{{ $post->published_at?->format('Y-m-d') }}">{{ $post->published_at?->format('M d, Y') }}</time>
        </div>

        @if ($post->body)
            <div class="text-neo-muted leading-relaxed text-base">
                {!! nl2br(e($post->body)) !!}
            </div>
        @endif
    </article>
</div>
