<?php

namespace App\Domains\Inventory\Http\Controllers;

use App\Domains\Inventory\Models\Container;
use App\Domains\Inventory\Models\Item;
use App\Domains\Inventory\Settings\InventorySettings;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use InvalidArgumentException;
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

        return Pdf::view('Inventory::pdf.labels', ['models' => [$entity]])
            ->margins()
            ->inline($entityId)
            ->paperSize($settings->label_print_width, $settings->label_print_height);
    }
}
