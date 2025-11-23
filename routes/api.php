<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// WordPress Integration API Routes (Single-user version)
Route::middleware(['auth:sanctum'])->prefix('wordpress')->name('api.wordpress.')->group(function () {
    Route::get('/products', [\App\Http\Controllers\Api\WordPressIntegrationController::class, 'getProducts'])->name('products');
    Route::post('/tokens/generate', [\App\Http\Controllers\Api\WordPressIntegrationController::class, 'generateToken'])->name('tokens.generate');
    Route::get('/tokens', [\App\Http\Controllers\Api\WordPressIntegrationController::class, 'getTokens'])->name('tokens');
    Route::delete('/tokens/{tokenId}', [\App\Http\Controllers\Api\WordPressIntegrationController::class, 'revokeToken'])->name('tokens.revoke');
});