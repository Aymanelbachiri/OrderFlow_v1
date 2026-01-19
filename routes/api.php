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

// Public Affiliate API Routes (for Next.js frontend)
Route::prefix('affiliate')->name('api.affiliate.')->group(function () {
    Route::post('/register', [\App\Http\Controllers\Api\AffiliateController::class, 'register'])->name('register');
    Route::get('/dashboard', [\App\Http\Controllers\Api\AffiliateController::class, 'dashboard'])->name('dashboard');
    Route::post('/fetch-subscriptions', [\App\Http\Controllers\Api\AffiliateController::class, 'fetchSubscriptions'])->name('fetch-subscriptions');
});

// Trial Request API (public endpoint)
Route::post('/trial-requests', [\App\Http\Controllers\Api\TrialRequestController::class, 'store'])->name('api.trial-requests.store');

// HotPlayer API (public endpoint for MAC validation)
Route::post('/hotplayer/check-device', [\App\Http\Controllers\Api\HotPlayerController::class, 'checkDevice'])->name('api.hotplayer.check-device');