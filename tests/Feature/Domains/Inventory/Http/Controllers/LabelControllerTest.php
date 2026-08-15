<?php

namespace Tests\Feature\Domains\Inventory\Http\Controllers;

use App\Domains\Inventory\Http\Controllers\LabelController;
use App\Domains\Inventory\Models\Container;
use App\Domains\Inventory\Models\Item;
use PHPUnit\Framework\Attributes\Test;
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\LaravelPdf\PdfBuilder;
use Tests\TestCase;

/**
 * @see LabelController
 */
class LabelControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Pdf::fake();
    }

    #[Test]
    public function show_an_item_label()
    {
        $item = Item::factory()->create();

        $this->get(route('filament.main.inventory.items.label', $item->public_id))
            ->assertOk();

        Pdf::assertRespondedWithPdf(fn (PdfBuilder $pdf) => $pdf->downloadName === "{$item->public_id}.pdf");
    }

    #[Test]
    public function show_a_container_label()
    {
        $container = Container::factory()->create();

        $this->get(route('filament.main.inventory.containers.label', $container->public_id))
            ->assertOk();

        Pdf::assertRespondedWithPdf(fn (PdfBuilder $pdf) => $pdf->downloadName === "{$container->public_id}.pdf");
    }

    #[Test]
    public function not_show_an_inexistent_item()
    {
        $this->get(route('filament.main.inventory.items.label', 'Inoop'))
            ->assertNotFound();
    }

    #[Test]
    public function not_show_an_inexistent_container_label()
    {
        $this->get(route('filament.main.inventory.containers.label', 'Cnoop'))
            ->assertNotFound();
    }

    #[Test]
    public function show_multiple_labels()
    {
        $container = Container::factory()->create();
        $item = Item::factory()->create();

        $this->get(route(
            'filament.main.inventory.entities.label',
            ['ids' => [$container->public_id, $item->public_id]]
        ))
            ->assertOk();

        Pdf::assertRespondedWithPdf(fn (PdfBuilder $pdf) => $pdf->downloadName === 'labels.pdf');
    }

    #[Test]
    public function show_multiple_inexistent_labels()
    {
        $this->get(route(
            'filament.main.inventory.entities.label',
            ['ids' => ['noop']]
        ))
            ->assertOk();

        Pdf::assertRespondedWithPdf(fn (PdfBuilder $pdf) => $pdf->downloadName === 'labels.pdf');
    }

    #[Test]
    public function show_requires_ids()
    {
        $this->get(route('filament.main.inventory.entities.label'))
            ->assertInvalid(['ids']);
    }
}
