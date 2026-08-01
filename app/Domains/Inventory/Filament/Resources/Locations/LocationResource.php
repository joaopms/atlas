<?php

namespace App\Domains\Inventory\Filament\Resources\Locations;

use App\Domains\Inventory\Filament\Resources\Locations\Pages\CreateLocation;
use App\Domains\Inventory\Filament\Resources\Locations\Pages\EditLocation;
use App\Domains\Inventory\Filament\Resources\Locations\Pages\ListLocations;
use App\Domains\Inventory\Filament\Resources\Locations\RelationManagers\ContainersRelationManager;
use App\Domains\Inventory\Filament\Resources\Locations\Schemas\LocationForm;
use App\Domains\Inventory\Filament\Resources\Locations\Tables\LocationsTable;
use App\Domains\Inventory\Models\Location;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return LocationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LocationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ContainersRelationManager::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLocations::route('/'),
            'create' => CreateLocation::route('/create'),
            'edit' => EditLocation::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
