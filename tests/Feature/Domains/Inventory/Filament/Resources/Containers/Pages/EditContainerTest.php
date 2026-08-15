<?php

namespace Tests\Feature\Domains\Inventory\Filament\Resources\Containers\Pages;

use App\Domains\Inventory\Filament\Resources\Containers\Pages\EditContainer;
use App\Domains\Inventory\Models\Container;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see EditContainer
 */
class EditContainerTest extends TestCase
{
    #[Test]
    public function page_loads()
    {
        $container = Container::factory()->create();

        Livewire::test(EditContainer::class, ['record' => $container->public_id])
            ->assertOk()
            ->assertSchemaComponentVisible('label')
            ->assertSchemaComponentVisible('public_id');
    }
}
