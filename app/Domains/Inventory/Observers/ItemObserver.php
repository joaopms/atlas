<?php

namespace App\Domains\Inventory\Observers;

use App\Domains\Inventory\Models\Item;

use function Ramsey\Uuid\v4;

class ItemObserver
{
    public function creating(Item $container): void
    {
        $container->public_id = v4();
    }
}
