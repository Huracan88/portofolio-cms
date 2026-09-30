<?php

namespace App\Filament\Widgets;

use App\Models\Visit;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\DB;

class TopPagesWidget extends TableWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        $user = auth()->user();

        return $user !== null && ($user->hasRole('admin') || $user->hasRole('super_admin'));
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Visit::query()
                    ->select('path', DB::raw('MIN(id) as id'), DB::raw('count(*) as total_visits'), DB::raw('max(visited_at) as last_visit'))
                    ->where('visited_at', '>=', now()->subDays(30))
                    ->groupBy('path')
                    ->orderByDesc('total_visits')
            )
            ->heading('Páginas Más Visitadas (30 días)')
            ->columns([
                TextColumn::make('path')
                    ->label('Ruta')
                    ->limit(35)
                    ->tooltip(fn (Visit $record): string => $record->path)
                    ->weight('medium'),

                TextColumn::make('total_visits')
                    ->label('Visitas')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('last_visit')
                    ->label('Última')
                    ->since()
                    ->color('gray'),
            ])
            ->paginated([5, 10])
            ->defaultPaginationPageOption(5);
    }
}
