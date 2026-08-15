<?php

namespace App\Domains\Inventory\Http\Controllers;

use App\Domains\Inventory\Actions\GeneratePdfLabels;
use App\Domains\Inventory\Models\Container;
use App\Domains\Inventory\Models\Item;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Illuminate\Support\Str;
use Tests\Feature\Domains\Inventory\Http\Controllers\LabelControllerTest;

/**
 * @see LabelControllerTest
 */
class LabelController extends Controller
{
    public function show(GeneratePdfLabels $generatePdfLabels, string $entityId)
    {
        $entity = $this->getEntities(collect([$entityId]))->first();
        abort_unless((bool) $entity, 404);

        return $generatePdfLabels->handle(collect([$entity]));
    }

    public function showMultiple(Request $request, GeneratePdfLabels $generatePdfLabels)
    {
        $request->validate([
            'ids' => 'required|array',
        ]);

        $entities = $this->getEntities($request->collect('ids'));

        return $generatePdfLabels->handle($entities->collect());
    }

    public function getEntities(Collection $ids): Enumerable
    {
        return $ids
            ->groupBy(fn (string $entityId) => Str::charAt($entityId, 0))
            ->map(fn (Collection $ids, string $publicIdPrefix) => match ($publicIdPrefix) {
                'C' => Container::query()->whereIn('public_id', $ids)->get(),
                'I' => Item::query()->whereIn('public_id', $ids)->get(),
                default => null
            })
            ->filter()
            ->flatten();
    }
}
