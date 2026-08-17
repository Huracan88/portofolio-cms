<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Concerns\InteractsWithAiTranslation;
use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\CreateRecord;
use Livewire\Attributes\On;

class CreatePost extends CreateRecord
{
    use InteractsWithAiTranslation;

    protected static string $resource = PostResource::class;

    /**
     * @return array<int, string>
     */
    public function translatableFields(): array
    {
        return ['title', 'excerpt', 'body'];
    }

    #[On('media-selected')]
    public function setCoverImage(string $url): void
    {
        $this->data['cover_image_url'] = $url;
        $this->unmountAction();
    }
}
