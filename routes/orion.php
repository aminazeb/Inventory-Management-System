<?php

use Orion\Facades\Orion;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SalesController;
use App\Actions\ExportInventory;

Route::middleware('auth:sanctum')->group(function () {
    Orion::resource('products', ProductController::class);
    Orion::resource('inventory', InventoryController::class);
    Orion::resource('users', UserController::class)->only('index', 'search', 'show', 'update');
    Orion::resource('purchases', PurchaseController::class);
    Orion::resource('sales', SalesController::class);
});


Route::namespace('\App\Actions')->group(function () {
    Route::post('/inventory/export', ExportInventory::class)->middleware(['auth:sanctum', \App\Http\Middleware\ApiLocalization::class]);
});
