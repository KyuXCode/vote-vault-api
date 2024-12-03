<?php

namespace Tests\Feature;

use App\Models\Component;
use App\Models\Certification;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class ComponentTest extends TestCase
{
    use WithFaker;

    public function testIndexReturnsDataInValidFormat()
    {
        $this->json('get', 'api/components')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'type',
                        'certification_id',
                        'created_at',
                        'updated_at',
                    ]
                ]
            );
    }

    public function testComponentIsCreatedSuccessfully()
    {
        $certification = Certification::factory()->create();

        $payload = [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'type' => $this->faker->randomElement(['DRE', 'OpScan', 'BMD', 'VVPAT', 'COTS', 'Other', 'Hardware', 'Software', 'Peripheral']),
            'certification_id' => $certification->id,
        ];

        $this->json('post', 'api/components', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'id',
                    'name',
                    'description',
                    'type',
                    'certification_id',
                    'created_at',
                    'updated_at',
                ]
            );

        $this->assertDatabaseHas('components', $payload);
    }

    public function testComponentIsUpdatedSuccessfully()
    {
        $component = Component::factory()->create();

        $payload = [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'type' => $this->faker->randomElement(['DRE', 'OpScan', 'BMD', 'VVPAT', 'COTS', 'Other', 'Hardware', 'Software', 'Peripheral']),
        ];

        $this->json('put', "api/components/{$component->id}", $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'id',
                    'name',
                    'description',
                    'type',
                    'certification_id',
                    'created_at',
                    'updated_at',
                ]
            );

        $this->assertDatabaseHas('components', array_merge($payload, ['id' => $component->id]));
    }

    public function testComponentIsDestroyed()
    {
        $component = Component::factory()->create();

        $this->json('delete', "api/components/{$component->id}")
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('components', ['id' => $component->id]);
    }
}
