<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use Orion\Facades\Orion;


Route::middleware('auth:sanctum')->group(function () {
    Orion::resource('translations', InventoryController::class)->only('store', 'update', 'index', 'search', 'show');
});

// Route::namespace('\App\Actions')->group(function () {
//     Route::post('/inventory/export', ExportInventory::class)->middleware(['auth:sanctum', \App\Http\Middleware\ApiLocalization::class]);
// });