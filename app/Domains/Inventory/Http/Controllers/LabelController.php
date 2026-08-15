<?php

namespace App\Domains\Inventory\Http\Controllers;

use App\Domains\Inventory\Actions\GeneratePdfLabels;
use App\Domains\Inventory\Models\Container;
use App\Domains\Inventory\Models\Item;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;

class LabelController extends Controller
{
    public function show(GeneratePdfLabels $generatePdfLabels, string $entityId)
    {
        $entity = match (Str::charAt($entityId, 0)) {
            'C' => Container::query()->where('public_id', $entityId)->firstOrFail(),
            'I' => Item::query()->where('public_id', $entityId)->firstOrFail(),
            default => throw new InvalidArgumentException('Invalid ID')
        };

        return $generatePdfLabels->handle(collect([$entity]));
    }

    public function showMultiple(Request $request, GeneratePdfLabels $generatePdfLabels)
    {
        $request->validate([
            'ids' => 'required|array',
        ]);

        $entities = $request->collect('ids')
            ->groupBy(fn (string $entityId) => Str::charAt($entityId, 0))
            ->map(fn (Collection $ids, string $publicIdPrefix) => match ($publicIdPrefix) {
                'C' => Container::query()->whereIn('public_id', $ids)->get(),
                'I' => Item::query()->whereIn('public_id', $ids)->get(),
                default => null
            })
            ->filter()
            ->flatten();

        return $generatePdfLabels->handle($entities->collect());
    }
}
