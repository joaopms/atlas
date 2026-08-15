<?php

namespace Tests\Feature\Domains\Inventory\Filament\Resources\Locations\Pages;

use App\Domains\Inventory\Filament\Resources\Locations\Pages\CreateLocation;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see CreateLocation
 */
class CreateLocationTest extends TestCase
{
    #[Test]
    public function page_loads()
    {
        Livewire::test(CreateLocation::class)
            ->assertOk();
    }
}
