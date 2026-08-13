<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaResource\Pages;
use App\Models\Media;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Section as InfolistSection;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;

class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    public static function getNavigationLabel(): string
    {
        return __('Media Library');
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-photo';
    }

    public static function getModelLabel(): string
    {
        return __('Media');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Media Library');
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                InfolistSection::make(__('Preview'))
                    ->schema([
                        ImageEntry::make('url')->label(__('Preview')),
                    ]),
                InfolistSection::make(__('Metadata'))
                    ->schema([
                        TextEntry::make('original_name'),
                        TextEntry::make('file_name'),
                        TextEntry::make('mime_type'),
                        TextEntry::make('size')
                            ->formatStateUsing(fn (Media $record): string => $record->human_size),
                        TextEntry::make('width')
                            ->formatStateUsing(fn (Media $record): string => "{$record->width} × {$record->height}px"),
                        TextEntry::make('collection'),
                        TextEntry::make('created_at')->dateTime(),
                    ]),
                InfolistSection::make(__('URLs'))
                    ->schema([
                        TextEntry::make('url')
                            ->label(__('Full URL'))
                            ->copyable(),
                        TextEntry::make('relative_url')
                            ->label(__('Relative URL'))
                            ->copyable(),
                    ]),
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make(__('Metadata'))
                    ->schema([
                        TextInput::make('alt_es')->label(__('Alt text (Spanish)'))->maxLength(255),
                        TextInput::make('alt_en')->label(__('Alt text (English)'))->maxLength(255),
                        TextInput::make('collection')->maxLength(100),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\ImageColumn::make('url')
                        ->label(__('Preview'))
                        ->square()
                        ->height(160)
                        ->extraImgAttributes(['loading' => 'lazy']),
                    Tables\Columns\TextColumn::make('original_name')
                        ->searchable()
                        ->limit(40)
                        ->weight('bold'),
                    Tables\Columns\TextColumn::make('size')
                        ->formatStateUsing(fn (Media $record): string => $record->human_size),
                    Tables\Columns\TextColumn::make('width')
                        ->label(__('Dimensions'))
                        ->formatStateUsing(fn (Media $record): string => "{$record->width} × {$record->height}px"),
                    Tables\Columns\TextColumn::make('collection')
                        ->icon('heroicon-o-folder')
                        ->placeholder(__('—'))
                        ->color('gray'),
                    Tables\Columns\TextColumn::make('url')
                        ->copyable()
                        ->limit(30)
                        ->icon('heroicon-o-link')
                        ->color('gray'),
                ]),
            ])
            ->contentGrid([
                'sm' => 1,
                'md' => 3,
                'xl' => 3,
                '2xl' => 3,
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('collection')
                    ->label(__('Collection'))
                    ->options(fn (): array => Media::query()
                        ->pluck('collection')
                        ->filter()
                        ->unique()
                        ->mapWithKeys(fn (string $collection): array => [$collection => $collection])
                        ->all()),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Actions\ViewAction::make()
                    ->iconButton()
                    ->tooltip(__('View'))
                    ->hidden(fn (Media $record): bool => $record->trashed()),
                Actions\EditAction::make()
                    ->iconButton()
                    ->tooltip(__('Edit'))
                    ->hidden(fn (Media $record): bool => $record->trashed()),
                Actions\Action::make('process')
                    ->label(__('Crop'))
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->iconButton()
                    ->tooltip(__('Crop'))
                    ->visible(fn (Media $record): bool => $record->is_processable && ! $record->trashed())
                    ->modalContent(fn (Media $record): View => view('filament.media.crop-modal', ['media' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel(__('Close'))
                    ->modalWidth(Width::FiveExtraLarge),
                Actions\DeleteAction::make()
                    ->label(__('Delete'))
                    ->iconButton()
                    ->tooltip(__('Delete'))
                    ->hidden(fn (Media $record): bool => $record->trashed()),
                Actions\RestoreAction::make()
                    ->label(__('Restore'))
                    ->iconButton()
                    ->tooltip(__('Restore')),
                Actions\ForceDeleteAction::make()
                    ->label(__('Purge'))
                    ->iconButton()
                    ->tooltip(__('Purge')),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()->label(__('Delete')),
                    Actions\RestoreBulkAction::make()->label(__('Restore')),
                    Actions\ForceDeleteBulkAction::make()->label(__('Purge')),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMedia::route('/'),
        ];
    }
}
