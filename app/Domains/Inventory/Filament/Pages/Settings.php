<?php

namespace App\Domains\Inventory\Filament\Pages;

use App\Domains\Inventory\Settings\InventorySettings;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Rawilk\Printing\Api\Cups\Types\Primitive\Keyword;
use Rawilk\Printing\Contracts\Printer;
use Rawilk\Printing\Facades\Printing;
use UnitEnum;

class Settings extends SettingsPage
{
    protected static string $settings = InventorySettings::class;

    protected static ?string $slug = 'inventory/settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Inventory';

    public function form(Schema $schema): Schema
    {
        $settings = app(InventorySettings::class);

        // TODO Extract this
        config()->set('printing.driver', 'cups');
        config()->set('printing.drivers.cups.ip', $settings->label_printer_cups_server);
        config()->set('printing.drivers.cups.port', $settings->label_printer_cups_port);

        $printer = Printing::printers()->filter(fn (Printer $printer) => $printer->name() === $settings->label_printer_name)->first();

        $media = $printer
            ? collect($printer->capabilities()['media-supported'])
                ->map(fn (Keyword $keyword) => $keyword->value)
                ->sort(SORT_NATURAL)
                ->values()
            : collect(['']);

        return $schema
            ->components([
                Section::make('Labels')
                    ->schema([
                        TextInput::make('label_printer_cups_server')
                            ->label('CUPS server')
                            ->required(),

                        TextInput::make('label_printer_cups_port')
                            ->label('CUPS port')
                            ->required(),

                        TextInput::make('label_printer_name')
                            ->label('Printer name')
                            ->required(),

                        Select::make('label_printer_media')
                            ->options($media)
                            ->required(),

                        TextInput::make('label_print_width')
                            ->label('Label width')
                            ->suffix('mm')
                            ->numeric()
                            ->required(),

                        TextInput::make('label_print_height')
                            ->label('Label height')
                            ->suffix('mm')
                            ->numeric()
                            ->required(),
                    ]),
            ]);
    }
}
