<?php

namespace Tests\Feature;

use App\Models\Certification;
use App\Models\Component;
use App\Models\Contract;
use App\Models\County;
use App\Models\InventoryUnit;
use App\Models\Disposition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuditTest extends TestCase
{
    use WithFaker;

    public function test_audit_public_tests()
    {
        $inventoryUnit = InventoryUnit::factory()->create();
        $component = Component::factory()->create();
        $certification = Certification::factory()->create();
        $contract = Contract::factory()->create();
        $county = County::factory()->create();

        $inventoryUnit->component()->associate($component);
        $component->certification()->associate($certification);
        $certification->contract()->associate($contract);
        $inventoryUnit->county()->associate($county);

        $response = $this->json('GET', 'api/audits/public_test');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'results' => [
                '*' => [
                    'inventory_id',
                    'serial_number',
                    'condition',
                    'usage',
                    'component_name',
                    'component_type',
                    'system_base',
                    'county_name',
                    'row_num'
                ]
            ]
        ]);
    }

    public function test_audit_random_test()
    {
        $inventoryUnit = InventoryUnit::factory()->create();
        $component = Component::factory()->create();
        $certification = Certification::factory()->create();
        $contract = Contract::factory()->create();
        $county = County::factory()->create();

        $inventoryUnit->component()->associate($component);
        $component->certification()->associate($certification);
        $certification->contract()->associate($contract);
        $inventoryUnit->county()->associate($county);

        $response = $this->json('GET', 'api/audits/random');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'seed_number',
            'results' => [
                '*' => [
                    'inventory_id',
                    'serial_number',
                    'condition',
                    'usage',
                    'component_name',
                    'county_name',
                    'total_count',
                    'row_num',
                ],
            ],
        ]);
    }
}
