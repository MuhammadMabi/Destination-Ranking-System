<?php

namespace App\Filament\Widgets;

use App\Models\DestinationScore;
use App\Models\Criteria;
use App\Filament\Resources\Destinations\DestinationResource;
use App\Models\Rankings;
use App\Services\SawService;
use BcMath\Number;
use Filament\QueryBuilder\Constraints\NumberConstraint;
use Filament\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RankingTable extends TableWidget
{
    protected static ?string $heading = 'Destination Rankings';
    protected int|string|array $columnSpan = 'full';

    public function mount(): void
    {
        // hitung ulang SAW tiap buka page
        app(SawService::class)->calculate();
    }

    public function table(Table $table): Table
    {
        // ambil semua kriteria names
        $criterias = Criteria::pluck('name')->toArray();

        // build kolom untuk masing-masing kriteria
        // $criteriaColumns = collect($criterias)->map(
        //     fn($c) => TextColumn::make("details->{$c}")
        //         ->label($c)
        //         ->sortable()
        //         ->searchable(isIndividual: true)
        //         ->color(fn($record) => match (true) {
        //             $record->details[$c]['score'] > 0.8 => 'success',
        //             $record->details[$c]['score'] >= 0.5 => 'warning',
        //             default => 'danger',
        //         })
        //         ->badge(fn($record) => match (true) {
        //             $record->details[$c]['score'] > 0.8 => 'Excellent',
        //             $record->details[$c]['score'] >= 0.5 => 'Average',
        //             default => 'Poor',
        //         })
        //         ->getStateUsing(fn($record) => $record->details[$c]['score'] ?? 0)
        // )->toArray();

        $criteriaColumns = collect($criterias)->map(
            fn($c) => TextColumn::make("details->{$c}")
                ->label($c)
                ->sortable()
                ->searchable(isIndividual: true)
                ->color(function ($record) use ($c) {
                    $score = $record->details[$c]['score'] ?? 0;
                    $weight = $record->details[$c]['weight'] ?? 1;

                    // indikator dinamis berdasarkan persentase dari bobot max (relatif)
                    $relative = $score / $weight; // semakin dekat ke bobot max = lebih hijau
        
                    return match (true) {
                        $relative >= 1 => 'success',       // Excellent
                        $relative >= 0.6 => 'warning',     // Average
                        default => 'danger',               // Poor
                    };
                })
                ->badge(function ($record) use ($c) {
                    $score = $record->details[$c]['score'] ?? 0;
                    $weight = $record->details[$c]['weight'] ?? 1;

                    $relative = $score / $weight;

                    return match (true) {
                        $relative >= 1 => 'Excellent',
                        $relative >= 0.6 => 'Average',
                        default => 'Poor',
                    };
                })
                ->getStateUsing(fn($record) => $record->details[$c]['score'] ?? 0)
        )->toArray();

        // dynamic filters untuk tiap kriteria
        $dynamicConstraints = [
            TextConstraint::make('destination.name')->label('Destination Name'),
            TextConstraint::make('destination.location.name')->label('Location Name'),
            NumberConstraint::make('score')->label('Total Score'),
        ];

        // foreach ($criterias as $criteriaName) {
        //     $dynamicConstraints[] = NumberConstraint::make("details->{$criteriaName}")
        //         ->label($criteriaName);
        // }

        return $table
            ->query(Rankings::with(['destination', 'destination.location']))
            ->columns(array_merge([
                TextColumn::make('rank')->label('Rank')->sortable(),
                TextColumn::make('destination.name')->label('Destination')->sortable()->searchable(),
                TextColumn::make('destination.location.name')->label('Location')->sortable()->searchable(),
                TextColumn::make('score')->label('Total Score')->sortable()->searchable(isIndividual: true)
                    ->color(fn($record) => match (true) {
                        $record->score > 0.8 => 'success',
                        $record->score >= 0.5 => 'warning',
                        default => 'danger',
                    })
                    ->badge(fn($record) => match (true) {
                        $record->score > 0.8 => 'Excellent',
                        $record->score >= 0.5 => 'Average',
                        default => 'Poor',
                    }),
            ], $criteriaColumns))
            ->filters([
                QueryBuilder::make()
                    ->constraints($dynamicConstraints)
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->searchable(['destination.name', 'destination.location.name'])
            ->defaultSort('score', 'desc');
    }
}