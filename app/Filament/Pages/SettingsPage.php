<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class SettingsPage extends Page
{
    protected static ?string $slug = 'settings';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?int $navigationSort = 100;

    protected string $view = 'filament.pages.settings-page';

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'super_admin']) ?? false;
    }

    public static function getNavigationLabel(): string
    {
        return __('Settings');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __('Settings');
    }

    public function mount(): void
    {
        $this->form->fill([
            'openrouter_translate_model' => Setting::get('openrouter_translate_model'),
            'openrouter_image_model' => Setting::get('openrouter_image_model'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('openrouter_key')
                    ->label(__('OpenRouter API key'))
                    ->password()
                    ->revealable()
                    ->autocomplete('off')
                    ->helperText(filled(Setting::get('openrouter_key'))
                        ? __('An API key is already saved. Leave this field empty to keep it.')
                        : __('Paste your OpenRouter API key.')),
                TextInput::make('openrouter_translate_model')
                    ->label(__('Translation model'))
                    ->required(),
                TextInput::make('openrouter_image_model')
                    ->label(__('Image generation model'))
                    ->required(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::set('openrouter_translate_model', $data['openrouter_translate_model'] ?? null);
        Setting::set('openrouter_image_model', $data['openrouter_image_model'] ?? null);

        if (filled($data['openrouter_key'] ?? null)) {
            Setting::set('openrouter_key', $data['openrouter_key'], encrypted: true);
        }

        Notification::make()
            ->success()
            ->title(__('Settings saved'))
            ->send();
    }
}
