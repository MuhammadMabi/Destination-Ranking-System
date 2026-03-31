<!-- 
namespace App\Filament\Widgets;

use App\Models\Destinations;
use App\Services\SawService;
use Filament\QueryBuilder\Constraints\NumberConstraint;
use Filament\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Collection;

class RankingTable extends TableWidget
{
    protected static ?string $heading = 'Destination Rankings';
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $rankings = app(SawService::class)->calculate();

        // ambil semua kriteria names untuk header
        $criterias = \App\Models\Criteria::pluck('name')->toArray();

        // build data table
        $data = collect($rankings)
            ->map(function ($score, $destinationId) use ($criterias) {
                $destination = Destinations::find($destinationId);

                $row = [
                    'name' => $destination?->name,
                    'location' => $destination?->location?->name ?? 'N/A',
                    'score' => round($score['total_score'], 4),
                ];

                // tambahin masing-masing nilai kriteria
                foreach ($criterias as $criteriaName) {
                    $row[$criteriaName] = round($score['details'][$criteriaName]['score'] ?? 0, 4);
                }

                return $row;
            })
            ->values()
            ->map(function ($item, $index) {
                $item['rank'] = $index + 1;
                return $item;
            });

        // build kolom untuk masing-masing kriteria
        $criteriaColumns = collect($criterias)->map(
            fn($c) => TextColumn::make($c)
                ->label($c)
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->searchable(isIndividual: true)
        )->toArray();

        $dynamicConstraints = [
            TextConstraint::make('name')->label('Destination Name'),
            TextConstraint::make('location')->label('Location Name'),
            NumberConstraint::make('score')->label('Total Score'),
        ];

        foreach ($criterias as $criteriaName) {
            $dynamicConstraints[] = NumberConstraint::make($criteriaName)
                ->label($criteriaName);
        }

        return $table
            ->records(fn(): Collection => $data)
            ->columns(array_merge([
                TextColumn::make('rank')->label('Rank')->sortable()->searchable(isIndividual: true),
                TextColumn::make('name')->label('Destination')->sortable()->searchable(isIndividual: true),
                TextColumn::make('location')->label('Location')->sortable()->searchable(isIndividual: true),
                TextColumn::make('score')->label('Total Score')->sortable()->searchable(isIndividual: true),
            ], $criteriaColumns))
            ->filters([
                QueryBuilder::make()
                    ->constraints($dynamicConstraints)
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->deferFilters()
            ->groups(['location'])
            ->searchable(['name', 'location']);
    }
} -->