<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-supabase', function () {
    dd(Storage::disk('s3')->put('uploads/sample.jpg', file_get_contents(resource_path('images/blue_pen.jpg'))));
});
