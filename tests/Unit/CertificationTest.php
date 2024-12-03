<?php

namespace Tests\Feature;

use App\Models\Certification;
use App\Models\Vendor;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class CertificationTest extends TestCase
{
    use WithFaker;

    public function testIndexReturnsDataInValidFormat()
    {
        $this->json('get', 'api/certifications')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    '*' => [
                        'id',
                        'model_number',
                        'description',
                        'application_date',
                        'certification_date',
                        'expiration_date',
                        'federal_certification_number',
                        'federal_certification_date',
                        'type',
                        'action',
                        'system_type',
                        'system_base',
                        'vendor_id',
                        'created_at',
                        'updated_at',
                    ]
                ]
            );
    }

    public function testCertificationIsCreatedSuccessfully()
    {
        $vendor = Vendor::factory()->create();

        $payload = [
            'model_number' => $this->faker->regexify('[A-Z]{2}[0-9]{3}'),
            'description' => $this->faker->sentence(),
            'application_date' => $this->faker->date('Y-m-d'),
            'certification_date' => $this->faker->date('Y-m-d'),
            'expiration_date' => $this->faker->date('Y-m-d'),
            'type' => $this->faker->randomElement(['Certification', 'Reevaluation', 'Renewal', 'Recertification', 'Other']),
            'action' => $this->faker->randomElement(['Approved', 'Pending', 'Denied', 'Other']),
            'system_type' => $this->faker->randomElement(['VS', 'EPB']),
            'system_base' => $this->faker->randomElement(['DRE', 'OpScan', 'PC/Laptop', 'Tablet', 'Custom Hardware', 'Other']),
            'vendor_id' => $vendor->id,
        ];

        $this->json('post', 'api/certifications', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'id',
                    'model_number',
                    'description',
                    'application_date',
                    'certification_date',
                    'expiration_date',
                    'type',
                    'action',
                    'system_type',
                    'system_base',
                    'vendor_id',
                    'created_at',
                    'updated_at',
                ]
            );

        $this->assertDatabaseHas('certifications', $payload);
    }

    public function testCertificationIsUpdatedSuccessfully()
    {
        $certification = Certification::factory()->create();

        $payload = [
            'model_number' => $this->faker->regexify('[A-Z]{2}[0-9]{3}'),
            'description' => $this->faker->sentence(),
            'application_date' => $this->faker->date('Y-m-d'),
            'certification_date' => $this->faker->date('Y-m-d'),
            'expiration_date' => $this->faker->date('Y-m-d'),
            'type' => $this->faker->randomElement(['Certification', 'Reevaluation', 'Renewal', 'Recertification', 'Other']),
            'action' => $this->faker->randomElement(['Approved', 'Pending', 'Denied', 'Other']),
            'system_type' => $this->faker->randomElement(['VS', 'EPB']),
            'system_base' => $this->faker->randomElement(['DRE', 'OpScan', 'PC/Laptop', 'Tablet', 'Custom Hardware', 'Other']),
        ];

        $this->json('put', "api/certifications/{$certification->id}", $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'id',
                    'model_number',
                    'description',
                    'application_date',
                    'certification_date',
                    'expiration_date',
                    'type',
                    'action',
                    'system_type',
                    'system_base',
                    'vendor_id',
                    'created_at',
                    'updated_at',
                ]
            );

        $this->assertDatabaseHas('certifications', $payload);
    }

    public function testCertificationIsDestroyed()
    {
        $certification = Certification::factory()->create();

        $this->json('delete', "api/certifications/$certification->id")
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('certifications', ['id' => $certification->id]);
    }
}
