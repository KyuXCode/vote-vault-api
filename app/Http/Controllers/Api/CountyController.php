<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\County;
use Illuminate\Http\Request;

class CountyController extends Controller
{
    public function getCounties()
    {
        return County::orderBy('id')->get();
    }

    public function createCounty(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:1', 'max:255', 'unique:counties,name'],
        ]);

        if ($data) {
            return County::create($data);
        } else {
            return response()->json(['message' => 'Error Creating'], 404);
        }
    }

    public function getCounty(string $id)
    {
        return County::findOrFail($id);
    }

    public function updateCounty(Request $request, string $id)
    {
        $request->validate([
            'name' => ['sometimes', 'string', 'min:1', 'max:255', 'unique:counties,name'],
        ]);

        $vendor = County::findOrFail($id);
        return $vendor->update($request->all());
    }

    public function deleteCounty(string $id)
    {
        $vendor = County::findOrFail($id);
        return $vendor->delete();
    }
}
