<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StorageLocation;
use Illuminate\Http\Request;

class StorageLocationController extends Controller
{
    public function index()
    {
        $storage_locations = StorageLocation::orderBy('id')->get();
        return response()->json($storage_locations);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:1', 'max:255', 'unique:storage_locations,name'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'min:2', 'max:2'],
            'zip' => ['required', 'string', 'min:5'],
            'inventory_unit_id' => ['required', 'integer', 'exists:inventory_units,id'],
        ]);

        $storage_location = StorageLocation::create($data);
        return response()->json($storage_location, 201);
    }

    public function show(string $id)
    {
        $storage_location = StorageLocation::findOrFail($id);
        return response()->json($storage_location);
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'min:1', 'max:255', 'unique:storage_locations,name'],
            'address' => ['sometimes', 'string', 'max:255'],
            'city' => ['sometimes', 'string', 'max:255'],
            'state' => ['sometimes', 'string', 'min:2', 'max:2'],
            'zip' => ['sometimes', 'string', 'min:5'],
            'inventory_unit_id' => ['sometimes', 'integer', 'exists:inventory_units,id'],
        ]);

        $storage_location = StorageLocation::findOrFail($id);
        $storage_location->update($data);

        return response()->json($storage_location);
    }

    public function destroy(string $id)
    {
        $storage_location = StorageLocation::findOrFail($id);
        $storage_location->delete();

        return response()->json(['message' => 'Storage Location deleted successfully']);
    }
}
