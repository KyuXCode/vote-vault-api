<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\County;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CountyController extends Controller
{
    public function index()
    {
        return response()->json(County::orderBy('id')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:1', 'max:255', 'unique:counties,name'],
        ]);

        $county = County::create($data);
        return response()->json($county, 201);
    }

    public function show(string $id)
    {
        $county = County::findOrFail($id);
        return response()->json($county);
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'min:1', 'max:255', 'unique:counties,name,' . $id],
        ]);

        $county = County::findOrFail($id);
        $county->update($data);

        return response()->json($county);
    }

    public function destroy(string $id)
    {
        $county = County::findOrFail($id);
        $county->delete();

        return response()->json(['message' => 'County deleted successfully']);
    }
}
