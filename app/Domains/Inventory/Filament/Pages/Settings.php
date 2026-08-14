<?php

namespace App\Domains\Inventory\Filament\Pages;

use App\Domains\Inventory\Settings\InventorySettings;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class Settings extends SettingsPage
{
    protected static string $settings = InventorySettings::class;

    protected static ?string $slug = 'inventory/settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Inventory';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Labels')
                    ->schema([
                        TextInput::make('label_printer')
                            ->label('Printer')
                            ->required(),

                        TextInput::make('label_print_width')
                            ->label('Label Width')
                            ->suffix('mm')
                            ->numeric()
                            ->required(),

                        TextInput::make('label_print_height')
                            ->label('Label Height')
                            ->suffix('mm')
                            ->numeric()
                            ->required(),
                    ]),
            ]);
    }
}
