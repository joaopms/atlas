<?php

namespace App\Domains\Inventory\database\factories;

use App\Domains\Inventory\Models\Container;
use App\Domains\Inventory\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
    {
        return [
            'public_id' => $this->faker->word(),
            'container_id' => Container::factory(),
            'name' => $this->faker->name(),
            'notes' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
