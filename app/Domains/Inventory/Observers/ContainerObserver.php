<?php

namespace App\Domains\Inventory\Observers;

use App\Domains\Inventory\Models\Container;

use function Ramsey\Uuid\v4;

class ContainerObserver
{
    public function creating(Container $container): void
    {
        $container->public_id = v4();
    }
}
