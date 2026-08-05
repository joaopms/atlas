<?php

namespace App\Domains\Inventory;

use App\Domains\Inventory\Models\Container;
use App\Domains\Inventory\Models\Item;
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
                Route::get('/containers/{container}/label', function (Container $container) {
                    return view('Inventory::filament.schemas.components.label-container', ['model' => $container]);
                });

                Route::get('/items/{item}/label', function (Item $item) {
                    return view('Inventory::filament.schemas.components.label-item', ['model' => $item]);
                });
            });
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
