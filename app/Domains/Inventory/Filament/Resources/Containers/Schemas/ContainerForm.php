<?php

namespace App\Domains\Inventory\Filament\Resources\Containers\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ContainerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Label')
                    ->schema([
                        View::make('Inventory::filament.schemas.components.label-container')
                            ->viewData(['model' => $schema->model]),
                    ])
                    ->afterHeader([
                        Action::make('view_label')
                            ->icon(Heroicon::OutlinedDocument)
                            ->url(route('filament.main.inventory.containers.label', $schema->model->public_id))
                            ->openUrlInNewTab()
                            ->iconButton(),
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
