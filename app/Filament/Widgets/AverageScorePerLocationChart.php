<?php

namespace App\Filament\Widgets;

use App\Models\Rankings;
use Filament\Widgets\ChartWidget;

class AverageScorePerLocationChart extends ChartWidget
{
    protected ?string $heading = 'Average Score Per Location Chart';

    protected function getData(): array
    {
        $rankings = Rankings::with('destination.location')->get();
        $locations = $rankings->groupBy(fn($r) => $r->destination->location->name ?? 'N/A');

        $labels = $locations->keys()->toArray();
        $data = $locations->map(fn($group) => round($group->avg('score'), 5))->values()->toArray();

        // bikin random color per lokasi
        $backgroundColors = collect($labels)->map(function () {
            $r = rand(50, 200);
            $g = rand(50, 200);
            $b = rand(50, 200);
            return "rgba($r, $g, $b, 0.5)";
        })->toArray();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Average Score',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                ]
            ],
        ];
    }

    protected function getType(): string
    {
        return 'polarArea';
    }
}