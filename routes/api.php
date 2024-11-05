<?php

use App\Http\Controllers\Api\CertificationController;
use App\Http\Controllers\Api\ComponentController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\CountyController;
use App\Http\Controllers\Api\DashboardDataController;
use App\Http\Controllers\Api\DispositionController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\InventoryUnitController;
use App\Http\Controllers\Api\StorageLocationController;
use App\Http\Controllers\Api\VendorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('vendors', VendorController::class);

Route::resource('certifications', CertificationController::class);

Route::resource('components', ComponentController::class);

Route::resource('counties', CountyController::class);

Route::resource('contracts', ContractController::class);

Route::resource('expenses', ExpenseController::class);

Route::resource('inventory_units', InventoryUnitController::class);

Route::resource('dispositions', DispositionController::class);

Route::resource('storage_locations', StorageLocationController::class);

Route::get('/api/dashboard_data', [DashboardDataController::class, 'getDashboardData']);
