<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::get('/profile', [AuthController::class, 'profile'])->middleware(['auth:sanctum', 'verified']);

Route::post('/verify-phone', [AuthController::class, 'verifyPhone']);
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['signed'])->name('verification.verify');
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth:sanctum')->name('verification.notice');

Route::post('/upload/image', \App\Actions\UploadImage::class);

Route::post('/email/verification-notification', [AuthController::class, 'resendEmailVerification'])
    ->middleware(['auth:sanctum'])->name('verification.send');
