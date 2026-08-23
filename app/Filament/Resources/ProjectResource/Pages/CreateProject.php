<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Concerns\InteractsWithAiTranslation;
use App\Filament\Concerns\InteractsWithGalleryPicker;
use App\Filament\Resources\ProjectResource;
use Filament\Resources\Pages\CreateRecord;
use Livewire\Attributes\On;

class CreateProject extends CreateRecord
{
    use InteractsWithAiTranslation;
    use InteractsWithGalleryPicker;

    protected static string $resource = ProjectResource::class;

    /**
     * @return array<int, string>
     */
    public function translatableFields(): array
    {
        return ['title', 'excerpt', 'description'];
    }

    #[On('media-selected')]
    public function setProjectImage(string $url): void
    {
        $this->data['image_url'] = $url;
        $this->unmountAction();
    }
}
