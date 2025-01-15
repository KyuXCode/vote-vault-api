<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Action;
use App\Helpers\CertificationType;
use App\Helpers\SystemBase;
use App\Helpers\SystemType;
use App\Http\Controllers\Controller;
use App\Models\Certification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rules\Enum;

class CertificationController extends Controller
{
    public function index()
    {
        $certifications = Certification::with('components')->orderBy('id')->get();
        return response()->json($certifications);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'model_number' => ['required', 'string', 'min:1', 'max:255'],
            'description' => ['required', 'string'],
            'application_date' => ['required', 'date'],
            'certification_date' => ['required', 'date'],
            'expiration_date' => ['required', 'date'],
            'federal_certification_number' => ['nullable', 'string', 'max:255'],
            'federal_certification_date' => ['nullable', 'date'],
            'type' => ['required', new Enum(CertificationType::class)],
            'action' => ['required', new Enum(Action::class)],
            'system_type' => ['required', new Enum(SystemType::class)],
            'system_base' => ['required', new Enum(SystemBase::class)],
            'vendor_id' => ['required', 'exists:vendors,id'],
        ]);

        $certification = Certification::create($data);
        return response()->json($certification, Response::HTTP_CREATED);
    }

    public function show(string $id)
    {
        $certification = Certification::with('components')->findOrFail($id);
        return response()->json($certification);
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'model_number' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'application_date' => ['sometimes', 'date'],
            'certification_date' => ['sometimes', 'date'],
            'expiration_date' => ['sometimes', 'date'],
            'federal_certification_number' => ['nullable', 'string', 'max:255'],
            'federal_certification_date' => ['nullable', 'date'],
            'type' => ['sometimes', new Enum(CertificationType::class)],
            'action' => ['sometimes', new Enum(Action::class)],
            'system_type' => ['sometimes', new Enum(SystemType::class)],
            'system_base' => ['sometimes', new Enum(SystemBase::class)],
            'vendor_id' => ['sometimes', 'exists:vendors,id'],
        ]);

        $certification = Certification::findOrFail($id);
        $certification->update($data);

        return response()->json($certification);
    }

    public function destroy(string $id)
    {
        $certification = Certification::findOrFail($id);
        $certification->delete();

        return response()->json(['message' => 'Certification deleted successfully'], Response::HTTP_OK);
    }
}
