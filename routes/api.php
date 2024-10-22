<?php

use App\Http\Controllers\Api\CertificationController;
use App\Http\Controllers\Api\VendorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Vendor
Route::get('/vendors', [VendorController::class, 'getVendors']);
Route::get('/vendors/{id}', [VendorController::class, 'getVendor']);

Route::post('/vendors', [VendorController::class, 'createVendor']);
Route::post('/vendors/{id}', [VendorController::class, 'updateVendor']);

Route::delete('/vendors/{id}', [VendorController::class, 'deleteVendor']);


//Certification
Route::get('/certifications', [CertificationController::class, 'getCertifications']);
Route::get('/certifications/{id}', [CertificationController::class, 'getCertification']);

Route::post('/certifications', [CertificationController::class, 'createCertification']);
Route::post('/certifications/{id}', [CertificationController::class, 'updateCertification']);

Route::delete('/certifications/{id}', [CertificationController::class, 'deleteCertification']);
