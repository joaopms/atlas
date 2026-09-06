<?php

namespace App\Domains\Inventory\Filament\Resources\Items\Schemas;

use App\Domains\Inventory\Filament\Resources\Containers\Schemas\ContainerForm;
use App\Domains\Inventory\Models\Container;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
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
                    ->key('label')
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

                Fieldset::make()
                    ->schema([
                        Select::make('container_id')
                            ->relationship('container', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm(ContainerForm::configure(...))
                            ->editOptionForm(ContainerForm::configure(...))
                            ->getOptionLabelFromRecordUsing(fn /** @var $record Container */ ($record) => $record->name_with_id),
                    ])
                    ->columnSpanFull(),

                TextInput::make('name')
                    ->autofocus()
                    ->required(),

                TextInput::make('quantity')
                    ->required()
                    ->integer()
                    ->default(1)
                    ->minValue(1),

                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
