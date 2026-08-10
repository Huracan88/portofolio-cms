<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExperienceResource\Pages;
use App\Models\Experience;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ExperienceResource extends Resource
{
    protected static ?string $model = Experience::class;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-briefcase';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make(__('English'))
                    ->schema([
                        TextInput::make('company_en')->required()->maxLength(150),
                        TextInput::make('position_en')->required()->maxLength(150),
                        RichEditor::make('description_en'),
                    ]),
                Section::make(__('Spanish'))
                    ->schema([
                        TextInput::make('company_es')->required()->maxLength(150),
                        TextInput::make('position_es')->required()->maxLength(150),
                        RichEditor::make('description_es'),
                    ]),
                Section::make(__('Dates & Settings'))
                    ->schema([
                        DatePicker::make('started_at')->required(),
                        DatePicker::make('ended_at'),
                        Toggle::make('is_current')->default(false),
                        TextInput::make('sort_order')->numeric()->default(0),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_en')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('position_en')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('started_at')->date()->sortable(),
                Tables\Columns\TextColumn::make('ended_at')->date()->sortable(),
                Tables\Columns\IconColumn::make('is_current')->boolean()->sortable(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
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
            'index' => Pages\ListExperiences::route('/'),
            'create' => Pages\CreateExperience::route('/create'),
            'edit' => Pages\EditExperience::route('/{record}/edit'),
        ];
    }
}
