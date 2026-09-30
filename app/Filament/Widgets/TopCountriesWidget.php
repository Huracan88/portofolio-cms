<?php

namespace App\Filament\Widgets;

use App\Models\Visit;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\DB;

class TopCountriesWidget extends TableWidget
{
    protected static ?int $sort = 4;

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
                    ->select('country_code', 'country_name', DB::raw('MIN(id) as id'), DB::raw('count(*) as total_visits'), DB::raw('count(distinct ip_hash) as unique_visitors'))
                    ->where('visited_at', '>=', now()->subDays(30))
                    ->groupBy('country_code', 'country_name')
                    ->orderByDesc('total_visits')
            )
            ->heading('Top Países (30 días)')
            ->columns([
                TextColumn::make('country')
                    ->label('País')
                    ->state(fn (Visit $record): string => $record->country_flag.' '.$record->country_name)
                    ->description(fn (Visit $record): string => $record->country_code)
                    ->weight('medium'),

                TextColumn::make('total_visits')
                    ->label('Visitas')
                    ->badge()
                    ->color('success'),

                TextColumn::make('unique_visitors')
                    ->label('Únicos')
                    ->badge()
                    ->color('info'),
            ])
            ->paginated([5, 10])
            ->defaultPaginationPageOption(5);
    }
}
