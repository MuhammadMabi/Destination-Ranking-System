<?php

namespace App\Filament\Resources\Destinations\Schemas;

use App\Models\Criteria;
use Faker\Provider\Image;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\Slider\Enums\PipsMode;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DestinationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Destination Information')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Select::make('location_id')
                            ->relationship('location', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Textarea::make('description')
                            ->required()
                            ->maxLength(255),

                        FileUpload::make('image')
                            ->image()
                            ->maxSize(1024)
                            ->acceptedFileTypes(['image/*']),
                    ]),

                Section::make('Destination Criteria')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('destinationCriterias')
                            ->hiddenLabel()
                            ->relationship()
                            ->addable(false)
                            ->deletable(false)
                            ->default(function () {
                                return Criteria::all()->map(function ($c) {
                                    return [
                                        'criteria_id' => $c->id,
                                    ];
                                })->toArray();
                            })
                            ->afterStateHydrated(function ($component, $state) {

                                $state = $state ?? [];

                                $existing = collect($state)
                                    ->pluck('criteria_id')
                                    ->filter()
                                    ->toArray();

                                $allCriteria = Criteria::all();

                                $missing = $allCriteria->whereNotIn('id', $existing);

                                // if state is empty, it means it's CREATE, so we can just set all criteria to the state
                                if (empty($state)) {
                                    $component->state(
                                        $allCriteria->map(fn($c) => [
                                            'criteria_id' => $c->id,
                                        ])->toArray()
                                    );
                                    return;
                                }

                                // if there are missing criteria, it means it's EDIT and there are new criteria added, so we need to add the new criteria to the state
                                if ($missing->isNotEmpty()) {
                                    $additional = $missing->map(fn($c) => [
                                        'criteria_id' => $c->id,
                                        'criteria_label' => $c->name,
                                        'criteria_type' => $c->type,
                                        'value' => 5, // default value
                                    ])->toArray();

                                    $component->state([
                                        ...$state,
                                        ...$additional,
                                    ]);
                                }
                            })
                            ->table([
                                TableColumn::make('Criteria')->width(150),
                                TableColumn::make('Value')->width(150),
                            ])
                            ->schema([
                                Hidden::make('criteria_id'),

                                TextInput::make('criteria_label')
                                    ->label('Criteria')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->afterStateHydrated(function ($component, $state, $get) {
                                        $id = $get('criteria_id');

                                        if ($id) {
                                            $component->state(
                                                Criteria::find($id)?->name
                                            );
                                        }
                                    }),

                                Slider::make('value')
                                    ->helperText(fn($get, $record) => Criteria::find($get('criteria_id'))?->type === 'benefit'
                                        ? '1 = worst, 10 = best (Benefit)'
                                        : '1 = cheapest/closest, 10 = most expensive/farthest (Cost)')
                                    ->range(1, 10)
                                    ->fillTrack()
                                    ->pips(PipsMode::Steps)
                                    ->step(1)
                                    ->default(5)
                                    ->required(),
                            ])
                            ->columnSpanFull(),
                    ]),

            ]);
    }
}
