<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ConditionType;
use App\Helpers\UsageType;
use App\Http\Controllers\Controller;
use App\Models\County;
use App\Models\InventoryUnit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class InventoryUnitController extends Controller
{
    public function index()
    {
        $inventory_units = InventoryUnit::orderBy('id')->get();
        return response()->json($inventory_units);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'serial_number' => ['required', 'string', 'max:255'],
            'acquisition_date' => ['required', 'date'],
            InventoryUnit::condition => ['required', new Enum(ConditionType::class)],
            InventoryUnit::usage => ['required', new Enum(UsageType::class)],
            'expense_id' => ['required', 'integer', 'exists:expenses,id'],
            'component_id' => ['required', 'integer', 'exists:components,id']
        ]);

        $inventory_units = InventoryUnit::create($data);
        return response()->json($inventory_units, 201);
    }

    public function show(string $id)
    {
        $inventoryUnits = InventoryUnit::findOrFail($id);
        return response()->json($inventoryUnits);
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'serial_number' => ['sometimes', 'string', 'max:255'],
            'acquisition_date' => ['sometimes', 'date'],
            InventoryUnit::condition => ['sometimes', new Enum(ConditionType::class)],
            InventoryUnit::usage => ['sometimes', new Enum(UsageType::class)],
            'expense_id' => ['sometimes', 'integer', 'exists:expenses,id'],
            'component_id' => ['sometimes', 'integer', 'exists:components,id']
        ]);

        $inventory_units = InventoryUnit::findOrFail($id);
        $inventory_units->update($data);

        return response()->json($inventory_units);
    }

    public function destroy(string $id)
    {
        $inventory_units = InventoryUnit::findOrFail($id);
        $inventory_units->delete();

        return response()->json(['message' => 'County deleted successfully']);
    }

    public function batchStore(Request $request)
    {
        $data = $request->validate([
            'inventory_units' => ['required', 'array'],
            'inventory_units.*.serial_number' => ['required', 'string', 'max:255'],
            'inventory_units.*.acquisition_date' => ['required', 'date'],
            'inventory_units.*.condition' => ['required', new Enum(ConditionType::class)],
            'inventory_units.*.usage' => ['required', new Enum(UsageType::class)],
            'inventory_units.*.expense_id' => ['required', 'integer', 'exists:expenses,id'],
            'inventory_units.*.component_id' => ['required', 'integer', 'exists:components,id']
        ]);

        $inventoryUnits = [];
        foreach ($data['inventory_units'] as $unitData) {
            $inventoryUnits[] = InventoryUnit::create($unitData);
        }

        return response()->json($inventoryUnits, 201);
    }
}

