<?php

namespace Tests\Feature\Domains\Inventory\Filament\Resources\Locations\Pages;

use App\Domains\Inventory\Filament\Resources\Locations\Pages\EditLocation;
use App\Domains\Inventory\Models\Location;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see EditLocation
 */
class EditLocationTest extends TestCase
{
    #[Test]
    public function page_loads()
    {
        $location = Location::factory()->create();

        Livewire::test(EditLocation::class, ['record' => $location->id])
            ->assertOk();
    }
}
