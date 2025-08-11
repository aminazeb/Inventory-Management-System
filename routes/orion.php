<?php

use Orion\Facades\Orion;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;

Route::middleware('auth:sanctum')->group(function () {
    Orion::resource('products', ProductController::class);
    Orion::resource('inventory', InventoryController::class);
});

// Route::namespace('\App\Actions')->group(function () {
//     Route::post('/inventory/export', ExportInventory::class)->middleware(['auth:sanctum', \App\Http\Middleware\ApiLocalization::class]);
// });