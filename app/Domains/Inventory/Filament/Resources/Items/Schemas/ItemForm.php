<?php

namespace App\Domains\Inventory\Filament\Resources\Items\Schemas;

use App\Domains\Inventory\Models\Container;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Label')
                    ->visible(fn (string $operation) => $operation !== 'create')
                    ->schema([
                        View::make('Inventory::filament.schemas.components.label-item')
                            ->viewData(['model' => $schema->model]),
                    ])
                    ->afterHeader([
                        Action::make('generate_label')
                            ->icon(Heroicon::OutlinedTag)
                            ->url(fn () => route('filament.main.inventory.items.label', $schema->model->public_id))
                            ->openUrlInNewTab()
                            ->iconButton(),
                    ]),

                TextInput::make('public_id')
                    ->label('ID')
                    ->visible(fn (string $operation) => $operation !== 'create')
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
