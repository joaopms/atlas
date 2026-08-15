<?php

namespace App\Domains\Inventory\Filament\Resources\Locations\Pages;

use App\Domains\Inventory\Filament\Resources\Locations\LocationResource;
use Filament\Resources\Pages\CreateRecord;
use Tests\Feature\Domains\Inventory\Filament\Resources\Locations\Pages\CreateLocationTest;

/**
 * @see CreateLocationTest
 */
class CreateLocation extends CreateRecord
{
    protected static string $resource = LocationResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
