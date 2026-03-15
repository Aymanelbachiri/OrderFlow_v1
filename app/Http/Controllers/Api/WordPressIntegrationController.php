<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PricingPlan;
use App\Models\ResellerCreditPack;
use App\Models\CustomProduct;
use App\Models\User;
use App\Models\Source;
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

        // Get source from token (required for iframe access)
        $token = $request->user()->currentAccessToken();
        $sourceName = null;
        if ($token && $token->source_id) {
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
                $checkoutUrl = route('reseller.checkout.show', ['plan_id' => $pack->id]);
                if ($sourceName) {
                    $checkoutUrl .= '&source=' . urlencode($sourceName);
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

        $validated = $request->validate([
            'token_name' => 'nullable|string|max:255',
            'source_id' => 'nullable|exists:sources,id',
        ]);

        $tokenName = $validated['token_name'] ?? 'wordpress-integration-' . Str::random(8);
        
        // Create new token
        $token = $user->createToken($tokenName, ['wordpress:read']);
        
        // Attach source if provided
        if (!empty($validated['source_id'])) {
            $token->accessToken->source_id = $validated['source_id'];
            $token->accessToken->save();
        }
        
        $plainTextToken = $token->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $plainTextToken,
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

        $tokens = $user->tokens()->with('source')->get()->map(function($token) {
            return [
                'id' => $token->id,
                'name' => $token->name,
                'abilities' => $token->abilities,
                'last_used_at' => $token->last_used_at,
                'created_at' => $token->created_at,
                'source_id' => $token->source_id,
                'source' => $token->source ? [
                    'id' => $token->source->id,
                    'name' => $token->source->name,
                ] : null,
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

