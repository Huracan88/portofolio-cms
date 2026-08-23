<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Concerns\InteractsWithAiTranslation;
use App\Filament\Concerns\InteractsWithGalleryPicker;
use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Livewire\Attributes\On;

class EditProject extends EditRecord
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

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }

    #[On('media-selected')]
    public function setProjectImage(string $url): void
    {
        $this->data['image_url'] = $url;
        $this->unmountAction();
    }
}
