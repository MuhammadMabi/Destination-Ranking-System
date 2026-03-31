<?php

namespace App\Filament\Widgets;

use App\Models\Criteria;
use App\Models\Rankings;
use Filament\Widgets\ChartWidget;

class CriteriaPerformancePerTopDestinationChart extends ChartWidget
{
    protected ?string $heading = 'Criteria Performance Per Top Destination Chart';

    protected function getData(): array
    {
        $rankings = Rankings::with('destination')->get()->sortByDesc('score')->take(3);
        $criteria = Criteria::pluck('name')->toArray();

        $colors = [
            ['borderColor' => 'rgb(0, 34, 102)', 'backgroundColor' => 'rgba(0, 34, 102, 0.2)'],   // navy
            ['borderColor' => 'rgb(0, 102, 51)', 'backgroundColor' => 'rgba(0, 102, 51, 0.2)'],   // green dark
            ['borderColor' => 'rgb(102, 0, 102)', 'backgroundColor' => 'rgba(102, 0, 102, 0.2)'], // purple
        ];

        $datasets = $rankings->values()->map(function ($r, $index) use ($criteria, $colors) {
            return [
                'label' => $r->destination->name,
                'data' => collect($criteria)->map(fn($c) => round($r->details[$c]['score'] ?? 0, 5))->toArray(),
                'fill' => true,
                'backgroundColor' => $colors[$index]['backgroundColor'],
                'borderColor' => $colors[$index]['borderColor'],
                'pointBackgroundColor' => $colors[$index]['borderColor'],
                'pointBorderColor' => '#fff',
                'pointHoverBackgroundColor' => '#fff',
                'pointHoverBorderColor' => $colors[$index]['borderColor'],
            ];
        })->toArray();

        return [
            'labels' => $criteria,
            'datasets' => $datasets,
        ];
    }

    protected function getType(): string
    {
        return 'radar';
    }

    protected function getOptions(): array
    {
        return [
            'elements' => [
                'line' => [
                    'borderWidth' => 3,
                ],
            ],
        ];
    }
}