<?php

namespace Tests\Unit;

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

    public function testValidationErrorsArePresent()
    {
        $Expense = Expense::factory()->withAllFields()->make();
        $Expense->amount = null;

        $this->json('post', 'api/expenses', $Expense->toArray())
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['amount']);

        self::assertTrue(
            Expense::where('Example expense', $Expense->name)->doesntExist()
        );
    }

    public function testExpenseIsCreatedSuccessfully()
    {
        $Expense = Expense::factory()->withAllFields()->make();

        $this->json('post', 'api/expenses', $Expense->toArray())
            ->assertCreated()
            ->assertJson($Expense->toArray());

        self::assertTrue(
            Expense::where('Example expense', $Expense->name)->exists()
        );
    }

    public function testExpenseIsUpdatedSuccessfully()
    {
        $Expense = Expense::factory()->withAllFields()->create();
        $Expense->name = 'Updated Expense';

        $this->json('put', "api/expenses/{$Expense->id}", $Expense->toArray())
            ->assertStatus(Response::HTTP_OK)
            ->assertJson($Expense->toArray());

        self::assertTrue(
            Expense::where('Updated Expense', $Expense->name)->exists()
        );
    }

    public function testExpenseIsDestroyed()
    {
        $expense = Expense::factory()->create();

        $this->json('delete', "api/expenses/{$expense->id}")
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
    }
}
