<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Component;
use Illuminate\Http\Request;

class ComponentController extends Controller
{
    public function getComponents()
    {
        return Component::orderBy('id')->get();
    }

    public function createComponent(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:DRE,OpScan,BMD,VVPAT,COTS,Other,Hardware,Software,Peripheral'],
            'certification_id' => ['required', 'exists:certifications,id'],
        ]);

        return Component::create($data);
    }

    public function getComponent(string $id)
    {
        return Component::findOrFail($id);
    }

    public function updateComponent(Request $request, string $id)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'type' => ['sometimes', 'in:DRE,OpScan,BMD,VVPAT,COTS,Other,Hardware,Software,Peripheral'],
            'certification_id' => ['sometimes', 'exists:certifications,id'],
        ]);

        $component = Component::findOrFail($id);
        $component->update($data);

        return $component;
    }

    public function deleteComponent(string $id)
    {
        $component = Component::findOrFail($id);
        return $component->delete();
    }
}
