<?php

namespace App\Domains\Inventory\Filament\Widgets;

use App\Domains\Inventory\Models\Container;
use App\Domains\Inventory\Models\Item;
use App\Domains\Inventory\Models\Location;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Locations', Location::count()),
            Stat::make('Containers', Container::count()),
            Stat::make('Items', Item::count()),
        ];
    }

    protected ?string $heading = 'Inventory';
}
