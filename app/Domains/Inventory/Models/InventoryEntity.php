<?php

namespace App\Domains\Inventory\Models;

use App\Traits\HasPublicId;
use Illuminate\Database\Eloquent\Attributes\RouteKey;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $public_id
 */
#[RouteKey('public_id')]
abstract class InventoryEntity extends Model
{
    use HasPublicId;

    protected function nameWithId(): Attribute
    {
        return Attribute::make(
            get: fn () => "$this->public_id | $this->name"
        );
    }

    //    abstract protected static function publicIdIdentifier(): string;
}
