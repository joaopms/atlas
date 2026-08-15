<?php

namespace App\Domains\Inventory\Filament\Resources\Containers\Pages;

use App\Domains\Inventory\Filament\Resources\Containers\ContainerResource;
use Filament\Resources\Pages\CreateRecord;
use Tests\Feature\Domains\Inventory\Filament\Resources\Containers\Pages\CreateContainerTest;

/**
 * @see CreateContainerTest
 */
class CreateContainer extends CreateRecord
{
    protected static string $resource = ContainerResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
