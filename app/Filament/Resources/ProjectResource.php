<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-briefcase';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make(__('English Content'))
                    ->schema([
                        TextInput::make('title_en')->required()->maxLength(200),
                        TextInput::make('slug')->required()->maxLength(250)->unique(ignoreRecord: true),
                        Textarea::make('excerpt_en')->maxLength(500),
                        RichEditor::make('description_en'),
                    ]),
                Section::make(__('Spanish Content'))
                    ->schema([
                        TextInput::make('title_es')->required()->maxLength(200),
                        Textarea::make('excerpt_es')->maxLength(500),
                        RichEditor::make('description_es'),
                    ]),
                Section::make(__('Media & Links'))
                    ->schema([
                        TextInput::make('image_url')
                            ->url()
                            ->maxLength(500)
                            ->hint(__('Landscape recommended'))
                            ->suffixAction(self::mediaPickerAction()),
                        TextInput::make('project_url')->url()->maxLength(500),
                        TextInput::make('repo_url')->url()->maxLength(500),
                    ]),
                Section::make(__('Skills'))
                    ->schema([
                        Select::make('skills')
                            ->relationship('skills', 'name_en')
                            ->multiple()
                            ->preload(),
                    ]),
                Section::make(__('Publishing'))
                    ->schema([
                        Toggle::make('is_featured')->default(false),
                        Toggle::make('is_visible')->default(true),
                        DateTimePicker::make('published_at'),
                        TextInput::make('sort_order')->numeric()->default(0),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title_en')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('title_es')->searchable()->sortable(),
                Tables\Columns\IconColumn::make('is_featured')->boolean()->sortable(),
                Tables\Columns\IconColumn::make('is_visible')->boolean()->sortable(),
                Tables\Columns\TextColumn::make('published_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_featured'),
                Tables\Filters\TernaryFilter::make('is_visible'),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }

    private static function mediaPickerAction(?string $collection = null): Action
    {
        return Action::make('openMediaPicker')
            ->icon('heroicon-o-photo')
            ->tooltip(__('Browse media'))
            ->modalHeading(__('Select media'))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel(__('Close'))
            ->modalWidth(Width::FiveExtraLarge)
            ->modalContent(fn (): View => view('filament.media.picker-modal', ['collection' => $collection]));
    }
}
