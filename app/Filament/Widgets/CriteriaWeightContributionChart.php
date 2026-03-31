<?php

namespace App\Filament\Widgets;

use App\Models\Criteria;
use App\Models\Rankings;
use Filament\Widgets\ChartWidget;

class CriteriaWeightContributionChart extends ChartWidget
{
    protected ?string $heading = 'Criteria Weight Contribution';

    protected function getData(): array
    {
        $rankings = Rankings::all();
        $criteria = Criteria::pluck('name')->toArray();

        $labels = $criteria;
        $data = collect($criteria)->map(function($c) use ($rankings) {
            return round($rankings->avg(fn($r) => $r->details[$c]['weight'] ?? 0), 5);
        })->toArray();

        // random color untuk tiap criteria
        $backgroundColors = collect($criteria)->map(function() {
            $r = rand(50, 200);
            $g = rand(50, 200);
            $b = rand(50, 200);
            return "rgba($r, $g, $b, 0.6)";
        })->toArray();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Average Weight',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                ]
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}