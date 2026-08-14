<?php

namespace App\Domains\Inventory;

use Filament\Panel;
use Illuminate\Support\ServiceProvider;

class InventoryServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     */
    protected string $namespace = 'App\Domains\Inventory\Http\Controllers';

    public function register(): void
    {
        $this->loadMigrationsFrom(app_path('Domains/Inventory/database/migrations'));

        $this->loadViewsFrom(app_path('Domains/Inventory/resources/views'), 'Inventory');

        Panel::configureUsing(function (Panel $panel): void {
            if ($panel->getId() !== 'main') {
                return;
            }

            $panel->plugin(InventoryPlugin::make());
        });
    }

    public function boot(): void
    {
        parent::register();
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }

    /**
     * Get the listener directories that should be used to discover events.
     */
    protected function discoverEventsWithin(): array
    {
        return [
            __DIR__.'\Listeners',
        ];
    }
}
