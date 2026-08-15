<?php

namespace App\Domains\Inventory\Models;

use App\Domains\Inventory\database\factories\ContainerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[UseFactory(ContainerFactory::class)]
#[Fillable('name')]
class Container extends InventoryEntity
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'inventory_containers';

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    protected static function publicIdIdentifier(): string
    {
        return 'C';
    }
}
