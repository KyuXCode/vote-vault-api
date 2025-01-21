<?php

namespace Tests\Unit;

use App\Models\Contract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class ContractTest extends TestCase
{
    use WithFaker, RefreshDatabase;

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

    public function testValidationErrorsArePresent()
    {
        $Contract = Contract::factory()->withAllFields()->make();

        $Contract->end_date = '2025-01-14';

        $this->json('post', 'api/contracts', $Contract->toArray())
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['end_date']);

        $this->assertDatabaseMissing('contracts', $Contract->toArray());
    }

    public function testContractIsCreatedSuccessfully()
    {
        $Contract = Contract::factory()->withAllFields()->make();

        $this->json('post', 'api/contracts', $Contract->toArray())
            ->assertCreated()
            ->assertJson($Contract->toArray());

        $this->assertDatabaseHas('contracts', $Contract->toArray());
    }

    public function testContractIsUpdatedSuccessfully()
    {
        $Contract = Contract::factory()->withAllFields()->create();
        $Contract->begin_date = '2023-01-16';

        $this->json('put', "api/contracts/{$Contract->id}", $Contract->toArray())
            ->assertStatus(Response::HTTP_OK)
            ->assertJson($Contract->toArray());

        self::assertTrue(
            $Contract::where('2023-01-16', $Contract->begin_date)->exists()
        );
    }

    public function testContractIsDestroyed()
    {
        $contract = Contract::factory()->create();

        $this->json('delete', "api/contracts/{$contract->id}")
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('contracts', ['id' => $contract->id]);
    }
}
