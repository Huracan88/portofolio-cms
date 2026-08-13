<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Resources\Pages\CreateRecord;
use Livewire\Attributes\On;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

    #[On('media-selected')]
    public function setProjectImage(string $url): void
    {
        $this->data['image_url'] = $url;
        $this->unmountAction();
    }
}
