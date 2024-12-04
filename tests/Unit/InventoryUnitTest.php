<?php

namespace Tests\Feature;

use App\Models\Component;
use App\Models\Expense;
use App\Models\InventoryUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class InventoryUnitTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function testIndexReturnsDataInValidFormat()
    {
        $this->json('get', 'api/inventory_units')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    '*' => [
                        'id',
                        'serial_number',
                        'acquisition_date',
                        'condition',
                        'usage',
                        'expense_id',
                        'component_id',
                        'created_at',
                        'updated_at',
                    ]
                ]
            );
    }

    public function testInventoryUnitIsCreatedSuccessfully()
    {
        $expense = Expense::factory()->create();
        $component = Component::factory()->create();

        $payload = [
            'serial_number' => $this->faker->unique()->regexify('[A-Z0-9]{10}'),
            'acquisition_date' => $this->faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'condition' => $this->faker->randomElement(['New', 'Excellent', 'Good', 'Worn', 'Damaged', 'Unusable']),
            'usage' => $this->faker->randomElement(['Active', 'Spare', 'Display', 'Other', 'Inactive']),
            'expense_id' => $expense->id,
            'component_id' => $component->id,
        ];

        $this->json('post', 'api/inventory_units', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'id',
                    'serial_number',
                    'acquisition_date',
                    'condition',
                    'usage',
                    'expense_id',
                    'component_id',
                    'created_at',
                    'updated_at',
                ]
            );

        $this->assertDatabaseHas('inventory_units', $payload);
    }

    public function testInventoryUnitIsUpdatedSuccessfully()
    {
        $inventoryUnit = InventoryUnit::factory()->create();

        $payload = [
            'serial_number' => $this->faker->unique()->regexify('[A-Z0-9]{10}'),
            'acquisition_date' => $this->faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'condition' => $this->faker->randomElement(['New', 'Excellent', 'Good', 'Worn', 'Damaged', 'Unusable']),
            'usage' => $this->faker->randomElement(['Active', 'Spare', 'Display', 'Other', 'Inactive']),
            'expense_id' => $inventoryUnit->expense_id,
            'component_id' => $inventoryUnit->component_id,
        ];

        $this->json('put', "api/inventory_units/{$inventoryUnit->id}", $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'id',
                    'serial_number',
                    'acquisition_date',
                    'condition',
                    'usage',
                    'expense_id',
                    'component_id',
                    'created_at',
                    'updated_at',
                ]
            );

        $this->assertDatabaseHas('inventory_units', array_merge($payload, ['id' => $inventoryUnit->id]));
    }

    public function testInventoryUnitIsDestroyed()
    {
        $inventoryUnit = InventoryUnit::factory()->create();

        $this->json('delete', "api/inventory_units/{$inventoryUnit->id}")
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('inventory_units', ['id' => $inventoryUnit->id]);
    }
}
