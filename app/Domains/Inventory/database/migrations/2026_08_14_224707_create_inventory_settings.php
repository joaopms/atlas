<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('inventory.label_printer_cups_server', '');
        $this->migrator->add('inventory.label_printer_cups_port', 631);
        $this->migrator->add('inventory.label_printer_name', '');
        $this->migrator->add('inventory.label_printer_media', '');
        $this->migrator->add('inventory.label_print_width', 62);
        $this->migrator->add('inventory.label_print_height', 29);
    }
};
