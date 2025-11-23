<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PricingPlan;
use App\Models\ResellerCreditPack;
use App\Models\CustomProduct;
use App\Models\User;
use Illuminate\Support\Str;

class WordPressIntegrationController extends Controller
{
    /**
     * Get all products for the authenticated user (admin)
     * Single-user version - returns all active products without admin_id filtering
     */
    public function getProducts(Request $request)
    {
        $user = $request->user();
        
        if (!$user || !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 401);
        }

        // Get source from token (optional)
        $token = $request->user()->currentAccessToken();
        $sourceName = null;
        if ($token && isset($token->source_id)) {
            $source = \App\Models\Source::find($token->source_id);
            if ($source) {
                $sourceName = $source->name;
            }
        }

        // Get pricing plans (single-user - no admin_id filtering)
        $pricingPlans = PricingPlan::active()->get()
            ->map(function($plan) use ($sourceName) {
                $checkoutUrl = route('checkout.show', ['plan_id' => $plan->id]);
                if ($sourceName) {
                    $checkoutUrl .= (strpos($checkoutUrl, '?') !== false ? '&' : '?') . 'source=' . urlencode($sourceName);
                }
                
                return [
                    'id' => $plan->id,
                    'type' => 'pricing_plan',
                    'name' => $plan->name,
                    'price' => $plan->price,
                    'formatted_price' => '$' . number_format($plan->price, 2),
                    'server_type' => $plan->server_type ?? null,
                    'plan_type' => $plan->plan_type ?? null,
                    'device_count' => $plan->device_count ?? null,
                    'duration_months' => $plan->duration_months ?? null,
                    'features' => $plan->features ?? [],
                    'payment_link' => $plan->payment_link ?? null,
                    'checkout_url' => $checkoutUrl,
                ];
            });

        // Get credit packs (single-user - no admin_id filtering)
        $creditPacks = ResellerCreditPack::active()->get()
            ->map(function($pack) use ($sourceName) {
                $checkoutUrl = route('reseller.checkout.show');
                if ($sourceName) {
                    $checkoutUrl .= (strpos($checkoutUrl, '?') !== false ? '&' : '?') . 'source=' . urlencode($sourceName);
                }
                
                return [
                    'id' => $pack->id,
                    'type' => 'credit_pack',
                    'name' => $pack->name,
                    'price' => $pack->price,
                    'formatted_price' => '$' . number_format($pack->price, 2),
                    'credits_amount' => $pack->credits_amount ?? null,
                    'features' => $pack->features ?? [],
                    'payment_methods' => $pack->payment_methods ?? [],
                    'checkout_url' => $checkoutUrl,
                ];
            });

        // Get custom products (single-user - no admin_id filtering)
        $customProducts = CustomProduct::active()->get()
            ->map(function($product) use ($sourceName) {
                $checkoutUrl = route('custom-product.checkout.show', ['product' => $product->slug]);
                if ($sourceName) {
                    $checkoutUrl .= (strpos($checkoutUrl, '?') !== false ? '&' : '?') . 'source=' . urlencode($sourceName);
                }
                
                return [
                    'id' => $product->id,
                    'type' => 'custom_product',
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->price,
                    'formatted_price' => '$' . number_format($product->price, 2),
                    'short_description' => $product->short_description ?? null,
                    'description' => $product->description ?? null,
                    'product_type' => $product->product_type ?? null,
                    'custom_fields' => $product->custom_fields ?? [],
                    'checkout_url' => $checkoutUrl,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'pricing_plans' => $pricingPlans,
                'credit_packs' => $creditPacks,
                'custom_products' => $customProducts,
            ],
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ]);
    }

    /**
     * Generate API token for WordPress integration
     */
    public function generateToken(Request $request)
    {
        $user = $request->user();
        
        if (!$user || !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 401);
        }

        // Create new token
        $token = $user->createToken('wordpress-integration-' . Str::random(8), ['wordpress:read'])->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'message' => 'API token generated successfully. Store this securely in your WordPress plugin settings.'
        ]);
    }

    /**
     * Get current API tokens
     */
    public function getTokens(Request $request)
    {
        $user = $request->user();
        
        if (!$user || !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 401);
        }

        $tokens = $user->tokens()->get()->map(function($token) {
            return [
                'id' => $token->id,
                'name' => $token->name,
                'abilities' => $token->abilities,
                'last_used_at' => $token->last_used_at,
                'created_at' => $token->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'tokens' => $tokens
        ]);
    }

    /**
     * Revoke an API token
     */
    public function revokeToken(Request $request, $tokenId)
    {
        $user = $request->user();
        
        if (!$user || !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 401);
        }

        $token = $user->tokens()->find($tokenId);
        
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found.'
            ], 404);
        }

        $token->delete();

        return response()->json([
            'success' => true,
            'message' => 'Token revoked successfully.'
        ]);
    }
}

