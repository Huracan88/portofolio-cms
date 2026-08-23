<?php

namespace App\Filament\Concerns;

use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use Livewire\Attributes\On;

trait InteractsWithGalleryPicker
{
    #[On('gallery-media-selected')]
    public function addGalleryImage(string $url, int $id): void
    {
        $gallery = $this->data['galleryImages'] ?? [];

        $alreadyAdded = collect($gallery)->contains(
            fn (array $item): bool => (int) ($item['media_id'] ?? 0) === $id
        );

        if ($alreadyAdded) {
            Notification::make()
                ->warning()
                ->title(__('Image already in gallery'))
                ->send();

            $this->unmountAction();

            return;
        }

        $gallery[(string) Str::uuid()] = [
            'media_id' => $id,
            'caption_es' => '',
            'caption_en' => '',
        ];

        $this->data['galleryImages'] = $gallery;

        $this->unmountAction();
    }
}
