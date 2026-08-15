<?php

namespace Tests\Feature\Domains\Inventory\Filament\Resources\Containers\Pages;

use App\Domains\Inventory\Filament\Resources\Containers\Pages\ListContainers;
use App\Domains\Inventory\Models\Container;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see ListContainers
 */
class ListContainersTest extends TestCase
{
    #[Test]
    public function page_loads()
    {
        $containers = Container::factory()->count(5)->create();

        Livewire::test(ListContainers::class)
            ->assertOk()
            ->assertCanSeeTableRecords($containers);
    }

    #[Test]
    public function can_generate_labels_in_bulk()
    {
        $containers = Container::factory()->count(5)->create();

        Livewire::test(ListContainers::class)
            ->selectTableRecords($containers)
            ->callAction(TestAction::make('generate_labels')->table()->bulk())
            ->assertRedirectToRoute(
                'filament.main.inventory.entities.label',
                ['ids' => $containers->pluck('public_id')->values()->all()]
            );
    }
}
