<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::orderBy('id')->get();
        return response()->json($vendors);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:1', 'max:255', 'unique:vendors,name'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'min:2', 'max:2'],
            'zip' => ['nullable', 'string', 'min:5'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'string', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
            'product' => ['required', 'in:EPB,VS,EPB & VS,Service,Other']
        ]);

        $vendor = Vendor::create($data);
        return response()->json($vendor, 201);
    }

    public function show(string $id)
    {
        $vendor = Vendor::findOrFail($id);
        return response()->json($vendor);
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'min:1', 'max:255', 'unique:vendors,name,' . $id],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'min:2', 'max:2'],
            'zip' => ['nullable', 'string', 'min:5'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'string', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
            'product' => ['sometimes', 'in:EPB,VS,EPB & VS,Service,Other']
        ]);

        $vendor = Vendor::findOrFail($id);
        $vendor->update($data);

        return response()->json($vendor);
    }

    public function destroy(string $id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();

        return response()->json(['message' => 'Vendor deleted successfully']);
    }
}
