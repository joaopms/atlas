<?php

namespace App\Domains\Inventory\Settings;

use Spatie\LaravelSettings\Settings;

class InventorySettings extends Settings
{
    public string $label_printer = '';

    public float $label_print_width = 62;

    public float $label_print_height = 29;

    public static function group(): string
    {
        return 'Inventory';
    }
}
