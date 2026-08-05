<?php

namespace App\Domains\Inventory\Filament\Resources\Containers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class ContainerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Label
                Section::make()
                    ->schema([
                        View::make('Inventory::filament.schemas.components.label-container')
                            ->viewData(['model' => $schema->model]),
                    ]),

                TextInput::make('public_id')
                    ->disabled()
                    ->readOnly(),

                TextInput::make('name')
                    ->required(),

                Select::make('location_id')
                    ->relationship('location', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required(),
                    ]),
            ]);
    }
}
