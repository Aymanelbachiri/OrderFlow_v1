<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ResellerCheckoutController;
use App\Http\Controllers\PublicPaymentController;

// Public routes (minimal)
// New public website for CONTROL WEB AGENCY
Route::view('/', 'home')->name('home');

// Blog
// Route::get('/blog', [HomeController::class, 'blog'])->name('blog');
// Route::get('/blog/{post}', [HomeController::class, 'blogPost'])->name('blog.post');



// Services pages (views only)
Route::view('/services/website-development', 'services.website-development')->name('website-development');
Route::view('/services/graphic-design', 'services.graphic-design')->name('graphic-design');
Route::view('/services/digital-marketing', 'services.digital-marketing')->name('digital-marketing');
Route::view('/services/other-services', 'services.other-services')->name('other-services');

// Keep pricing pages accessible (hidden from navigation)
// Route::get('/pricing', [HomeController::class, 'pricing'])->name('pricing');
// Route::get('/reseller-plans', [\App\Http\Controllers\PublicResellerController::class, 'creditPacks'])->name('public.reseller-plans');

// Public reseller checkout (no auth) - iframe only from authorized sources
Route::middleware(['require.iframe.source'])->group(function () {
    Route::get('/reseller/checkout', [ResellerCheckoutController::class, 'show'])->name('reseller.checkout.show');
    Route::post('/reseller/checkout', [ResellerCheckoutController::class, 'submit'])->name('reseller.checkout.submit');
});



// Public payment routes (no auth)
Route::get('/payment-intents/{paymentIntent}/payment/stripe', [\App\Http\Controllers\PublicPaymentController::class, 'stripePayment'])->name('public.payment.stripe');
Route::get('/payment-intents/{paymentIntent}/payment/paypal', [\App\Http\Controllers\PublicPaymentController::class, 'paypalPayment'])->name('public.payment.paypal');
Route::get('/payment-intents/{paymentIntent}/payment/crypto', [\App\Http\Controllers\PublicPaymentController::class, 'cryptoPayment'])->name('public.payment.crypto');
Route::get('/payment-intents/{paymentIntent}/payment/coinbase-commerce', [\App\Http\Controllers\PublicPaymentController::class, 'coinbaseCommercePayment'])->name('public.payment.coinbase-commerce');
Route::get('/payment-intents/{paymentIntent}/payment/coinbase-commerce/success', [\App\Http\Controllers\PublicPaymentController::class, 'coinbaseCommerceSuccess'])->name('public.payment.coinbase-commerce.success');
Route::get('/payment-intents/{paymentIntent}/payment/coinbase-commerce/cancel', [\App\Http\Controllers\PublicPaymentController::class, 'coinbaseCommerceCancel'])->name('public.payment.coinbase-commerce.cancel');
Route::get('/payment-intents/{paymentIntent}/payment/coinbase-commerce/status', [\App\Http\Controllers\PublicPaymentController::class, 'coinbaseCommerceCheckStatus'])->name('public.payment.coinbase-commerce.status');
Route::post('/webhooks/coinbase-commerce', [\App\Http\Controllers\PublicPaymentController::class, 'coinbaseCommerceWebhook'])->name('webhooks.coinbase-commerce');
Route::match(['get', 'post'], '/payment-intents/{paymentIntent}/payment/success', [\App\Http\Controllers\PublicPaymentController::class, 'paymentIntentSuccess'])->name('public.payment-intents.success');
// Thank you page
Route::get('/thank-you/{order}', [PublicPaymentController::class, 'thankYou'])->name('public.thank-you');

// Public checkout (no auth) - iframe only from authorized sources
Route::middleware(['require.iframe.source'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'submit'])->name('checkout.submit');
    Route::post('/checkout/fetch-subscriptions', [CheckoutController::class, 'fetchSubscriptions'])->name('checkout.fetch-subscriptions');
    Route::post('/checkout/validate-referral', [CheckoutController::class, 'validateReferral'])->name('checkout.validate-referral');
});

// Public renewal routes (no auth) - iframe only from authorized sources
Route::middleware(['require.iframe.source'])->group(function () {
    Route::get('/renew', [\App\Http\Controllers\PublicRenewalController::class, 'lookup'])->name('renewal.lookup');
    Route::get('/renew/{orderNumber}', [\App\Http\Controllers\PublicRenewalController::class, 'show'])->name('renewal.show');
    Route::post('/renew/{orderNumber}', [\App\Http\Controllers\PublicRenewalController::class, 'submit'])->name('renewal.submit');
    Route::get('/renew/{orderNumber}/quick', [\App\Http\Controllers\PublicRenewalController::class, 'quickRenew'])->name('renewal.quick');
});

// Custom product checkout (no auth) - iframe only from authorized sources
Route::middleware(['require.iframe.source'])->group(function () {
    Route::get('/products/{product:slug}/checkout', [\App\Http\Controllers\CustomProductCheckoutController::class, 'show'])->name('custom-product.checkout.show');
    Route::post('/products/{product:slug}/checkout', [\App\Http\Controllers\CustomProductCheckoutController::class, 'submit'])->name('custom-product.checkout.submit');
});

// Public affiliate routes (no auth) - iframe compatible
Route::middleware(['require.iframe.source'])->group(function () {
    Route::get('/affiliate', [\App\Http\Controllers\AffiliateController::class, 'register'])->name('affiliate.register');
    Route::post('/affiliate', [\App\Http\Controllers\AffiliateController::class, 'store'])->name('affiliate.store');
    Route::get('/affiliate/dashboard', [\App\Http\Controllers\AffiliateController::class, 'dashboard'])->name('affiliate.dashboard');
    Route::post('/affiliate/fetch-subscriptions', [\App\Http\Controllers\AffiliateController::class, 'fetchSubscriptions'])->name('affiliate.fetch-subscriptions');
});

// Authenticated dashboard -> only admin kept
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified', 'role:admin'])->name('dashboard');

// Admin routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Client management
    Route::resource('clients', \App\Http\Controllers\Admin\ClientController::class);
    Route::post('/clients/{client}/suspend', [\App\Http\Controllers\Admin\ClientController::class, 'suspend'])->name('clients.suspend');
    Route::post('/clients/{client}/reactivate', [\App\Http\Controllers\Admin\ClientController::class, 'reactivate'])->name('clients.reactivate');
    Route::post('/clients/{client}/toggle-status', [\App\Http\Controllers\Admin\ClientController::class, 'toggleStatus'])->name('clients.toggle-status');
    Route::post('/clients/{client}/verify-email', [\App\Http\Controllers\Admin\ClientController::class, 'verifyEmail'])->name('clients.verify-email');
    Route::post('/clients/{client}/send-password-reset', [\App\Http\Controllers\Admin\ClientController::class, 'sendPasswordReset'])->name('clients.send-password-reset');

    // Order management
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
    Route::post('/orders/{order}/send-credentials', [\App\Http\Controllers\Admin\OrderController::class, 'sendCredentials'])->name('orders.send-credentials');
    Route::post('/orders/{order}/activate', [\App\Http\Controllers\Admin\OrderController::class, 'activate'])->name('orders.activate');
    Route::get('/orders/export', [\App\Http\Controllers\Admin\OrderController::class, 'export'])->name('orders.export');

    // Pricing management
    Route::resource('pricing', \App\Http\Controllers\Admin\PricingController::class);

    // Payment configuration
    Route::get('/payment-config', [\App\Http\Controllers\Admin\PricingController::class, 'paymentConfig'])->name('payment.config');
    Route::post('/payment-config', [\App\Http\Controllers\Admin\PricingController::class, 'updatePaymentConfig'])->name('payment.update-config');

    // Source management
    Route::resource('sources', \App\Http\Controllers\Admin\SourceController::class);
    Route::post('/sources/{source}/test-smtp', [\App\Http\Controllers\Admin\SourceController::class, 'testSmtp'])->name('sources.test-smtp');


    // Reseller management
    Route::resource('resellers', \App\Http\Controllers\Admin\ResellerController::class);
    Route::post('/resellers/{reseller}/suspend', [\App\Http\Controllers\Admin\ResellerController::class, 'suspend'])->name('resellers.suspend');
    Route::post('/resellers/{reseller}/reactivate', [\App\Http\Controllers\Admin\ResellerController::class, 'reactivate'])->name('resellers.reactivate');
    Route::post('/resellers/{reseller}/toggle-status', [\App\Http\Controllers\Admin\ResellerController::class, 'toggleStatus'])->name('resellers.toggle-status');
    Route::post('/resellers/{reseller}/send-password-reset', [\App\Http\Controllers\Admin\ResellerController::class, 'sendPasswordReset'])->name('resellers.send-password-reset');

    // Reseller credit pack management
    Route::resource('reseller-credit-packs', \App\Http\Controllers\Admin\ResellerCreditPackController::class);

    // Custom products management
    Route::resource('custom-products', \App\Http\Controllers\Admin\CustomProductController::class);
    Route::post('/custom-products/{customProduct}/toggle-status', [\App\Http\Controllers\Admin\CustomProductController::class, 'toggleStatus'])->name('custom-products.toggle-status');

    // Affiliate management
    Route::resource('affiliates', \App\Http\Controllers\Admin\AffiliateController::class);
    Route::post('/affiliates/{affiliate}/referrals/{referral}/approve', [\App\Http\Controllers\Admin\AffiliateController::class, 'approveReward'])->name('affiliates.referrals.approve');
    Route::post('/affiliates/{affiliate}/referrals/{referral}/reject', [\App\Http\Controllers\Admin\AffiliateController::class, 'rejectReward'])->name('affiliates.referrals.reject');
    Route::post('/affiliates/{affiliate}/grant-reward', [\App\Http\Controllers\Admin\AffiliateController::class, 'grantDirectReward'])->name('affiliates.grant-reward');

    // Trial requests management
    Route::get('/trial-requests', [\App\Http\Controllers\Admin\TrialRequestController::class, 'index'])->name('trial-requests.index');
    Route::get('/trial-requests/{trialRequest}', [\App\Http\Controllers\Admin\TrialRequestController::class, 'show'])->name('trial-requests.show');
    Route::post('/trial-requests/{trialRequest}/approve', [\App\Http\Controllers\Admin\TrialRequestController::class, 'approve'])->name('trial-requests.approve');
    Route::post('/trial-requests/{trialRequest}/reject', [\App\Http\Controllers\Admin\TrialRequestController::class, 'reject'])->name('trial-requests.reject');
    Route::post('/trial-requests/generate-m3u', [\App\Http\Controllers\Admin\TrialRequestController::class, 'generateTrialM3u'])->name('trial-requests.generate-m3u');
    Route::delete('/trial-requests/{trialRequest}', [\App\Http\Controllers\Admin\TrialRequestController::class, 'destroy'])->name('trial-requests.destroy');

    // Analytics
    Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');

    // System settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');

    // WordPress Integration
    Route::get('/wordpress-integration', [\App\Http\Controllers\Admin\WordPressIntegrationController::class, 'index'])->name('wordpress-integration.index');
    Route::post('/wordpress-integration/tokens/generate', [\App\Http\Controllers\Admin\WordPressIntegrationController::class, 'generateToken'])->name('wordpress-integration.generate-token');
    Route::delete('/wordpress-integration/tokens/{tokenId}', [\App\Http\Controllers\Admin\WordPressIntegrationController::class, 'revokeToken'])->name('wordpress-integration.revoke-token');
    Route::get('/wordpress-integration/download-plugin', [\App\Http\Controllers\Admin\WordPressIntegrationController::class, 'downloadPlugin'])->name('wordpress-integration.download-plugin');
    Route::get('/settings/system', [\App\Http\Controllers\Admin\SettingsController::class, 'system'])->name('settings.system');
    Route::get('/settings/logs', [\App\Http\Controllers\Admin\SettingsController::class, 'logs'])->name('settings.logs');
    Route::post('/settings/logs/clear', [\App\Http\Controllers\Admin\SettingsController::class, 'clearLogs'])->name('settings.clear-logs');
    Route::post('/settings/clear-cache', [\App\Http\Controllers\Admin\SettingsController::class, 'clearCache'])->name('settings.clear-cache');
    Route::post('/settings/run-migrations', [\App\Http\Controllers\Admin\SettingsController::class, 'runMigrations'])->name('settings.run-migrations');
    Route::post('/settings/backup-database', [\App\Http\Controllers\Admin\SettingsController::class, 'backupDatabase'])->name('settings.backup-database');
    Route::post('/settings/test-renewals', [\App\Http\Controllers\Admin\SettingsController::class, 'testRenewalReminders'])->name('settings.test-renewals');
});

// Profile routes (admin only)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});





require __DIR__ . '/auth.php';
