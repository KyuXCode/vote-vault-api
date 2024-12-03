<?php

namespace Database\Factories;

use App\Models\Component;
use App\Models\Expense;
use App\Models\InventoryUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryUnitFactory extends Factory
{
    protected $model = InventoryUnit::class;

    public function definition(): array
    {
        return [
            'serial_number' => $this->faker->unique()->regexify('[A-Z0-9]{10}'), // Random alphanumeric string
            'acquisition_date' => $this->faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'condition' => $this->faker->randomElement(['New', 'Excellent', 'Good', 'Worn', 'Damaged', 'Unusable']),
            'usage' => $this->faker->randomElement(['Active', 'Spare', 'Display', 'Other', 'Inactive']),
            'expense_id' => Expense::query()->inRandomOrder()->value('id') ?? Expense::factory(),
            'component_id' => Component::query()->inRandomOrder()->value('id') ?? Component::factory(),
        ];
    }
}
