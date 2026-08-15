<?php

namespace App\Domains\Inventory\Settings;

use Spatie\LaravelSettings\Settings;

class InventorySettings extends Settings
{
    public string $label_printer_cups_server;

    public int $label_printer_cups_port;

    public string $label_printer_name;

    public string $label_printer_media;

    public float $label_print_width;

    public float $label_print_height;

    public static function group(): string
    {
        return 'Inventory';
    }
}
