<?php

namespace App\Domains\Inventory\Actions;

use App\Domains\Inventory\Models\InventoryEntity;
use Illuminate\Support\Collection;
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\LaravelPdf\PdfBuilder;

class GeneratePdfLabels
{
    /**
     * @param  Collection<InventoryEntity>  $models
     */
    public function handle(Collection $models): PdfBuilder
    {
        $pdf = Pdf::view('Inventory::pdf.labels', ['models' => $models])
            ->margins()
            ->paperSize('62', '29');

        if ($models->count() == 1) {
            /** @var InventoryEntity $model */
            $model = $models->first();

            return $pdf->name($model->public_id);
        }

        return $pdf->inline('labels');
    }
}
