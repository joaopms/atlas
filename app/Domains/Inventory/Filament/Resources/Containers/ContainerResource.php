<?php

namespace App\Domains\Inventory\Filament\Resources\Containers;

use App\Domains\Inventory\Filament\Resources\Containers\RelationManagers\ItemsRelationManager;
use App\Domains\Inventory\Filament\Resources\Containers\Schemas\ContainerForm;
use App\Domains\Inventory\Filament\Resources\Containers\Tables\ContainersTable;
use App\Domains\Inventory\Models\Container;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContainerResource extends Resource
{
    protected static ?string $model = Container::class;

    protected static ?string $slug = 'containers';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ContainerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContainersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Domains\Inventory\Filament\Resources\Containers\Pages\ListContainers::route('/'),
            'create' => \App\Domains\Inventory\Filament\Resources\Containers\Pages\CreateContainer::route('/create'),
            'edit' => \App\Domains\Inventory\Filament\Resources\Containers\Pages\EditContainer::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
}
