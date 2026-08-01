<?php

namespace App\Domains\Inventory\Models;

use App\Domains\Inventory\database\factories\ContainerFactory;
use App\Domains\Inventory\Observers\ContainerObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[UseFactory(ContainerFactory::class)]
#[Fillable('name')]
#[ObservedBy(ContainerObserver::class)]
class Container extends Model
{
    use SoftDeletes;
}
