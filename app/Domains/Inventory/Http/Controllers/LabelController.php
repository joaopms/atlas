<?php

namespace App\Domains\Inventory\Http\Controllers;

use App\Domains\Inventory\Models\Container;
use App\Domains\Inventory\Models\Item;
use App\Domains\Inventory\Settings\InventorySettings;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Rawilk\Printing\Api\Cups\Types\Primitive\Keyword;
use Rawilk\Printing\Contracts\Printer;
use Rawilk\Printing\Facades\Printing;
use Spatie\LaravelPdf\Facades\Pdf;

class LabelController extends Controller
{
    public function show(InventorySettings $settings, string $entityId)
    {
        $entity = match (Str::charAt($entityId, 0)) {
            'C' => Container::query()->where('public_id', $entityId)->firstOrFail(),
            'I' => Item::query()->where('public_id', $entityId)->firstOrFail(),
            default => throw new InvalidArgumentException('Invalid ID')
        };

        // TODO Extract this
        config()->set('printing.driver', 'cups');
        config()->set('printing.drivers.cups.ip', $settings->label_printer_cups_server);
        config()->set('printing.drivers.cups.port', $settings->label_printer_cups_port);

        $printer = Printing::printers()->filter(fn (Printer $printer) => $printer->name() === $settings->label_printer_name)->first();
        throw_unless($printer, "Printer {$settings->label_printer_name} not found");

        Printing::newPrintTask()
            ->printer($printer)
            ->option('media', new Keyword($settings->label_printer_media))
            ->content(Pdf::view('Inventory::pdf.labels', ['models' => [$entity]])
                ->margins()
                ->inline($entityId)
                ->paperSize($settings->label_print_width, $settings->label_print_height)
                ->generatePdfContent()
            )
            ->send();

        return Pdf::view('Inventory::pdf.labels', ['models' => [$entity]])
            ->margins()
            ->inline($entityId)
            ->paperSize($settings->label_print_width, $settings->label_print_height);
    }
}
