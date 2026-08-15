<?php

namespace App\Domains\Inventory\Filament\Resources\Items\Pages;

use App\Domains\Inventory\Filament\Resources\Items\ItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Tests\Feature\Domains\Inventory\Filament\Resources\Items\Pages\ListItemsTest;

/**
 * @see ListItemsTest
 */
class ListItems extends ListRecords
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
