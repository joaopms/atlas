<?php

namespace Tests\Feature\Domains\Inventory\Filament\Resources\Containers\Pages;

use App\Domains\Inventory\Filament\Resources\Containers\Pages\CreateContainer;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see CreateContainer
 */
class CreateContainerTest extends TestCase
{
    #[Test]
    public function page_loads()
    {
        Livewire::test(CreateContainer::class)
            ->assertOk()
            ->assertSchemaComponentHidden('label')
            ->assertSchemaComponentHidden('public_id');
    }

    #[Test]
    public function store_sets_public_id()
    {
        Livewire::test(CreateContainer::class)
            ->fillForm([
                'name' => 'Test',
            ])
            ->call('create');

        $this->assertDatabaseCount('inventory_containers', 1);
        $this->assertDatabaseHas('inventory_containers', [
            'public_id' => 'CFWTX',
        ]);
    }
}
