<?php

namespace Database\Factories;

use App\Models\Certification;
use App\Models\Contract;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractFactory extends Factory
{
    protected $model = Contract::class;

    public function definition(): array
    {
        $beginDate = $this->faker->dateTimeBetween('-1 year', 'now');
        $endDate = $this->faker->dateTimeBetween($beginDate, '+1 year');

        return [
            'begin_date' => $beginDate,
            'end_date' => $endDate,
            'type' => $this->faker->randomElement(['Purchase', 'Lease', 'Service', 'Other']),
            'certification_id' => Certification::query()->inRandomOrder()->value('id') ?? Certification::factory(),
        ];
    }
}
