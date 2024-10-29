<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function getContracts(): Collection
    {
        return Contract::with('expenses')->orderBy('id')->get();
    }

    public function createContract(Request $request)
    {
        $data = $request->validate([
            'begin_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:begin_date'],
            'type' => ['required', 'string', 'in:Purchase,Lease,Service,Other'],
            'certification_id' => ['required', 'integer', 'exists:certifications,id'],
        ]);

        return Contract::create($data);
    }

    public function getContract(string $id)
    {
        return Contract::with('certification')->findOrFail($id);
    }

    public function updateContract(Request $request, string $id)
    {
        $data = $request->validate([
            'begin_date' => ['sometimes', 'date'],
            'end_date' => ['sometimes', 'date', 'after:begin_date'],
            'type' => ['sometimes', 'string', 'in:Purchase,Lease,Service,Other'],
            'certification_id' => ['sometimes', 'integer', 'exists:certifications,id'],
        ]);

        $contract = Contract::findOrFail($id);
        $contract->update($data);

        return $contract;
    }

    public function deleteContract(string $id)
    {
        $contract = Contract::findOrFail($id);
        return $contract->delete();
    }
}
