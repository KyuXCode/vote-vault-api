<?php

use App\Http\Controllers\Api\CertificationController;
use App\Http\Controllers\Api\ComponentController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\CountyController;
use App\Http\Controllers\Api\ExpenseController;
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


//Component
Route::get('/components', [ComponentController::class, 'getComponents']);
Route::get('/components/{id}', [ComponentController::class, 'getComponent']);

Route::post('/components', [ComponentController::class, 'createComponent']);
Route::post('/components/{id}', [ComponentController::class, 'updateComponent']);

Route::delete('/components/{id}', [ComponentController::class, 'deleteComponent']);

//County
Route::get('/counties', [CountyController::class, 'getCounties']);
Route::get('/counties/{id}', [CountyController::class, 'getCounty']);

Route::post('/counties', [CountyController::class, 'createCounty']);
Route::post('/counties/{id}', [CountyController::class, 'updateCounty']);

Route::delete('/counties/{id}', [CountyController::class, 'deleteCounty']);


//Contract
Route::get('/contracts', [ContractController::class, 'getContracts']);
Route::post('/contracts', [ContractController::class, 'createContract']);
Route::get('/contracts/{id}', [ContractController::class, 'getContract']);
Route::put('/contracts/{id}', [ContractController::class, 'updateContract']);
Route::delete('/contracts/{id}', [ContractController::class, 'deleteContract']);

Route::resource('expenses', ExpenseController::class);
