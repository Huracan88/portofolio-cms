@php
    $galleryMediaId = $get('media_id');
    $galleryMedia = $galleryMediaId ? \App\Models\Media::find($galleryMediaId) : null;
@endphp

<div class="overflow-hidden rounded-lg border border-gray-300 dark:border-gray-600">
    @if ($galleryMedia)
        <img
            src="{{ asset($galleryMedia->relative_url) }}"
            alt="{{ $galleryMedia->original_name }}"
            class="h-32 w-full object-cover"
        >
    @else
        <div class="flex h-32 items-center justify-center bg-gray-100 text-xs text-gray-400 dark:bg-gray-800 dark:text-gray-500">
            {{ __('No media selected') }}
        </div>
    @endif
</div>