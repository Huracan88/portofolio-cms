<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Actions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-document-text';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make(__('English Content'))
                    ->schema([
                        TextInput::make('title_en')->required()->maxLength(300),
                        TextInput::make('slug')->required()->maxLength(350)->unique(ignoreRecord: true),
                        Textarea::make('excerpt_en')->maxLength(500),
                        RichEditor::make('body_en'),
                    ]),
                Section::make(__('Spanish Content'))
                    ->schema([
                        TextInput::make('title_es')->required()->maxLength(300),
                        Textarea::make('excerpt_es')->maxLength(500),
                        RichEditor::make('body_es'),
                    ]),
                Section::make(__('Relations'))
                    ->schema([
                        Select::make('author_id')
                            ->relationship('author', 'name')
                            ->searchable()
                            ->required(),
                        Select::make('category_id')
                            ->relationship('category', 'name_en')
                            ->searchable(),
                        Select::make('tags')
                            ->relationship('tags', 'name_en')
                            ->multiple()
                            ->preload(),
                    ]),
                Section::make(__('Media'))
                    ->schema([
                        TextInput::make('cover_image_url')->url()->maxLength(500),
                    ]),
                Section::make(__('Publishing'))
                    ->schema([
                        Toggle::make('is_published')->default(false),
                        Toggle::make('is_featured')->default(false),
                        DateTimePicker::make('published_at'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title_en')->searchable()->sortable()->limit(50),
                Tables\Columns\TextColumn::make('author.name')->sortable(),
                Tables\Columns\TextColumn::make('category.name_en')->sortable(),
                Tables\Columns\IconColumn::make('is_published')->boolean()->sortable(),
                Tables\Columns\IconColumn::make('is_featured')->boolean()->sortable(),
                Tables\Columns\TextColumn::make('published_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published'),
                Tables\Filters\TernaryFilter::make('is_featured'),
                Tables\Filters\SelectFilter::make('author')
                    ->relationship('author', 'name'),
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name_en'),
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
            ->defaultSort('published_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
