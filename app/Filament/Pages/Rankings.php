<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\RankingTable;
use Filament\Pages\Page;

class Rankings extends Page
{
    protected string $view = 'filament.pages.rankings';

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-trophy';

    protected function getHeaderWidgets(): array
    {
        return [
            RankingTable::class,
        ];
    }
}
