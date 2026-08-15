<?php

namespace Tests\Feature\Domains\Inventory\Filament\Resources\Items\Pages;

use App\Domains\Inventory\Filament\Resources\Items\Pages\CreateItem;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see CreateItem
 */
class CreateItemTest extends TestCase
{
    #[Test]
    public function page_loads()
    {
        Livewire::test(CreateItem::class)
            ->assertOk()
            ->assertSchemaComponentHidden('label')
            ->assertSchemaComponentHidden('public_id');
    }

    #[Test]
    public function store_sets_public_id()
    {
        Livewire::test(CreateItem::class)
            ->fillForm([
                'name' => 'Test',
            ])
            ->call('create');

        $this->assertDatabaseCount('inventory_items', 1);
        $this->assertDatabaseHas('inventory_items', [
            'public_id' => 'IFWTX',
        ]);
    }
}
