<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Disposition;
use Illuminate\Http\Request;

class DispositionController extends Controller
{
    public function index()
    {
        $dispositions = Disposition::orderBy('id')->get();
        return response()->json($dispositions);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => ['required', 'date'],
            'method' => ['required', 'string', 'max:255'],
            'entity' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric'],
            'inventory_unit_id' => ['required', 'integer', 'exists:inventory_units,id'],
        ]);

        $disposition = Disposition::create($data);
        return response()->json($disposition, 201);
    }

    public function show($id)
    {
        $disposition = Disposition::findOrFail($id);
        return response()->json($disposition);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'date' => ['sometimes', 'date'],
            'method' => ['sometimes', 'string', 'max:255'],
            'entity' => ['sometimes', 'string', 'max:255'],
            'amount' => ['sometimes', 'numeric'],
            'inventory_unit_id' => ['sometimes', 'integer', 'exists:inventory_units,id'],
        ]);

        $disposition = Disposition::findOrFail($id);
        $disposition->update($data);
        return response()->json($disposition);
    }

    public function destroy($id)
    {
        $disposition = Disposition::findOrFail($id);
        $disposition->delete();
        return response()->json(['message' => 'Disposition deleted successfully']);
    }
}

