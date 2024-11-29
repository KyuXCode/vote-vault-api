<?php

namespace Database\Factories;

use App\Models\StorageLocation;
use App\Models\InventoryUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

class StorageLocationFactory extends Factory
{
    protected $model = StorageLocation::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->stateAbbr(),
            'zip' => $this->faker->postcode(),
            'inventory_unit_id' => InventoryUnit::query()->inRandomOrder()->value('id') ?? InventoryUnit::factory(),
        ];
    }
}
