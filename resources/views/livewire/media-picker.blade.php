<div class="space-y-4">
    <div>
        <x-filament::input.wrapper>
            <x-filament::input
                type="search"
                wire:model.live.debounce.300ms="search"
                placeholder="{{ __('Search media') }}"
            />
        </x-filament::input.wrapper>
    </div>

    @if ($media->isEmpty())
        <p class="rounded-lg border border-dashed border-gray-300 p-8 text-center text-sm text-gray-500 dark:border-gray-600 dark:text-gray-400">
            {{ __('No media found') }}
        </p>
    @else
        <div class="grid grid-cols-3 gap-3">
            @foreach ($media as $item)
                <button
                    type="button"
                    wire:click="pick({{ $item->id }})"
                    wire:key="media-{{ $item->id }}"
                    class="group overflow-hidden rounded-lg border border-gray-300 text-left transition hover:border-primary-500 dark:border-gray-600"
                    title="{{ $item->original_name }}"
                >
                    <img
                        src="{{ asset($item->relative_url) }}"
                        alt="{{ $item->original_name }}"
                        loading="lazy"
                        class="h-24 w-full border-b border-gray-200 object-cover dark:border-gray-700"
                    >
                    <span class="block truncate px-2 py-1.5 text-xs text-gray-700 dark:text-gray-300">
                        {{ $item->original_name }}
                    </span>
                </button>
            @endforeach
        </div>

        <div>
            {{ $media->links() }}
        </div>
    @endif
</div>
