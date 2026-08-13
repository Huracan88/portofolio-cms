@php
    $cropPresets = collect(config('media.presets'))
        ->map(fn (array $preset): array => [
            'name' => $preset['name'],
            'label' => __($preset['label']),
            'ratio' => $preset['ratio'],
        ])
        ->values();
@endphp

<div
    x-data="mediaCropper({
        mediaId: {{ $media->id }},
        src: '{{ $media->relative_url }}',
        defaultQuality: {{ (int) config('media.webp_quality') }},
        defaultMaxWidth: {{ (int) config('media.default_max_width') }},
        presets: @js($cropPresets),
    })"
    class="space-y-5"
>
    <div class="max-h-[500px] overflow-auto rounded-xl border border-gray-300 bg-gray-100 dark:border-gray-600 dark:bg-gray-800">
        <img
            x-ref="sourceImage"
            src="{{ asset($media->relative_url) }}"
            alt="{{ $media->original_name }}"
            class="max-w-full"
        >
    </div>

    <div>
        <p class="text-sm font-medium text-gray-950 dark:text-white">{{ __('Preset') }}</p>
        <div class="mt-2 grid grid-cols-2 gap-2 sm:grid-cols-4">
            @foreach ($cropPresets as $preset)
                <x-filament::button
                    color="primary"
                    size="sm"
                    x-on:click="setPreset('{{ $preset['name'] }}')"
                    x-bind:class="activePreset === '{{ $preset['name'] }}' ? '' : 'fi-outlined'"
                    class="w-full"
                >
                    {{ $preset['label'] }}
                </x-filament::button>
            @endforeach
        </div>
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label class="text-sm font-medium text-gray-950 dark:text-white">
                {{ __('Quality') }}
                <span class="text-gray-500 dark:text-gray-400" x-text="` (${quality}%)`"></span>
            </label>
            <input
                type="range"
                min="40"
                max="95"
                x-model.number="quality"
                class="mt-2 w-full"
            >
        </div>

        <div>
            <label class="text-sm font-medium text-gray-950 dark:text-white">
                {{ __('Max width') }} (px)
            </label>
            <x-filament::input.wrapper class="mt-2">
                <x-filament::input
                    type="number"
                    min="1"
                    x-model.number="maxWidth"
                />
            </x-filament::input.wrapper>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                {{ __('Scales the image down so it never exceeds this width. Leave high to keep full resolution.') }}
            </p>
        </div>
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <x-filament::fieldset>
            <legend class="text-sm font-medium text-gray-950 dark:text-white">
                {{ __('Output format') }}
            </legend>

            <label class="flex items-center gap-3">
                <x-filament::input.checkbox
                    x-model="asWebp"
                    x-bind:disabled="mode === 'overwrite'"
                />
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('Save as WebP') }}</span>
            </label>

            <p
                x-show="mode === 'overwrite'"
                x-cloak
                class="mt-2 text-xs text-gray-500 dark:text-gray-400"
            >
                {{ __('Overwriting keeps the original format so the file URL stays the same.') }}
            </p>
        </x-filament::fieldset>

        <x-filament::fieldset>
            <legend class="text-sm font-medium text-gray-950 dark:text-white">
                {{ __('Save mode') }}
            </legend>

            <label class="flex items-center gap-3">
                <x-filament::input.checkbox
                    x-model="overwrite"
                    x-on:change="mode = overwrite ? 'overwrite' : 'saveAsNew'"
                />
                <span class="text-sm text-gray-700 dark:text-gray-300">
                    {{ __('Overwrite file (keep the same URL)') }}
                </span>
            </label>

            <div
                x-show="mode === 'saveAsNew'"
                x-cloak
                class="mt-3 flex items-start gap-2 rounded-lg border border-warning-300 bg-warning-50 p-3 text-sm text-warning-700 dark:border-warning-600 dark:bg-warning-500/10 dark:text-warning-400"
            >
                <x-filament::icon icon="heroicon-o-exclamation-triangle" class="h-5 w-5 shrink-0" />
                <span>{{ __('This will change the URL of the image in all places where it is used.') }}</span>
            </div>
        </x-filament::fieldset>
    </div>

    <div class="flex justify-end gap-3">
        <x-filament::button
            type="button"
            color="primary"
            x-on:click="apply()"
            x-bind:disabled="isApplying"
        >
            <span x-show="isApplying" x-cloak class="mr-2">
                <x-filament::loading-indicator class="h-4 w-4" />
            </span>
            {{ __('Apply') }}
        </x-filament::button>
    </div>
</div>
