<?php

namespace App\Filament\Resources\MediaResource\Pages;

use App\Exceptions\InvalidImageException;
use App\Exceptions\UnprocessableImageException;
use App\Filament\Resources\MediaResource;
use App\Models\Media;
use App\Services\MediaService;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;

class ListMedia extends ListRecords
{
    protected static string $resource = MediaResource::class;

    protected function getHeaderActions(): array
    {
        return [$this->uploadAction()];
    }

    protected function uploadAction(): Action
    {
        return Action::make('upload')
            ->label(__('Upload images'))
            ->icon('heroicon-o-arrow-up-tray')
            ->schema([
                FileUpload::make('files')
                    ->label(__('Images'))
                    ->multiple()
                    ->storeFiles(false)
                    ->acceptedFileTypes(config('media.allowed_mimes'))
                    ->maxSize(config('media.max_upload_kb'))
                    ->image()
                    ->imageEditor()
                    ->imageEditorMode(2)
                    ->imageEditorAspectRatioOptions(collect(config('media.presets'))->pluck('aspect')->all())
                    ->required(),
            ])
            ->action(function (array $data): void {
                foreach ($data['files'] ?? [] as $file) {
                    app(MediaService::class)->store($file, auth()->id(), 'general');
                }

                Notification::make()
                    ->success()
                    ->title(__('Images uploaded successfully'))
                    ->send();
            })
            ->modalSubmitActionLabel(__('Upload'))
            ->modalWidth(Width::Large);
    }

    /**
     * Apply a crop / optimization payload coming from the cropper modal.
     *
     * @param  array{mode?: string, preset?: string, crop?: array{x: int, y: int, width: int, height: int}, maxWidth?: int, quality?: int, asWebp?: bool}  $payload
     */
    public function applyCrop(int $mediaId, array $payload): void
    {
        $media = Media::findOrFail($mediaId);

        $this->authorize('update', $media);

        try {
            app(MediaService::class)->process($media, [
                'overwrite' => ($payload['mode'] ?? 'overwrite') === 'overwrite',
                'crop' => $payload['crop'] ?? null,
                'maxWidth' => $payload['maxWidth'] ?? config('media.default_max_width'),
                'quality' => $payload['quality'] ?? config('media.webp_quality'),
                'asWebp' => $payload['asWebp'] ?? true,
            ]);

            Notification::make()
                ->success()
                ->title(__('Image updated successfully'))
                ->send();
        } catch (InvalidImageException|UnprocessableImageException $exception) {
            Notification::make()
                ->danger()
                ->title($exception->getMessage())
                ->send();
        }

        $this->unmountAction();
    }
}
