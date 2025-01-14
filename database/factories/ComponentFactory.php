<?php

namespace Database\Factories;

use App\Helpers\ComponentType;
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
            Component::type => $this->faker->randomElement(ComponentType::cases()),
            'certification_id' => Certification::query()->inRandomOrder()->value('id') ?? Certification::factory(),
        ];
    }

    public function withAllFields(array $attributes = []): self
    {
        return $this->state(fn() => array_merge([
            'name' => 'name',
            Component::type => ComponentType::BMD,
            'certification_id' => Certification::query()->inRandomOrder()->value('id') ?? Certification::factory(),
        ],
            $attributes)
        );
    }
}
