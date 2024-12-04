<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\County;
use App\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class ExpenseTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function testIndexReturnsDataInValidFormat()
    {
        $this->json('get', 'api/expenses')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    '*' => [
                        'id',
                        'name',
                        'amount',
                        'fund',
                        'owner',
                        'contract_id',
                        'county_id',
                        'created_at',
                        'updated_at',
                    ]
                ]
            );
    }

    public function testExpenseIsCreatedSuccessfully()
    {
        $contract = Contract::factory()->create();
        $county = County::factory()->create();

        $payload = [
            'name' => $this->faker->words(3, true),
            'amount' => $this->faker->randomFloat(2, 10, 10000),
            'fund' => $this->faker->word(),
            'owner' => $this->faker->name(),
            'contract_id' => $contract->id,
            'county_id' => $county->id,
        ];

        $this->json('post', 'api/expenses', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'id',
                    'name',
                    'amount',
                    'fund',
                    'owner',
                    'contract_id',
                    'county_id',
                    'created_at',
                    'updated_at',
                ]
            );

        $this->assertDatabaseHas('expenses', $payload);
    }

    public function testExpenseIsUpdatedSuccessfully()
    {
        $expense = Expense::factory()->create();

        $payload = [
            'name' => $this->faker->words(3, true),
            'amount' => $this->faker->randomFloat(2, 10, 10000),
            'fund' => $this->faker->word(),
            'owner' => $this->faker->name(),
            'contract_id' => $expense->contract_id,
            'county_id' => $expense->county_id,
        ];

        $this->json('put', "api/expenses/{$expense->id}", $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'id',
                    'name',
                    'amount',
                    'fund',
                    'owner',
                    'contract_id',
                    'county_id',
                    'created_at',
                    'updated_at',
                ]
            );

        $this->assertDatabaseHas('expenses', array_merge($payload, ['id' => $expense->id]));
    }

    public function testExpenseIsDestroyed()
    {
        $expense = Expense::factory()->create();

        $this->json('delete', "api/expenses/{$expense->id}")
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
    }
}
