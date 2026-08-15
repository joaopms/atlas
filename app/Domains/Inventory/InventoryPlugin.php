<?php

namespace App\Domains\Inventory;

use App\Domains\Inventory\Http\Controllers\LabelController;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Illuminate\Support\Facades\Route;

class InventoryPlugin implements Plugin
{
    public function getId(): string
    {
        return 'inventory';
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function register(Panel $panel): void
    {
        $panel
            ->discoverResources(
                in: __DIR__.'/Filament/Resources',
                for: 'App\\Domains\\Inventory\\Filament\\Resources',
            )
            ->discoverPages(
                in: __DIR__.'/Filament/Pages',
                for: 'App\\Domains\\Inventory\\Filament\\Pages',
            )
            ->discoverWidgets(
                in: __DIR__.'/Filament/Widgets',
                for: 'App\\Domains\\Inventory\\Filament\\Widgets',
            )
            ->routes(function () {
                Route::prefix('inventory')->name('inventory.')
                    ->group(function () {
                        Route::get('/items/{entityId}/label', [LabelController::class, 'show'])->name('items.label');
                        Route::get('/containers/{entityId}/label', [LabelController::class, 'show'])->name('containers.label');

                        Route::get('/entities/label', [LabelController::class, 'showMultiple'])->name('entities.label');
                    });
            });
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
