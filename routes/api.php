<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SignatureRequestController;
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

// Signasture Request routes
Route::middleware([
    'auth:sanctum',
])->group(function () {
    Route::get('/signature-requests/received-requests', [SignatureRequestController::class, 'receivedSignatureRequests'])
        ->name('signature-requests.received-requests');
    Route::apiResource('signature-requests', SignatureRequestController::class)->only(['index', 'store']);
});
