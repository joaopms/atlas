<?php

namespace App\Domains\Inventory\Filament\Resources\Items;

use App\Domains\Inventory\Filament\Resources\Items\Pages\CreateItem;
use App\Domains\Inventory\Filament\Resources\Items\Pages\EditItem;
use App\Domains\Inventory\Filament\Resources\Items\Pages\ListItems;
use App\Domains\Inventory\Filament\Resources\Items\Schemas\ItemForm;
use App\Domains\Inventory\Filament\Resources\Items\Tables\ItemsTable;
use App\Domains\Inventory\Models\Item;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $recordTitleAttribute = 'name_with_id';

    protected static string|\UnitEnum|null $navigationGroup = 'Inventory';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedPaperClip;

    public static function form(Schema $schema): Schema
    {
        return ItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ItemsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListItems::route('/'),
            'create' => CreateItem::route('/create'),
            'edit' => EditItem::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['public_id', 'name'];
    }
}
