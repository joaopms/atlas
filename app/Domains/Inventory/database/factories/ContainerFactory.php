<?php

namespace App\Domains\Inventory\database\factories;

use App\Domains\Inventory\Models\Container;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

#[UseModel(Container::class)]
class ContainerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'public_id' => $this->faker->word(),
            'name' => $this->faker->name(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
