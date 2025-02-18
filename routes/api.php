<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\ProductCategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ServiceCategoryController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\DeviceTypeController;
use App\Http\Controllers\API\TypeStatusController;
use App\Http\Controllers\API\SubStatusController;
use App\Http\Controllers\API\DeviceModelController;
use App\Http\Controllers\API\DeviceBrandController;
use App\Http\Controllers\API\DeviceColorController;
use App\Http\Controllers\API\DeviceController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\OrderHistoryController;



Route::apiResource('clients', ClientController::class);
Route::apiResource('product-categories', ProductCategoryController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('service-categories', ServiceCategoryController::class);
Route::apiResource('services', ServiceController::class);
Route::apiResource('device-types', DeviceTypeController::class);
Route::apiResource('type-statuses', TypeStatusController::class);
Route::apiResource('sub-statuses', SubStatusController::class);
Route::apiResource('device-models', DeviceModelController::class);
Route::apiResource('device-brands', DeviceBrandController::class);
Route::apiResource('device-colors', DeviceColorController::class);
Route::apiResource('devices', DeviceController::class);
Route::apiResource('orders', OrderController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
