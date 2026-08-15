<?php

namespace Tests\Feature\Domains\Inventory\Filament\Resources\Items\Pages;

use App\Domains\Inventory\Filament\Resources\Items\Pages\ListItems;
use App\Domains\Inventory\Models\Item;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see ListItems
 */
class ListItemsTest extends TestCase
{
    #[Test]
    public function page_loads()
    {
        $items = Item::factory()->count(5)->create();

        Livewire::test(ListItems::class)
            ->assertOk()
            ->assertCanSeeTableRecords($items);
    }

    #[Test]
    public function can_generate_labels_in_bulk()
    {
        $items = Item::factory()->count(5)->create();

        Livewire::test(ListItems::class)
            ->selectTableRecords($items)
            ->callAction(TestAction::make('generate_labels')->table()->bulk())
            ->assertRedirectToRoute(
                'filament.main.inventory.entities.label',
                ['ids' => $items->pluck('public_id')->values()->all()]
            );
    }
}
