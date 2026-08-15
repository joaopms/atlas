<?php

namespace Tests\Feature\Domains\Inventory\Filament\Resources\Locations\Pages;

use App\Domains\Inventory\Filament\Resources\Locations\Pages\ListLocations;
use App\Domains\Inventory\Models\Location;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see ListLocations
 */
class ListLocationsTest extends TestCase
{
    #[Test]
    public function page_loads()
    {
        $locations = Location::factory()->count(5)->create();

        Livewire::test(ListLocations::class)
            ->assertOk()
            ->assertCanSeeTableRecords($locations);
    }
}
