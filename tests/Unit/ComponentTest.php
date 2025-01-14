<?php

namespace Tests\Feature;

use App\Models\Component;
use App\Models\Certification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class ComponentTest extends TestCase
{
    use WithFaker, RefreshDatabase;

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

    public function testValidationErrorsArePresent()
    {
        $Certification = Certification::factory()->create();

        $this->json('post', 'api/components', $Certification->toArray())
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'type', 'certification_id']);

        self::assertTrue(Component::where('name', $Certification->name)->doesntExist());
    }

    public function testComponentIsCreatedSuccessfully()
    {
        $Component = Component::factory()->withAllFields()->make();

        $this->json('post', 'api/components', $Component->toArray())
            ->assertCreated()
            ->assertJson($Component->toArray());

        self::assertTrue(
            Component::where('name', $Component->name)->exists()
        );
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
