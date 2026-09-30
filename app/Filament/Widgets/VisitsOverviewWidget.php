<?php

namespace App\Filament\Widgets;

use App\Models\Visit;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class VisitsOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        $user = auth()->user();

        return $user !== null && ($user->hasRole('admin') || $user->hasRole('super_admin'));
    }

    protected function getStats(): array
    {
        $today = Carbon::today();
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        $todayVisits = Visit::whereDate('visited_at', $today)->count();
        $yesterdayVisits = Visit::whereDate('visited_at', $today->copy()->subDay())->count();

        $diffToday = $todayVisits - $yesterdayVisits;
        $todayDescription = $diffToday >= 0
            ? "+{$diffToday} vs ayer"
            : "{$diffToday} vs ayer";

        $total30Days = Visit::where('visited_at', '>=', $thirtyDaysAgo)->count();

        // Trend for the last 7 days
        $trend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $trend[] = (float) Visit::whereDate('visited_at', $date)->count();
        }

        $unique30Days = Visit::where('visited_at', '>=', $thirtyDaysAgo)
            ->whereNotNull('ip_hash')
            ->distinct('ip_hash')
            ->count('ip_hash');

        /** @var object{country_code: string, country_name: string, total: int}|null $topCountry */
        $topCountry = Visit::query()
            ->select('country_code', 'country_name', DB::raw('count(*) as total'))
            ->where('visited_at', '>=', $thirtyDaysAgo)
            ->groupBy('country_code', 'country_name')
            ->orderByDesc('total')
            ->first();

        $topCountryStat = 'Sin datos';
        $topCountryDesc = 'Esperando visitas';
        if ($topCountry !== null) {
            $tempVisit = new Visit(['country_code' => $topCountry->country_code]);
            $topCountryStat = $tempVisit->country_flag.' '.$topCountry->country_name;
            $topCountryDesc = "{$topCountry->total} visitas en 30 días";
        }

        return [
            Stat::make('Visitas Hoy', (string) $todayVisits)
                ->description($todayDescription)
                ->descriptionIcon($diffToday >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($diffToday >= 0 ? 'success' : 'warning'),

            Stat::make('Visitas (30 días)', (string) $total30Days)
                ->description('Evolución últimos 7 días')
                ->chart($trend)
                ->color('primary'),

            Stat::make('Visitantes Únicos', (string) $unique30Days)
                ->description('Identificados anónimamente')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),

            Stat::make('País Principal', $topCountryStat)
                ->description($topCountryDesc)
                ->color('primary'),
        ];
    }
}
