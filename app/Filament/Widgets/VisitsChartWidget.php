<?php

namespace App\Filament\Widgets;

use App\Models\Visit;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class VisitsChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '280px';

    public ?string $filter = '14';

    public static function canView(): bool
    {
        $user = auth()->user();

        return $user !== null && ($user->hasRole('admin') || $user->hasRole('super_admin'));
    }

    protected ?string $heading = 'Evolución de Visitas';

    protected ?string $description = 'Comparativa diaria de páginas vistas y visitantes únicos.';

    protected function getFilters(): ?array
    {
        return [
            '7' => 'Últimos 7 días',
            '14' => 'Últimos 14 días',
            '30' => 'Últimos 30 días',
        ];
    }

    protected function getData(): array
    {
        $days = in_array($this->filter, ['7', '14', '30'], true) ? (int) $this->filter : 14;

        $labels = [];
        $totalVisits = [];
        $uniqueVisitors = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateString = $date->toDateString();

            $labels[] = $date->format('d M');

            $totalVisits[] = Visit::whereDate('visited_at', $dateString)->count();
            $uniqueVisitors[] = Visit::whereDate('visited_at', $dateString)
                ->whereNotNull('ip_hash')
                ->distinct('ip_hash')
                ->count('ip_hash');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Visitas',
                    'data' => $totalVisits,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Visitantes Únicos',
                    'data' => $uniqueVisitors,
                    'borderColor' => '#38bdf8',
                    'backgroundColor' => 'rgba(56, 189, 248, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
