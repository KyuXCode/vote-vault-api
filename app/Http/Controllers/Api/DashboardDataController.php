<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\Component;
use App\Models\Contract;
use App\Models\Expense;
use App\Models\InventoryUnit;
use App\Models\Vendor;

class DashboardDataController extends Controller
{
    public function getDashboardData()
    {
        $dashboardData = [
            'total_vendors' => Vendor::count(),
            'total_certifications' => Certification::count(),
            'total_components' => Component::count(),
            'total_contracts' => Contract::count(),
            'total_expenses' => Expense::sum('amount'),
            'total_inventory_units' => InventoryUnit::count(),
        ];

        return response()->json($dashboardData);
    }
}
