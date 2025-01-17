<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ContractType;
use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rules\Enum;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = Contract::with('expenses')->orderBy('id')->get();
        return response()->json($contracts);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'begin_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:begin_date'],
            'type' => ['required', 'string', new Enum(ContractType::class)],
            'certification_id' => ['required', 'integer', 'exists:certifications,id'],
        ]);

        $contract = Contract::create($data);
        return response()->json($contract, 201);
    }

    public function show(string $id)
    {
        $contract = Contract::with('certification')->findOrFail($id);
        return response()->json($contract);
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'begin_date' => ['sometimes', 'date'],
            'end_date' => ['sometimes', 'date', 'after:begin_date'],
            'type' => ['sometimes', 'string', new Enum(ContractType::class)],
            'certification_id' => ['sometimes', 'integer', 'exists:certifications,id'],
        ]);

        $contract = Contract::findOrFail($id);
        $contract->update($data);

        return response()->json($contract);
    }

    public function destroy(string $id)
    {
        $contract = Contract::findOrFail($id);
        $contract->delete();

        return response()->json(['message' => 'Contract deleted successfully']);
    }
}
