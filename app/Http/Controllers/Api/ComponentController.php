<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ComponentType;
use App\Http\Controllers\Controller;
use App\Models\Component;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rules\Enum;

class ComponentController extends Controller
{
    public function index()
    {
        return response()->json(Component::orderBy('id')->get());
    }

    public function store(Request $request)
    {
        return response()
            ->json(
                Component::create(
                    $request->validate([
                        'name' => ['required', 'string', 'max:255'],
                        'description' => ['required', 'string', 'max:255'],
                        'type' => ['required', new Enum(ComponentType::class)],
                        'certification_id' => ['required', 'integer', 'exists:certifications,id'],
                    ])
                ),
                201
            );
    }

    public function batchStore(Request $request)
    {
        $data = $request->validate([
            'components' => ['required', 'array'],
            'components.*.name' => ['required', 'string', 'max:255'],
            'components.*.description' => ['required', 'string', 'max:255'],
            'components.*.type' => ['required', new Enum(ComponentType::class)],
            'components.*.certification_id' => ['required', 'integer', 'exists:certifications,id'],
        ]);

        $components = [];
        foreach ($data['components'] as $componentData) {
            $components[] = Component::create($componentData);
        }

        return response()->json($components, 201);
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
            'type' => ['sometimes', new Enum(ComponentType::class)],
            'certification_id' => ['sometimes', 'integer', 'exists:certifications,id'],
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
