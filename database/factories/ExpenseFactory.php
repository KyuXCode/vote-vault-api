<?php

namespace Database\Factories;

use App\Models\Contract;
use App\Models\County;
use App\Models\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'amount' => $this->faker->randomFloat(2, 10, 10000),
            'fund' => $this->faker->word(),
            'owner' => $this->faker->name(),
            'contract_id' => Contract::query()->inRandomOrder()->value('id') ?? Contract::factory(),
            'county_id' => County::query()->inRandomOrder()->value('id') ?? County::factory(),
        ];
    }
}
