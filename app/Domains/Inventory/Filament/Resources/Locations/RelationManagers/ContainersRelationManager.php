<?php

namespace App\Domains\Inventory\Filament\Resources\Locations\RelationManagers;

use App\Domains\Inventory\Filament\Resources\Containers\ContainerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ContainersRelationManager extends RelationManager
{
    protected static string $relationship = 'containers';

    protected static ?string $relatedResource = ContainerResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
