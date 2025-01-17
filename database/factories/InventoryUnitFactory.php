<?php

namespace Database\Factories;

use App\Helpers\ConditionType;
use App\Helpers\UsageType;
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
            'condition' => $this->faker->randomElement(ConditionType::cases()),
            'usage' => $this->faker->randomElement(UsageType::cases()),
            'expense_id' => Expense::query()->inRandomOrder()->value('id') ?? Expense::factory(),
            'component_id' => Component::query()->inRandomOrder()->value('id') ?? Component::factory(),
        ];
    }

    public function withAllFields(array $attributes = []): self
    {
        return $this->state(fn() => array_merge([
                'serial_number' => 'A1B2C3D4E5',
                'acquisition_date' => '2025-01-01',
                InventoryUnit::condition => ConditionType::Good,
                InventoryUnit::usage => UsageType::Active,
                'expense_id' => Expense::query()->inRandomOrder()->value('id') ?? Expense::factory(),
                'component_id' => Component::query()->inRandomOrder()->value('id') ?? Component::factory(),
            ]
            , $attributes));
    }
}
