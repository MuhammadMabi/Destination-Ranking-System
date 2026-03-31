<?php

namespace App\Filament\Resources\Destinations;

use App\Filament\Resources\Destinations\Pages\CreateDestination;
use App\Filament\Resources\Destinations\Pages\EditDestination;
use App\Filament\Resources\Destinations\Pages\ListDestinations;
use App\Filament\Resources\Destinations\Pages\ViewDestination;
use App\Filament\Resources\Destinations\Schemas\DestinationForm;
use App\Filament\Resources\Destinations\Schemas\DestinationInfolist;
use App\Filament\Resources\Destinations\Tables\DestinationsTable;
use App\Models\Destination;
use App\Models\Destinations;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DestinationResource extends Resource
{
    protected static ?string $model = Destinations::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-map';

    protected static ?string $recordTitleAttribute = 'Destinations';
    
    protected static string|UnitEnum|null $navigationGroup = 'Management';

    public static function form(Schema $schema): Schema
    {
        return DestinationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DestinationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DestinationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDestinations::route('/'),
            'create' => CreateDestination::route('/create'),
            'view' => ViewDestination::route('/{record}'),
            'edit' => EditDestination::route('/{record}/edit'),
        ];
    }
}
