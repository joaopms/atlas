<?php

namespace App\Domains\Inventory\Models;

use App\Domains\Inventory\database\factories\LocationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[UseFactory(LocationFactory::class)]
#[Fillable('name')]
class Location extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'inventory_locations';

    public function containers(): HasMany
    {
        return $this->hasMany(Container::class, 'location_id', 'id');
    }
}
