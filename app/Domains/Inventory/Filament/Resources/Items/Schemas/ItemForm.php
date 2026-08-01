<?php

namespace App\Domains\Inventory\Filament\Resources\Items\Schemas;

use App\Domains\Inventory\Models\Container;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),

                Select::make('container_id')
                    ->relationship('container', 'name')
                    ->searchable()
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn /** @var $record Container */ ($record) => $record->name_with_id),

                TextInput::make('notes')
                    ->default(null),
            ]);
    }
}
