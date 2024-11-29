<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company,
            'address' => $this->faker->optional()->streetAddress,
            'city' => $this->faker->optional()->city,
            'state' => $this->faker->optional()->stateAbbr, // Abbreviation like 'CA'
            'zip' => $this->faker->optional()->postcode,
            'contact_name' => $this->faker->optional()->name,
            'contact_email' => $this->faker->optional()->safeEmail,
            'contact_phone' => $this->faker->optional()->phoneNumber,
            'product' => $this->faker->randomElement(['EPB', 'VS', 'EPB & VS', 'Service', 'Other']),
        ];
    }
}
