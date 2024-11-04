<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::orderBy('id')->get();
        return response()->json($expenses);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric'],
            'fund' => ['required', 'string', 'max:255'],
            'owner' => ['required', 'string', 'max:255'],
            'contract_id' => ['required', 'exists:contracts,id'],
            'county_id' => ['required', 'integer', 'exists:counties,id'],
        ]);


        $expense = Expense::create($data);
        return response()->json($expense, 201);
    }

    public function show($id)
    {
        $expense = Expense::findOrFail($id);
        return response()->json($expense);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'amount' => ['sometimes', 'numeric'],
            'fund' => ['sometimes', 'string', 'max:255'],
            'owner' => ['sometimes', 'string', 'max:255'],
            'contract_id' => ['sometimes', 'exists:contracts,id'],
            'county_id' => ['sometimes', 'integer', 'exists:counties,id'],
        ]);

        $expense = Expense::findOrFail($id);
        $expense->update($data);
        return response()->json($expense);
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();
        return response()->json(['message' => 'Expense deleted successfully']);
    }
}
