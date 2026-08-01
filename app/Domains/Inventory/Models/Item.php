<?php

namespace App\Domains\Inventory\Models;

use App\Domains\Inventory\database\factories\ItemFactory;
use App\Domains\Inventory\Observers\ItemObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(ItemObserver::class)]
#[UseFactory(ItemFactory::class)]
#[Fillable('name')]
class Item extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'inventory_items';

    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class, 'container_id');
    }

    protected function nameWithId(): Attribute
    {
        return Attribute::make(
            get: fn () => "$this->public_id | $this->name"
        );
    }
}
