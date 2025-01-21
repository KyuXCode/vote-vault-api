<?php

namespace Tests\Unit;

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

    public function testValidationErrorsArePresent()
    {
        $InventoryUnit = InventoryUnit::factory()->withAllfields()->make();
        $InventoryUnit->acquisition_date = null;

        $this->json('post', 'api/inventory_units', $InventoryUnit->toArray())
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['acquisition_date']);


        self::assertTrue(InventoryUnit::where('A1B2C3D4E5', $InventoryUnit->serial_number)->doesntExist());
    }


    public function testInventoryUnitIsCreatedSuccessfully()
    {
        $InventoryUnit = InventoryUnit::factory()->withAllfields()->make();

        $this->json('post', 'api/inventory_units', $InventoryUnit->toArray())
            ->assertCreated()
            ->assertJson($InventoryUnit->toArray());

        self::assertTrue(
            InventoryUnit::where('A1B2C3D4E5', $InventoryUnit->serial_number)->exists()
        );
    }

    public function testInventoryUnitIsUpdatedSuccessfully()
    {
        $InventoryUnit = InventoryUnit::factory()->withAllfields()->create();
        $InventoryUnit->serial_number = 'A112B223';

        $this->json('put', "api/inventory_units/{$InventoryUnit->id}", $InventoryUnit->toArray())
            ->assertStatus(Response::HTTP_OK)
            ->assertJson($InventoryUnit->toArray());

        self::assertTrue(
            $InventoryUnit::where('A112B223', $InventoryUnit->serial_number)->exists()
        );
    }

    public function testInventoryUnitIsDestroyed()
    {
        $inventoryUnit = InventoryUnit::factory()->create();

        $this->json('delete', "api/inventory_units/{$inventoryUnit->id}")
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('inventory_units', ['id' => $inventoryUnit->id]);
    }
}
