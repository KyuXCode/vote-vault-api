<?php

namespace Tests\Feature;

use App\Models\Certification;
use App\Models\Component;
use App\Models\Contract;
use App\Models\Expense;
use App\Models\InventoryUnit;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardDataTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function testDashboardData()
    {
        Vendor::factory()->count(5)->create();
        Certification::factory()->count(3)->create();
        Component::factory()->count(4)->create();
        Contract::factory()->count(2)->create();
        Expense::factory()->count(6)->create();
        InventoryUnit::factory()->count(7)->create();

        $response = $this->json('GET', 'api/dashboard_data');

        $response->assertStatus(200);

        $expectedExpenseSum = Expense::sum('amount');

        $response->assertJson([
            'total_vendors' => 5,
            'total_certifications' => 3,
            'total_components' => 4,
            'total_contracts' => 2,
            'total_expenses' => $expectedExpenseSum,
            'total_inventory_units' => 7,
        ]);
    }
}
