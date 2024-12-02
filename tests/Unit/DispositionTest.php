<?php

namespace Tests\Feature;

use App\Models\Disposition;
use App\Models\InventoryUnit;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class DispositionTest extends TestCase
{
    use WithFaker;

    public function testIndexReturnsDataInValidFormat()
    {
        $this->json('get', 'api/dispositions')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    '*' => [
                        'id',
                        'date',
                        'method',
                        'entity',
                        'amount',
                        'inventory_unit_id',
                        'created_at',
                        'updated_at',
                    ]
                ]
            );
    }

    public function testDispositionIsCreatedSuccessfully()
    {
        $inventoryUnit = InventoryUnit::factory()->create();

        $payload = [
            'date' => $this->faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'method' => $this->faker->randomElement(['Sale', 'Donation', 'Disposal', 'Return']),
            'entity' => $this->faker->company(),
            'amount' => $this->faker->randomFloat(2, 50, 10000),
            'inventory_unit_id' => $inventoryUnit->id,
        ];

        $this->json('post', 'api/dispositions', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'id',
                    'date',
                    'method',
                    'entity',
                    'amount',
                    'inventory_unit_id',
                    'created_at',
                    'updated_at',
                ]
            );

        $this->assertDatabaseHas('dispositions', $payload);
    }

    public function testDispositionIsUpdatedSuccessfully()
    {
        $disposition = Disposition::factory()->create();

        $payload = [
            'date' => $this->faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'method' => $this->faker->randomElement(['Sale', 'Donation', 'Disposal', 'Return']),
            'entity' => $this->faker->company(),
            'amount' => $this->faker->randomFloat(2, 50, 10000),
            'inventory_unit_id' => $disposition->inventory_unit_id,
        ];

        $this->json('put', "api/dispositions/{$disposition->id}", $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'id',
                    'date',
                    'method',
                    'entity',
                    'amount',
                    'inventory_unit_id',
                    'created_at',
                    'updated_at',
                ]
            );

        $this->assertDatabaseHas('dispositions', array_merge($payload, ['id' => $disposition->id]));
    }

    public function testDispositionIsDestroyed()
    {
        $disposition = Disposition::factory()->create();

        $this->json('delete', "api/dispositions/{$disposition->id}")
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('dispositions', ['id' => $disposition->id]);
    }
}
