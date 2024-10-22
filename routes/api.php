<?php

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
