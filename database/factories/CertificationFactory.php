<?php

namespace Database\Factories;

use App\Models\Certification;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;
use PhpParser\Node\Expr\Array_;

class CertificationFactory extends Factory
{
    protected $model = Certification::class;

    public function definition(): array
    {
        return [
            'model_number' => $this->faker->regexify('[A-Z]{2}[0-9]{3}'), // Example: AB123
            'description' => $this->faker->sentence(),
            'application_date' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'certification_date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'expiration_date' => $this->faker->dateTimeBetween('now', '+2 years')->format('Y-m-d'),
            'federal_certification_number' => $this->faker->boolean(70) ? $this->faker->regexify('[A-Z]{3}[0-9]{5}') : null, // 70% chance to generate
            'federal_certification_date' => $this->faker->boolean(70) ? $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d') : null,
            'type' => $this->faker->randomElement(['Certification', 'Reevaluation', 'Renewal', 'Recertification', 'Other']),
            'action' => $this->faker->randomElement(['Approved', 'Pending', 'Denied', 'Other']),
            'system_type' => $this->faker->randomElement(['VS', 'EPB']),
            'system_base' => $this->faker->randomElement(['DRE', 'OpScan', 'PC/Laptop', 'Tablet', 'Custom Hardware', 'Other']),
            'vendor_id' => Vendor::query()->inRandomOrder()->value('id') ?? Vendor::factory(),
        ];
    }

    public function withAllFields(array $attributes = []): self
    {
        return $this->state(fn() => array_merge([
            'model_number' => 'ABC_Test',
            'description' => 'This is a example certification.',
            'application_date' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'certification_date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'expiration_date' => $this->faker->dateTimeBetween('now', '+2 years')->format('Y-m-d'),
            'federal_certification_number' => $this->faker->boolean(70) ? $this->faker->regexify('[A-Z]{3}[0-9]{5}') : null, // 70% chance to generate
            'federal_certification_date' => $this->faker->boolean(70) ? $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d') : null,
            'type' => $this->faker->randomElement(['Certification', 'Reevaluation', 'Renewal', 'Recertification', 'Other']),
            'action' => $this->faker->randomElement(['Approved', 'Pending', 'Denied', 'Other']),
            'system_type' => $this->faker->randomElement(['VS', 'EPB']),
            'system_base' => $this->faker->randomElement(['DRE', 'OpScan', 'PC/Laptop', 'Tablet', 'Custom Hardware', 'Other']),
            'vendor_id' => Vendor::query()->inRandomOrder()->value('id') ?? Vendor::factory(),
        ],
            $attributes)
        );
    }
}
