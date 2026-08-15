<?php

namespace App\Domains\Inventory\Filament\Resources\Items\Pages;

use App\Domains\Inventory\Filament\Resources\Items\ItemResource;
use Filament\Resources\Pages\CreateRecord;
use Tests\Feature\Domains\Inventory\Filament\Resources\Items\Pages\CreateItemTest;

/**
 * @see CreateItemTest
 */
class CreateItem extends CreateRecord
{
    protected static string $resource = ItemResource::class;
}
