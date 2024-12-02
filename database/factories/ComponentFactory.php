<?php

namespace Database\Factories;

use App\Models\Component;
use App\Models\Certification;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComponentFactory extends Factory
{
    protected $model = Component::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'type' => $this->faker->randomElement(['DRE', 'OpScan', 'BMD', 'VVPAT', 'COTS', 'Other', 'Hardware', 'Software', 'Peripheral']),
            'certification_id' => Certification::query()->inRandomOrder()->value('id') ?? Certification::factory(),
        ];
    }
}
