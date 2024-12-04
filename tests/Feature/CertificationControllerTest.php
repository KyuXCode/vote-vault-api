<?php

namespace Tests\Feature;

use App\Models\Certification;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CertificationControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testIndexReturnsCertifications()
    {
        $certifications = Certification::factory()->count(3)->create();

        $response = $this->json('get', '/api/certifications');

        $response->assertStatus(200);
        foreach ($certifications as $certification) {
            $response->assertJsonFragment(['id' => $certification->id]);
        }
    }

    public function testStoreCreatesCertification()
    {
        $vendor = Vendor::factory()->create();
        $payload = [
            'model_number' => $this->faker->regexify('[A-Z]{2}[0-9]{3}'),
            'description' => $this->faker->sentence(),
            'application_date' => $this->faker->date(),
            'certification_date' => $this->faker->date(),
            'expiration_date' => $this->faker->date(),
            'type' => 'Certification',
            'action' => 'Approved',
            'system_type' => 'VS',
            'system_base' => 'DRE',
            'vendor_id' => $vendor->id,
        ];

        $response = $this->json('post', '/api/certifications', $payload);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonFragment(['model_number' => $payload['model_number']]);
        $this->assertDatabaseHas('certifications', $payload);
    }

    public function testDestroyDeletesCertification()
    {
        $certification = Certification::factory()->create();

        $response = $this->json('delete', "/api/certifications/{$certification->id}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['message' => 'Certification deleted successfully']);
        $this->assertDatabaseMissing('certifications', ['id' => $certification->id]);
    }
}


