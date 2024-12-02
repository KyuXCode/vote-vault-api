<?php

namespace Tests\Feature;

use App\Models\StorageLocation;
use App\Models\InventoryUnit;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class StorageLocationTest extends TestCase
{
    use WithFaker;

    public function testIndexReturnsDataInValidFormat()
    {
        $this->json('get', 'api/storage_locations')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    '*' => [
                        'id',
                        'name',
                        'address',
                        'city',
                        'state',
                        'zip',
                        'inventory_unit_id',
                        'created_at',
                        'updated_at',
                    ]
                ]
            );
    }

    public function testStorageLocationIsCreatedSuccessfully()
    {
        $inventoryUnit = InventoryUnit::factory()->create();

        $payload = [
            'name' => $this->faker->unique()->company(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->stateAbbr(),
            'zip' => $this->faker->postcode(),
            'inventory_unit_id' => $inventoryUnit->id,
        ];

        $this->json('post', 'api/storage_locations', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'id',
                    'name',
                    'address',
                    'city',
                    'state',
                    'zip',
                    'inventory_unit_id',
                    'created_at',
                    'updated_at',
                ]
            );

        $this->assertDatabaseHas('storage_locations', $payload);
    }

    public function testStorageLocationIsUpdatedSuccessfully()
    {
        $storageLocation = StorageLocation::factory()->create();

        $payload = [
            'name' => $this->faker->unique()->company(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->stateAbbr(),
            'zip' => $this->faker->postcode(),
            'inventory_unit_id' => $storageLocation->inventory_unit_id,
        ];

        $this->json('put', "api/storage_locations/{$storageLocation->id}", $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'id',
                    'name',
                    'address',
                    'city',
                    'state',
                    'zip',
                    'inventory_unit_id',
                    'created_at',
                    'updated_at',
                ]
            );

        $this->assertDatabaseHas('storage_locations', array_merge($payload, ['id' => $storageLocation->id]));
    }

    public function testStorageLocationIsDestroyed()
    {
        $storageLocation = StorageLocation::factory()->create();

        $this->json('delete', "api/storage_locations/{$storageLocation->id}")
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('storage_locations', ['id' => $storageLocation->id]);
    }
}
