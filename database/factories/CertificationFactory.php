<?php

namespace Database\Factories;

use App\Helpers\ActionType;
use App\Helpers\CertificationType;
use App\Helpers\SystemBase;
use App\Helpers\SystemType;
use App\Models\Certification;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            Certification::type => $this->faker->randomElement(CertificationType::cases()),
            Certification::action => $this->faker->randomElement(ActionType::cases()),
            Certification::system_type => $this->faker->randomElement(SystemType::cases()),
            Certification::system_base => $this->faker->randomElement(SystemBase::cases()),
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
            'federal_certification_number' => '12345', // 70% chance to generate
            'federal_certification_date' => $this->faker->boolean(70) ? $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d') : null,
            Certification::type => CertificationType::Certification,
            Certification::action => ActionType::Pending,
            Certification::system_type => SystemType::EPB,
            Certification::system_base => SystemBase::Computer,
            'vendor_id' => Vendor::query()->inRandomOrder()->value('id') ?? Vendor::factory(),
        ],
            $attributes)
        );
    }
}
