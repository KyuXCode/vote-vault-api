<?php

namespace Tests\Feature;

use App\Models\County;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class CountyTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function testIndexReturnsDataInValidFormat()
    {
        $this->json('get', 'api/counties')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    '*' => [
                        'id',
                        'name',
                        'created_at',
                        'updated_at',
                    ]
                ]
            );
    }

    public function testCountyIsCreatedSuccessfully()
    {
        $payload = [
            'name' => $this->faker->unique()->city(),
        ];

        $this->json('post', 'api/counties', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'id',
                    'name',
                    'created_at',
                    'updated_at',
                ]
            );

        $this->assertDatabaseHas('counties', $payload);
    }

    public function testCountyIsUpdatedSuccessfully()
    {
        $county = County::factory()->create();

        $payload = [
            'name' => $this->faker->unique()->city(),
        ];

        $this->json('put', "api/counties/{$county->id}", $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'id',
                    'name',
                    'created_at',
                    'updated_at',
                ]
            );

        $this->assertDatabaseHas('counties', array_merge($payload, ['id' => $county->id]));
    }

    public function testCountyIsDestroyed()
    {
        $county = County::factory()->create();

        $this->json('delete', "api/counties/{$county->id}")
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('counties', ['id' => $county->id]);
    }
}
