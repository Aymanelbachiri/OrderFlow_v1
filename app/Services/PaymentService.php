<?php

namespace App\Services;

use App\Models\SystemSetting;

class PaymentService
{
    /**
     * Get available payment methods based on admin configuration
     */
    public static function getAvailablePaymentMethods(): array
    {
        $primaryMethod = SystemSetting::get('primary_payment_method', 'paypal');

        // If primary method is not 'multiple', return only that method if configured
        if ($primaryMethod !== 'multiple') {
            if (self::isPaymentMethodConfigured($primaryMethod)) {
                return [self::getPaymentMethodConfig($primaryMethod)];
            }
            // If primary method is not configured, fall back to multiple mode
        }

        // If 'multiple' is selected, return all properly configured methods
        $methods = [];

        // Check each payment method if enabled and configured
        if (self::isStripeConfigured()) {
            $methods[] = self::getPaymentMethodConfig('stripe');
        }

        if (self::isPayPalConfigured()) {
            $methods[] = self::getPaymentMethodConfig('paypal');
        }

        if (self::isCryptoConfigured()) {
            $methods[] = self::getPaymentMethodConfig('crypto');
        }

        if (self::isCoinbaseCommerceConfigured()) {
            $methods[] = self::getPaymentMethodConfig('coinbase_commerce');
        }

        return $methods;
    }
    
    /**
     * Get payment method configuration
     */
    private static function getPaymentMethodConfig(string $method): array
    {
        $configs = [
            'stripe' => [
                'key' => 'stripe',
                'name' => 'Stripe',
                'description' => 'Stripe',
                'icon' => 'credit-card',
                'enabled' => self::isStripeConfigured(),
                'configured' => self::isStripeConfigured(),
            ],
            'paypal' => [
                'key' => 'paypal',
                'name' => 'PayPal',
                'description' => 'Secure payment',
                'icon' => 'paypal',
                'enabled' => self::isPayPalConfigured(),
                'configured' => self::isPayPalConfigured(),
            ],
            'crypto' => [
                'key' => 'crypto',
                'name' => 'USDT(TRC20)',
                'description' => 'USDT TRC20',
                'icon' => 'crypto',
                'enabled' => self::isCryptoConfigured(),
                'configured' => self::isCryptoConfigured(),
            ],
            'coinbase_commerce' => [
                'key' => 'coinbase_commerce',
                'name' => 'Crypto',
                'description' => 'Pay with Crypto',
                'icon' => 'crypto',
                'enabled' => self::isCoinbaseCommerceConfigured(),
                'configured' => self::isCoinbaseCommerceConfigured(),
            ],
        ];

        return $configs[$method] ?? $configs['paypal'];
    }
    
    /**
     * Check if a payment method is available
     */
    public static function isPaymentMethodAvailable(string $method): bool
    {
        $availableMethods = self::getAvailablePaymentMethods();
        
        foreach ($availableMethods as $availableMethod) {
            if ($availableMethod['key'] === $method && $availableMethod['enabled']) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get the first available payment method (for default selection)
     */
    public static function getDefaultPaymentMethod(): string
    {
        $availableMethods = self::getAvailablePaymentMethods();
        return $availableMethods[0]['key'] ?? 'paypal';
    }

    /**
     * Check if a specific payment method is properly configured
     */
    public static function isPaymentMethodConfigured(string $method): bool
    {
        return match($method) {
            'stripe' => self::isStripeConfigured(),
            'paypal' => self::isPayPalConfigured(),
            'crypto' => self::isCryptoConfigured(),
            'coinbase_commerce' => self::isCoinbaseCommerceConfigured(),
            default => false,
        };
    }
    
    /**
     * Validate payment method selection
     */
    public static function validatePaymentMethod(string $method): bool
    {
        return self::isPaymentMethodAvailable($method);
    }
    
    /**
     * Get payment method configuration for validation rules
     */
    public static function getPaymentMethodValidationRules(): string
    {
        // Accept common methods regardless of configuration; downstream controllers will enforce availability
        return 'required|in:stripe,paypal,crypto,coinbase_commerce,bank_transfer';
    }
    
    /**
     * Check if Stripe is properly configured
     */
    public static function isStripeConfigured(): bool
    {
        return SystemSetting::get('stripe_enabled', false) &&
               !empty(SystemSetting::get('stripe_public_key', '')) &&
               !empty(SystemSetting::get('stripe_secret_key', ''));
    }
    
    /**
     * Check if PayPal is properly configured
     */
    public static function isPayPalConfigured(): bool
    {
        $mode = SystemSetting::get('paypal_mode', 'sandbox');
        
        if ($mode === 'sandbox') {
            return SystemSetting::get('paypal_enabled', false) &&
                   !empty(SystemSetting::get('paypal_sandbox_client_id', '')) &&
                   !empty(SystemSetting::get('paypal_sandbox_client_secret', ''));
        } else {
            return SystemSetting::get('paypal_enabled', false) &&
                   !empty(SystemSetting::get('paypal_live_client_id', '')) &&
                   !empty(SystemSetting::get('paypal_live_client_secret', ''));
        }
    }
    
    /**
     * Get PayPal Client ID based on current mode
     */
    public static function getPayPalClientId(): string
    {
        $mode = SystemSetting::get('paypal_mode', 'sandbox');
        
        if ($mode === 'sandbox') {
            return SystemSetting::get('paypal_sandbox_client_id', '');
        } else {
            return SystemSetting::get('paypal_live_client_id', '');
        }
    }
    
    /**
     * Get PayPal Client Secret based on current mode
     */
    public static function getPayPalClientSecret(): string
    {
        $mode = SystemSetting::get('paypal_mode', 'sandbox');
        
        if ($mode === 'sandbox') {
            return SystemSetting::get('paypal_sandbox_client_secret', '');
        } else {
            return SystemSetting::get('paypal_live_client_secret', '');
        }
    }
    
    /**
     * Check if Crypto is properly configured
     */
    public static function isCryptoConfigured(): bool
    {
        return SystemSetting::get('crypto_enabled', false) &&
               !empty(SystemSetting::get('crypto_wallet_address', ''));
    }
    
    /**
     * Check if Coinbase Commerce is properly configured
     */
    public static function isCoinbaseCommerceConfigured(): bool
    {
        return SystemSetting::get('coinbase_commerce_enabled', false) &&
               !empty(SystemSetting::get('coinbase_commerce_api_key', '')) &&
               !empty(SystemSetting::get('coinbase_commerce_webhook_secret', ''));
    }
    
    /**
     * Get Coinbase Commerce API Key
     */
    public static function getCoinbaseCommerceApiKey(): string
    {
        return SystemSetting::get('coinbase_commerce_api_key', '');
    }
    
    /**
     * Get Coinbase Commerce Webhook Secret
     */
    public static function getCoinbaseCommerceWebhookSecret(): string
    {
        return SystemSetting::get('coinbase_commerce_webhook_secret', '');
    }
    
    /**
     * Create a Coinbase Commerce charge
     */
    public static function createCoinbaseCommerceCharge(\App\Models\PaymentIntent $internalIntent): array
    {
        $apiKey = self::getCoinbaseCommerceApiKey();
        if (empty($apiKey)) {
            \Log::error('Coinbase Commerce: API key not configured', [
                'payment_intent_id' => $internalIntent->id,
            ]);
            throw new \RuntimeException('Coinbase Commerce is not configured');
        }

        // Force USD only for Coinbase Commerce
        // Ensure amount is a numeric value (cast to float to handle decimal strings)
        $amount = (float) ($internalIntent->amount ?? 0);
        $currency = 'USD';
        
        // Ensure minimum amount (Coinbase Commerce minimum is typically $0.01)
        if ($amount < 0.01) {
            \Log::error('Coinbase Commerce: Amount too small', [
                'payment_intent_id' => $internalIntent->id,
                'amount' => $amount,
            ]);
            throw new \RuntimeException('Amount must be at least $0.01');
        }
        
        // Coinbase Commerce requires amount as a decimal string with exactly 2 decimal places for USD
        // Format: "10.00" (not "10" or "10.0" or 10 as number)
        // Use number_format to ensure exactly 2 decimal places
        $amountString = number_format($amount, 2, '.', '');
        
        // Validate amount string format
        if (!preg_match('/^\d+\.\d{2}$/', $amountString)) {
            \Log::error('Coinbase Commerce: Invalid amount format', [
                'payment_intent_id' => $internalIntent->id,
                'original_amount' => $internalIntent->amount,
                'formatted_amount' => $amountString,
            ]);
            throw new \RuntimeException('Invalid amount format');
        }

        // Minimal metadata - only essential payment tracking info
        $metadata = [
            'payment_intent_id' => (string) $internalIntent->id,
        ];

        // Generic name and description - no product details
        $name = 'Digital Services';
        $description = 'Payment for digital services';
        
        // Coinbase Commerce expects amount as a string with exactly 2 decimal places
        // Ensure it's a string, not a number, to avoid JSON encoding issues
        $chargeData = [
            'name' => $name,
            'description' => $description,
            'pricing_type' => 'fixed_price',
            'local_price' => [
                'amount' => (string) $amountString, // Explicitly cast to string
                'currency' => $currency,
            ],
            'metadata' => $metadata,
            'redirect_url' => route('public.payment.coinbase-commerce.success', $internalIntent),
            'cancel_url' => route('public.payment.coinbase-commerce.cancel', $internalIntent),
        ];

        // Log the request data for debugging
        \Log::info('Coinbase Commerce: Creating charge', [
            'payment_intent_id' => $internalIntent->id,
            'amount' => $amount,
            'amount_string' => $amountString,
            'currency' => $currency,
            'charge_data' => $chargeData,
        ]);

        // Make API call to Coinbase Commerce
        $url = 'https://api.commerce.coinbase.com/charges';
        $requestBody = json_encode($chargeData);
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-CC-Api-Key: ' . $apiKey,
            'X-CC-Version: 2018-03-22',
        ]);
        
        // SSL verification - only disable in local/dev environments
        $isLocal = app()->environment('local') || app()->environment('development');
        
        if ($isLocal) {
            // Disable SSL verification in local/development only
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        } else {
            // Production: Try to find CA bundle for SSL verification
            $caBundleFound = false;
            $caBundlePaths = [
                '/etc/ssl/certs/ca-certificates.crt', // Linux
                '/etc/pki/tls/certs/ca-bundle.crt', // CentOS/RHEL
                '/usr/local/etc/openssl/cert.pem', // macOS
                __DIR__ . '/../../vendor/composer/ca-bundle/ca-bundle.crt', // Composer CA bundle
            ];
            
            foreach ($caBundlePaths as $caPath) {
                if (file_exists($caPath)) {
                    curl_setopt($ch, CURLOPT_CAINFO, $caPath);
                    $caBundleFound = true;
                    break;
                }
            }
            
            // In production, always verify SSL (CA bundle should be available)
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        }
        
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        // Check for cURL errors first (http_code 0 usually means connection failed)
        if ($curlError || $httpCode === 0) {
            \Log::error('Coinbase Commerce: cURL error occurred', [
                'payment_intent_id' => $internalIntent->id,
                'curl_error' => $curlError,
                'http_code' => $httpCode,
            ]);
            
            throw new \RuntimeException('Network error connecting to Coinbase Commerce');
        }

        if ($httpCode !== 201) {
            // Try to parse error response
            $errorResponse = json_decode($response, true);
            $errorMessage = 'Failed to create Coinbase Commerce charge';
            if (isset($errorResponse['error']['message'])) {
                $errorMessage .= '. ' . $errorResponse['error']['message'];
            }
            
            \Log::error('Coinbase Commerce charge creation failed', [
                'payment_intent_id' => $internalIntent->id,
                'http_code' => $httpCode,
                'response' => $response,
                'request_data' => [
                    'amount' => $amountString,
                    'currency' => $currency,
                    'original_amount' => $internalIntent->amount,
                ],
            ]);
            throw new \RuntimeException('Failed to create Coinbase Commerce charge. HTTP ' . $httpCode . ': ' . $errorMessage);
        }

        $charge = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            \Log::error('Coinbase Commerce: JSON decode error', [
                'payment_intent_id' => $internalIntent->id,
            ]);
            throw new \RuntimeException('Invalid JSON response from Coinbase Commerce');
        }

        if (!isset($charge['data']['hosted_url']) || !isset($charge['data']['code'])) {
            \Log::error('Coinbase Commerce: Missing required fields in response', [
                'payment_intent_id' => $internalIntent->id,
            ]);
            throw new \RuntimeException('Invalid response structure from Coinbase Commerce');
        }
        
        return [
            'charge' => $charge,
            'hosted_url' => $charge['data']['hosted_url'],
            'charge_id' => $charge['data']['code'],
        ];
    }

    /**
     * Get Coinbase Commerce charge status by charge code
     * Returns charge data with status (NEW, PENDING, COMPLETED, EXPIRED, UNRESOLVED, RESOLVED, CANCELED)
     */
    public static function getCoinbaseCommerceChargeStatus(string $chargeCode): ?array
    {
        $apiKey = self::getCoinbaseCommerceApiKey();
        if (empty($apiKey)) {
            \Log::error('Coinbase Commerce: API key not configured for status check', [
                'charge_code' => $chargeCode,
            ]);
            return null;
        }

        $url = 'https://api.commerce.coinbase.com/charges/' . $chargeCode;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-CC-Api-Key: ' . $apiKey,
            'X-CC-Version: 2018-03-22',
        ]);

        // SSL verification - only disable in local/dev environments
        $isLocal = app()->environment('local') || app()->environment('development');
        
        if ($isLocal) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        } else {
            // Production: Try to find CA bundle for SSL verification
            $caBundlePaths = [
                '/etc/ssl/certs/ca-certificates.crt',
                '/etc/pki/tls/certs/ca-bundle.crt',
                '/usr/local/etc/openssl/cert.pem',
                __DIR__ . '/../../vendor/composer/ca-bundle/ca-bundle.crt',
            ];
            
            foreach ($caBundlePaths as $caPath) {
                if (file_exists($caPath)) {
                    curl_setopt($ch, CURLOPT_CAINFO, $caPath);
                    break;
                }
            }
            
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        }
        
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($response === false || $httpCode === 0) {
            \Log::error('Coinbase Commerce: Failed to check charge status', [
                'charge_code' => $chargeCode,
                'http_code' => $httpCode,
                'curl_error' => $curlError,
            ]);
            return null;
        }

        if ($httpCode !== 200) {
            \Log::warning('Coinbase Commerce: Charge status check returned non-200', [
                'charge_code' => $chargeCode,
                'http_code' => $httpCode,
            ]);
            return null;
        }

        $charge = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            \Log::error('Coinbase Commerce: JSON decode error in status check', [
                'charge_code' => $chargeCode,
            ]);
            return null;
        }

        return $charge['data'] ?? null;
    }
    
    /**
     * Create a Stripe PaymentIntent for the given internal PaymentIntent model.
     * Returns an array: [stripePaymentIntentObject, clientSecret]
     */
    public static function createStripePaymentIntent(\App\Models\PaymentIntent $internalIntent): array
    {
        $publicKey = SystemSetting::get('stripe_public_key', '');
        $secretKey = SystemSetting::get('stripe_secret_key', '');
        if (empty($publicKey) || empty($secretKey)) {
            throw new \RuntimeException('Stripe is not configured');
        }

        // If Stripe PHP SDK is available, create a real intent
        if (class_exists('Stripe\\Stripe')) {
            \Stripe\Stripe::setApiKey($secretKey);

            $currency = strtolower($internalIntent->currency ?? 'usd');
            $amountInCents = (int) round(($internalIntent->amount ?? 0) * 100);
            $metadata = [
                'payment_intent_id' => (string) $internalIntent->id,
                'app_payment_method' => (string) $internalIntent->payment_method,
                'order_type' => 'web_service',
                'user_id' => (string) $internalIntent->user_id,
                'service_id' => (string) $internalIntent->pricing_plan_id,
            ];

            // Get or create Stripe Customer for the user (lowers risk score)
            $stripeCustomerId = null;
            $receiptEmail = null;
            
            if ($internalIntent->user) {
                $user = $internalIntent->user;
                $receiptEmail = $user->email;
                
                // Get existing Stripe customer ID or create new one
                if ($user->stripe_id) {
                    $stripeCustomerId = $user->stripe_id;
                } else {
                    // Create new Stripe Customer
                    try {
                        $stripeCustomer = \Stripe\Customer::create([
                            'email' => $user->email,
                            'name' => $user->name,
                            'phone' => $user->phone,
                            'metadata' => [
                                'user_id' => (string) $user->id,
                                'app_user_email' => $user->email,
                            ],
                        ]);
                        
                        $stripeCustomerId = $stripeCustomer->id;
                        
                        // Save Stripe customer ID to user
                        $user->update(['stripe_id' => $stripeCustomerId]);
                    } catch (\Exception $e) {
                        // If customer creation fails, continue without customer
                        // This allows payment to proceed even if customer creation fails
                        \Log::warning('Failed to create Stripe customer', [
                            'user_id' => $user->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            }

            // Build PaymentIntent parameters
            $paymentIntentParams = [
                'amount' => max($amountInCents, 50), // minimum amount safeguard
                'currency' => $currency,
                'metadata' => $metadata,
                'description' => 'Web Service',
                'automatic_payment_methods' => ['enabled' => true],
                // Note: statement_descriptor is not supported with automatic_payment_methods for cards
                // Statement descriptor is controlled by your Stripe account settings instead
            ];

            // Configure payment method options for better authentication (lowers risk score)
            $paymentIntentParams['payment_method_options'] = [
                'card' => [
                    // Request 3D Secure when needed - helps with authentication and lowers risk
                    'request_three_d_secure' => 'automatic', // 'automatic', 'any', or 'challenge'
                    // Enable network tokenization for better success rates
                    'network' => null, // Let Stripe choose best network
                ],
            ];

            // Attach customer if available (significantly lowers risk score)
            if ($stripeCustomerId) {
                $paymentIntentParams['customer'] = $stripeCustomerId;
                
                // Update customer information if it has changed (keeps data fresh)
                try {
                    $stripeCustomer = \Stripe\Customer::retrieve($stripeCustomerId);
                    $needsUpdate = false;
                    $updateData = [];
                    
                    if ($internalIntent->user) {
                        $user = $internalIntent->user;
                        
                        // Update email if changed
                        if ($user->email && $stripeCustomer->email !== $user->email) {
                            $updateData['email'] = $user->email;
                            $needsUpdate = true;
                        }
                        
                        // Update name if changed
                        if ($user->name && $stripeCustomer->name !== $user->name) {
                            $updateData['name'] = $user->name;
                            $needsUpdate = true;
                        }
                        
                        // Update phone if changed
                        if ($user->phone && $stripeCustomer->phone !== $user->phone) {
                            $updateData['phone'] = $user->phone;
                            $needsUpdate = true;
                        }
                        
                        if ($needsUpdate) {
                            \Stripe\Customer::update($stripeCustomerId, $updateData);
                        }
                    }
                } catch (\Exception $e) {
                    // If update fails, continue with payment - non-critical
                    \Log::warning('Failed to update Stripe customer', [
                        'customer_id' => $stripeCustomerId,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // Add receipt email if available (lowers risk score)
            if ($receiptEmail) {
                $paymentIntentParams['receipt_email'] = $receiptEmail;
            }

            $stripeIntent = \Stripe\PaymentIntent::create($paymentIntentParams);

            return [$stripeIntent, $stripeIntent->client_secret];
        }

        // Fallback: return a lightweight object to avoid hard failure in non-Stripe environments
        $fake = (object) [
            'id' => 'pi_fake_' . uniqid(),
            'client_secret' => 'secret_fake_' . uniqid(),
            'amount' => $internalIntent->amount,
            'currency' => $internalIntent->currency ?? 'USD',
            'status' => 'requires_payment_method',
        ];
        return [$fake, $fake->client_secret];
    }
    
    /**
     * Get payment method status for admin dashboard
     */
    public static function getPaymentMethodStatus(): array
    {
        return [
            'stripe' => [
                'enabled' => SystemSetting::get('stripe_enabled', false),
                'configured' => self::isStripeConfigured(),
                'status' => self::isStripeConfigured() ? 'active' : 'needs_config',
            ],
            'paypal' => [
                'enabled' => SystemSetting::get('paypal_enabled', false),
                'configured' => self::isPayPalConfigured(),
                'status' => self::isPayPalConfigured() ? 'active' : 'needs_config',
            ],
            'crypto' => [
                'enabled' => SystemSetting::get('crypto_enabled', false),
                'configured' => self::isCryptoConfigured(),
                'status' => self::isCryptoConfigured() ? 'active' : 'needs_config',
            ],
            'coinbase_commerce' => [
                'enabled' => SystemSetting::get('coinbase_commerce_enabled', false),
                'configured' => self::isCoinbaseCommerceConfigured(),
                'status' => self::isCoinbaseCommerceConfigured() ? 'active' : 'needs_config',
            ],
        ];
    }
}
