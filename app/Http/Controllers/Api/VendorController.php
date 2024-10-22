<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function getVendors()
    {
        return Vendor::orderBy('id')->get();
    }

    public function createVendor(Request $request)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'min:1',
                'max:255',
                'unique:vendors,name'
            ],
            'address' => [
                'nullable',
                'string',
                'max:255',
            ],
            'city' => [
                'nullable',
                'string',
                'max:255',
            ],
            'state' => [
                'nullable',
                'string',
                'min:2',
                'max:2',
            ],
            'zip' => [
                'nullable',
                'string',
                'min:5',
            ],
            'contact_name' => [
                'nullable',
                'string',
                'max:255',
            ],
            'contact_email' => [
                'nullable',
                'string',
                'email',
                'max:255',
            ],
            'contact_phone' => [
                'nullable',
                'string',
                'max:20',
            ],
            'product' => [
                'required',
                'in:EPB,VS,EPB & VS,Service,Other'
            ]
        ]);

        if ($data) {
            return Vendor::create($data);
        } else{
            return response()->json(['message' => 'Error Creating'], 404);
        }
    }

    public function getVendor(string $id)
    {
        return Vendor::findOrFail($id);
    }

    public function updateVendor(Request $request, string $id)
    {
        $request->validate([
            'name' => [
                'string',
                'min:1',
                'max:255',
                'unique:vendors,name'
            ],
            'address' => [
                'nullable',
                'string',
                'max:255',
            ],
            'city' => [
                'nullable',
                'string',
                'max:255',
            ],
            'state' => [
                'nullable',
                'string',
                'min:2',
                'max:2',
            ],
            'zip' => [
                'nullable',
                'string',
                'min:5',
            ],
            'contact_name' => [
                'nullable',
                'string',
                'max:255',
            ],
            'contact_email' => [
                'nullable',
                'string',
                'email',
                'max:255',
            ],
            'contact_phone' => [
                'nullable',
                'string',
                'max:20',
            ],
            'product' => [
                'in:EPB,VS,EPB & VS,Service,Other'
            ]
        ]);

        $vendor = Vendor::findOrFail($id);
        return $vendor->update($request->all());
    }

    public function deleteVendor(string $id)
    {
        $vendor = Vendor::findOrFail($id);
        return $vendor->delete();
    }
}

