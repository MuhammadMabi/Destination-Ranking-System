<?php

namespace App\Filament\Resources\Criterias\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\Slider\Enums\PipsMode;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CriteriaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),

                Slider::make('weight')
                    ->helperText('1 = lowest importance, 10 = highest importance')
                    ->range(1, 10)
                    ->fillTrack()
                    ->pips(PipsMode::Steps)
                    ->step(1)
                    ->label('Weight')
                    ->default(5)
                    ->required(),

                Select::make('type')
                    ->options(['cost' => 'Cost', 'benefit' => 'Benefit'])
                    ->required()
                    ->searchable(),
            ]);
    }
}
