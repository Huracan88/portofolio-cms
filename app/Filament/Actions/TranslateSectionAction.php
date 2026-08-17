<?php

namespace App\Filament\Actions;

use App\Filament\Resources\PostResource\Pages\CreatePost;
use App\Filament\Resources\PostResource\Pages\EditPost;
use App\Filament\Resources\ProjectResource\Pages\CreateProject;
use App\Filament\Resources\ProjectResource\Pages\EditProject;
use Filament\Actions\Action;
use Illuminate\Support\Str;

class TranslateSectionAction
{
    /**
     * Build an action that fills the target-language section from the source one.
     */
    public static function make(string $from, string $to): Action
    {
        return Action::make('translate'.Str::studly($from).'To'.Str::studly($to))
            ->label(self::labelFor($from, $to))
            ->icon('heroicon-o-language')
            ->visible(fn (): bool => auth()->user()?->hasAnyRole(['admin', 'super_admin', 'editor']) ?? false)
            ->action(fn (CreatePost|EditPost|CreateProject|EditProject $livewire) => $livewire->translateSection($from, $to));
    }

    private static function labelFor(string $from, string $to): string
    {
        return match ([$from, $to]) {
            ['es', 'en'] => __('Translate from Spanish'),
            default => __('Translate from English'),
        };
    }
}
