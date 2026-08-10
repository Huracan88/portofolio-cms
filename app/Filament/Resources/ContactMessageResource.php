<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Actions;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section as InfolistSection;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static ?string $navigationLabel = 'Messages';

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-envelope';
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                InfolistSection::make(__('Sender'))
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('email'),
                        TextEntry::make('subject'),
                        TextEntry::make('created_at')->dateTime(),
                    ]),
                InfolistSection::make(__('Message'))
                    ->schema([
                        TextEntry::make('message')->markdown(),
                    ]),
                InfolistSection::make(__('Status'))
                    ->schema([
                        TextEntry::make('is_read')
                            ->formatStateUsing(fn (bool $state): string => $state ? __('Read') : __('Unread')),
                        TextEntry::make('replied_at')->dateTime(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('subject')->searchable()->limit(50),
                Tables\Columns\IconColumn::make('is_read')->boolean()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_read'),
            ])
            ->actions([
                Actions\ViewAction::make(),
                Actions\Action::make('mark_read')
                    ->icon('heroicon-o-check')
                    ->label(__('Mark Read'))
                    ->authorize(fn (ContactMessage $record): bool => auth()->user()->can('Update:ContactMessage'))
                    ->action(fn (ContactMessage $record) => $record->update(['is_read' => true]))
                    ->hidden(fn (ContactMessage $record) => $record->is_read),
                Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
            'view' => Pages\ViewContactMessage::route('/{record}'),
        ];
    }
}
