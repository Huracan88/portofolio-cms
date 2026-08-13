<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\CreateRecord;
use Livewire\Attributes\On;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    #[On('media-selected')]
    public function setCoverImage(string $url): void
    {
        $this->data['cover_image_url'] = $url;
        $this->unmountAction();
    }
}
