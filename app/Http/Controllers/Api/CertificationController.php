<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CertificationController extends Controller
{
    public function getCertifications(): Collection
    {
        return Certification::with('components')->orderBy('id')->get();
    }

    public function createCertification(Request $request)
    {
        $data = $request->validate([
            'model_number' => ['required', 'string','min:1', 'max:255'],
            'description' => ['required', 'string'],
            'application_date' => ['required', 'date'],
            'certification_date' => ['required', 'date'],
            'expiration_date' => ['required', 'date'],
            'federal_certification_number' => ['nullable', 'string', 'max:255'],
            'federal_certification_date' => ['nullable', 'date'],
            'type' => ['required', 'in:Certification,Reevaluation,Renewal,Recertification,Other'],
            'action' => ['required', 'in:Approved,Pending,Denied,Other'],
            'system_type' => ['required', 'in:VS,EPB'],
            'system_base' => ['required', 'in:DRE,OpScan,PC/Laptop,Tablet,Custom Hardware,Other'],
            'vendor_id' => ['required', 'exists:vendors,id'],
        ]);

        return Certification::create($data);
    }

    public function getCertification(string $id)
    {
        return Certification::with('components')->findOrFail($id);
    }

    public function updateCertification(Request $request, string $id)
    {
        $data = $request->validate([
            'model_number' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'application_date' => ['sometimes', 'date'],
            'certification_date' => ['sometimes', 'date'],
            'expiration_date' => ['sometimes', 'date'],
            'federal_certification_number' => ['nullable', 'string', 'max:255'],
            'federal_certification_date' => ['nullable', 'date'],
            'type' => ['sometimes', 'in:Certification,Reevaluation,Renewal,Recertification,Other'],
            'action' => ['sometimes', 'in:Approved,Pending,Denied,Other'],
            'system_type' => ['sometimes', 'in:VS,EPB'],
            'system_base' => ['sometimes', 'in:DRE,OpScan,PC/Laptop,Tablet,Custom Hardware,Other'],
            'vendor_id' => ['sometimes', 'exists:vendors,id'],
        ]);

        $certification = Certification::findOrFail($id);
        $certification->update($data);

        return $certification;
    }

    public function deleteCertification(string $id)
    {
        $certification = Certification::findOrFail($id);
        return $certification->delete();
    }
}
