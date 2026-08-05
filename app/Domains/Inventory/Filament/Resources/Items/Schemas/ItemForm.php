<?php

namespace App\Domains\Inventory\Filament\Resources\Items\Schemas;

use App\Domains\Inventory\Models\Container;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class ItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Label
                Section::make()
                    ->schema([
                        View::make('Inventory::filament.schemas.components.label-item')
                            ->viewData(['model' => $schema->model]),
                    ]),

                TextInput::make('public_id')
                    ->disabled()
                    ->readOnly(),

                TextInput::make('name')
                    ->required(),

                Select::make('container_id')
                    ->relationship('container', 'name')
                    ->searchable()
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn /** @var $record Container */ ($record) => $record->name_with_id),

                Textarea::make('notes')
                    ->default(null),
            ]);
    }
}
