<?php

namespace App\Domains\Inventory\Models;

use App\Domains\Inventory\database\factories\ItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[UseFactory(ItemFactory::class)]
#[Fillable('name')]
class Item extends InventoryEntity
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'inventory_items';

    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class, 'container_id');
    }

    protected static function publicIdIdentifier(): string
    {
        return 'I';
    }
}
