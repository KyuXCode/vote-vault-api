<?php

namespace Database\Factories;

use App\Models\Disposition;
use App\Models\InventoryUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

class DispositionFactory extends Factory
{
    protected $model = Disposition::class;

    public function definition(): array
    {
        return [
            'date' => $this->faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'method' => $this->faker->randomElement(['Sale', 'Donation', 'Disposal', 'Return']),
            'entity' => $this->faker->company(),
            'amount' => $this->faker->randomFloat(2, 50, 10000),
            'inventory_unit_id' => InventoryUnit::query()->inRandomOrder()->value('id') ?? InventoryUnit::factory(),
        ];
    }
}
