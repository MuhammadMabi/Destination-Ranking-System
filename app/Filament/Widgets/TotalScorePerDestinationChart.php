<?php

namespace App\Filament\Widgets;

use App\Models\Rankings;
use Filament\Widgets\ChartWidget;

class TotalScorePerDestinationChart extends ChartWidget
{
    protected ?string $heading = 'Total Score Per Destination';

    protected function getData(): array
    {
        $rankings = Rankings::with('destination')->take(10)->get();
        $labels = $rankings->pluck('destination.name')->toArray();
        $data = $rankings->pluck('score')->map(fn($s) => round($s, 5))->toArray();

        // random color per bar
        $backgroundColors = collect($data)->map(function($score) {
            $r = rand(50, 200);
            $g = rand(50, 200);
            $b = rand(50, 200);
            return "rgba($r, $g, $b, 0.7)";
        })->toArray();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Score',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                ]
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}