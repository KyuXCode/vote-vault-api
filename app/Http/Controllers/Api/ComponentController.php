<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Component;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ComponentController extends Controller
{
    public function index()
    {
        $components = Component::orderBy('id')->get();
        return response()->json($components);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:DRE,OpScan,BMD,VVPAT,COTS,Other,Hardware,Software,Peripheral'],
            'certification_id' => ['required','integer','exists:certifications,id'],
        ]);

        $component = Component::create($data);
        return response()->json($component, 201);
    }

    public function show(string $id)
    {
        $component = Component::findOrFail($id);
        return response()->json($component);
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string', 'max:255'],
            'type' => ['sometimes', 'in:DRE,OpScan,BMD,VVPAT,COTS,Other,Hardware,Software,Peripheral'],
            'certification_id' => ['sometimes', 'integer','exists:certifications,id'],
        ]);

        $component = Component::findOrFail($id);
        $component->update($data);

        return response()->json($component);
    }

    public function destroy(string $id)
    {
        $component = Component::findOrFail($id);
        $component->delete();

        return response()->json(['message' => 'Component deleted successfully']);
    }
}
