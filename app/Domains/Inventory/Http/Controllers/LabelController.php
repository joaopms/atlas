<?php

namespace App\Domains\Inventory\Http\Controllers;

use App\Domains\Inventory\Models\Container;
use App\Domains\Inventory\Models\Item;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Spatie\LaravelPdf\Facades\Pdf;

class LabelController extends Controller
{
    public function show(string $entityId)
    {
        $entity = match (Str::charAt($entityId, 0)) {
            'C' => Container::query()->where('public_id', $entityId)->firstOrFail(),
            'I' => Item::query()->where('public_id', $entityId)->firstOrFail(),
            default => throw new InvalidArgumentException('Invalid ID')
        };

        return Pdf::view('Inventory::pdf.labels', ['models' => [$entity]])
            ->margins()
            ->inline($entityId)
            ->paperSize('62', '29');
    }
}
