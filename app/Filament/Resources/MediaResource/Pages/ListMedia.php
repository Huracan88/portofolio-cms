<?php

namespace App\Filament\Resources\MediaResource\Pages;

use App\Exceptions\InvalidImageException;
use App\Exceptions\MissingOpenRouterKeyException;
use App\Exceptions\OpenRouterException;
use App\Exceptions\UnprocessableImageException;
use App\Filament\Resources\MediaResource;
use App\Models\Media;
use App\Models\Setting;
use App\Services\MediaService;
use App\Services\OpenRouterService;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ListMedia extends ListRecords
{
    protected static string $resource = MediaResource::class;

    protected function getHeaderActions(): array
    {
        return [$this->uploadAction(), $this->generateImageAction()];
    }

    protected function generateImageAction(): Action
    {
        return Action::make('generateImage')
            ->label(__('Generate with AI'))
            ->icon('heroicon-o-sparkles')
            ->schema([
                Textarea::make('prompt')
                    ->label(__('Image prompt'))
                    ->required()
                    ->maxLength(2000)
                    ->rows(4),
                TextInput::make('model')
                    ->label(__('Model'))
                    ->default(Setting::get('openrouter_image_model') ?? config('openrouter.image_model')),
                Select::make('aspect_ratio')
                    ->label(__('Aspect ratio'))
                    ->options([
                        '1:1' => __('1:1 Square'),
                        '16:9' => __('16:9 Cover'),
                        '4:3' => __('4:3 Standard'),
                    ])
                    ->default('1:1'),
                Select::make('n')
                    ->label(__('Number of images'))
                    ->options([
                        1 => '1',
                        2 => '2',
                        3 => '3',
                        4 => '4',
                    ])
                    ->default(1),
            ])
            ->action(function (array $data): void {
                try {
                    set_time_limit(180);

                    $images = app(OpenRouterService::class)->generateImages(
                        prompt: $data['prompt'],
                        model: filled($data['model'] ?? null) ? $data['model'] : null,
                        aspectRatio: $data['aspect_ratio'] ?? '1:1',
                        n: (int) ($data['n'] ?? 1),
                    );
                } catch (MissingOpenRouterKeyException|OpenRouterException $exception) {
                    Notification::make()
                        ->danger()
                        ->title($exception->getMessage())
                        ->send();

                    Log::error('AI image generation failed', ['error' => $exception->getMessage()]);

                    return;
                }

                $mediaService = app(MediaService::class);
                $stored = 0;
                $failed = 0;

                foreach ($images as $image) {
                    try {
                        $media = $mediaService->storeBinary(
                            $image['binary'],
                            $image['media_type'],
                            'ai-'.Str::slug(Str::limit($data['prompt'], 50)),
                            auth()->id(),
                            'generated',
                        );

                        $mediaService->process($media, ['asWebp' => true]);
                        $stored++;
                    } catch (InvalidImageException|UnprocessableImageException $exception) {
                        $failed++;
                        Log::warning('AI generated image could not be stored', ['error' => $exception->getMessage()]);
                    }
                }

                if ($failed === 0) {
                    Notification::make()
                        ->success()
                        ->title(__('Images generated successfully'))
                        ->send();
                } else {
                    Notification::make()
                        ->warning()
                        ->title(__('Some images could not be stored'))
                        ->body(__(':stored stored, :failed failed', ['stored' => $stored, 'failed' => $failed]))
                        ->send();
                }
            })
            ->modalWidth(Width::Large);
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
