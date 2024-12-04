<?php

namespace Database\Factories;

use App\Models\County;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountyFactory extends Factory
{
    protected $model = County::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->city(),
        ];
    }
}
