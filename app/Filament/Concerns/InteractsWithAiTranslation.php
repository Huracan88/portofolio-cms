<?php

namespace App\Filament\Concerns;

use App\Exceptions\MissingOpenRouterKeyException;
use App\Exceptions\OpenRouterException;
use App\Filament\Actions\TranslateSectionAction;
use App\Services\OpenRouterService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

trait InteractsWithAiTranslation
{
    /**
     * Base field names (without locale suffix) that the AI translator handles.
     *
     * @return array<int, string>
     */
    abstract public function translatableFields(): array;

    public function translateEsToEnAction(): Action
    {
        return TranslateSectionAction::make('es', 'en');
    }

    public function translateEnToEsAction(): Action
    {
        return TranslateSectionAction::make('en', 'es');
    }

    /**
     * Translate every non-empty translatable field from one locale to the other,
     * writing the result back into the form state without persisting anything.
     */
    public function translateSection(string $from, string $to): void
    {
        $service = app(OpenRouterService::class);
        $translated = false;

        foreach ($this->translatableFields() as $field) {
            $raw = $this->data["{$field}_{$from}"] ?? '';
            $source = is_array($raw) ? '' : trim((string) $raw);

            if (blank($source)) {
                continue;
            }

            try {
                $this->data["{$field}_{$to}"] = $service->translate(
                    $source,
                    $from,
                    $to,
                    isHtml: in_array($field, ['body', 'description'], true),
                );

                $translated = true;
            } catch (MissingOpenRouterKeyException|OpenRouterException $exception) {
                Notification::make()
                    ->danger()
                    ->title(__('Translation failed'))
                    ->body($exception->getMessage())
                    ->send();

                Log::warning('AI section translation failed', [
                    'field' => $field,
                    'error' => $exception->getMessage(),
                ]);

                return;
            }
        }

        if (! $translated) {
            Notification::make()
                ->warning()
                ->title(__('Nothing to translate'))
                ->send();

            return;
        }

        Notification::make()
            ->success()
            ->title(__('Section translated successfully'))
            ->send();
    }
}
