<?php

namespace Tests\Feature;

use App\Models\Certification;
use App\Models\Contract;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class ContractTest extends TestCase
{
    use WithFaker;

    public function testIndexReturnsDataInValidFormat()
    {
        $this->json('get', 'api/contracts')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    '*' => [
                        'id',
                        'begin_date',
                        'end_date',
                        'type',
                        'certification_id',
                        'created_at',
                        'updated_at',
                    ]
                ]
            );
    }

    public function testContractIsCreatedSuccessfully()
    {
        $certification = Certification::factory()->create();
        $payload = [
            'begin_date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'end_date' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'type' => $this->faker->randomElement(['Purchase', 'Lease', 'Service', 'Other']),
            'certification_id' => $certification->id,
        ];

        $this->json('post', 'api/contracts', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'id',
                    'begin_date',
                    'end_date',
                    'type',
                    'certification_id',
                    'created_at',
                    'updated_at',
                ]
            );

        $this->assertDatabaseHas('contracts', $payload);
    }

    public function testContractIsUpdatedSuccessfully()
    {
        $contract = Contract::factory()->create();

        $payload = [
            'begin_date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'end_date' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'type' => $this->faker->randomElement(['Purchase', 'Lease', 'Service', 'Other']),
            'certification_id' => $contract->certification_id,
        ];

        $this->json('put', "api/contracts/{$contract->id}", $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'id',
                    'begin_date',
                    'end_date',
                    'type',
                    'certification_id',
                    'created_at',
                    'updated_at',
                ]
            );

        $this->assertDatabaseHas('contracts', array_merge($payload, ['id' => $contract->id]));
    }

    public function testContractIsDestroyed()
    {
        $contract = Contract::factory()->create();

        $this->json('delete', "api/contracts/{$contract->id}")
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('contracts', ['id' => $contract->id]);
    }
}
