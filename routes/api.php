<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// Shield Domain API Routes (Public, CORS enabled)
Route::prefix('shield-domain')->group(function () {
    // Get config for shield domain
    Route::get('/config', [\App\Http\Controllers\ShieldDomainApiController::class, 'getConfig']);
    
    // Checkout endpoints
    Route::get('/checkout/init', [\App\Http\Controllers\ShieldDomainApiController::class, 'initCheckout']);
    Route::post('/checkout/submit', [\App\Http\Controllers\ShieldDomainApiController::class, 'submitCheckout']);
    
    // Renewal endpoints
    Route::get('/renewal/lookup', [\App\Http\Controllers\ShieldDomainApiController::class, 'renewalLookup']);
    Route::get('/renewal/{orderNumber}', [\App\Http\Controllers\ShieldDomainApiController::class, 'renewalShow']);
    Route::post('/renewal/{orderNumber}', [\App\Http\Controllers\ShieldDomainApiController::class, 'renewalSubmit']);
});