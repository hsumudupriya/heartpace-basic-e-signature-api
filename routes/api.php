<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SignatureRequestController;
use App\Http\Controllers\SignDocumentController;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::post('login', LoginController::class)->name('login');
Route::post('register', RegisterController::class)->name('register');

// Routes protected by access token
Route::middleware([
    'auth:sanctum',
])->group(function () {
    // Document routes
    Route::apiResource('documents', DocumentController::class)->only(['index', 'store']);

    // Signasture Request routes
    Route::get('/signature-requests/received-requests', [SignatureRequestController::class, 'receivedSignatureRequests'])
        ->name('signature-requests.received-requests');
    Route::apiResource('signature-requests', SignatureRequestController::class)->only(['index', 'store']);

    // Sign document
    Route::post('sign-document', SignDocumentController::class)->name('sign-document');
});
