<?php

namespace Database\Factories;

use App\Helpers\ContractType;
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
            Contract::type => $this->faker->randomElement(ContractType::cases()),
            'certification_id' => Certification::query()->inRandomOrder()->value('id') ?? Certification::factory(),
        ];
    }

    public function withAllFields(array $attributes = []): self
    {
        $beginDate = '2025-01-16';
        $endDate = '2026-01-16';

        return $this->state(fn() => array_merge([
            'begin_date' => $beginDate,
            'end_date' => $endDate,
            Contract::type => ContractType::Purchase,
            'certification_id' => Certification::query()->inRandomOrder()->value('id') ?? Certification::factory(),
        ],
            $attributes)
        );
    }
}
