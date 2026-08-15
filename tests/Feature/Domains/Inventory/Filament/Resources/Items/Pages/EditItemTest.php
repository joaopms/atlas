<?php

namespace Tests\Feature\Domains\Inventory\Filament\Resources\Items\Pages;

use App\Domains\Inventory\Filament\Resources\Items\Pages\EditItem;
use App\Domains\Inventory\Models\Item;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see EditItem
 */
class EditItemTest extends TestCase
{
    #[Test]
    public function page_loads()
    {
        $item = Item::factory()->create();

        Livewire::test(EditItem::class, ['record' => $item->public_id])
            ->assertOk()
            ->assertSchemaComponentVisible('label')
            ->assertSchemaComponentVisible('public_id');
    }
}
