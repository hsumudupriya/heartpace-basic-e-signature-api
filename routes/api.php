<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::post('login', LoginController::class)->name('login');
Route::post('register', RegisterController::class)->name('register');

// Document routes
Route::middleware([
    'auth:sanctum',
])->group(function () {
    Route::apiResource('documents', DocumentController::class)->only(['index', 'store']);
});
