<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Affiliate;
use App\Models\Order;
use App\Models\User;
use App\Services\AffiliateService;
use Illuminate\Support\Facades\RateLimiter;

class AffiliateController extends Controller
{
    protected $affiliateService;

    public function __construct(AffiliateService $affiliateService)
    {
        $this->affiliateService = $affiliateService;
    }

    /**
     * Register affiliate (API endpoint for Next.js)
     */
    public function register(Request $request)
    {
        // Rate limiting: 5 attempts per hour per IP
        $key = 'affiliate_registration:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => false,
                'message' => "Too many registration attempts. Please try again in {$seconds} seconds.",
            ], 429);
        }

        RateLimiter::hit($key, 3600); // 1 hour

        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'selected_order_id' => 'required|exists:orders,id',
        ]);

        $email = strtolower($validated['email']);

        // Check if affiliate already exists
        $existingAffiliate = Affiliate::whereRaw('LOWER(email) = ?', [$email])->first();
        
        if ($existingAffiliate) {
            return response()->json([
                'success' => false,
                'message' => 'An affiliate account already exists for this email address.',
            ], 422);
        }

        // Validate subscription ownership
        if (!$this->affiliateService->validateSubscriptionOwnership($email, $validated['selected_order_id'])) {
            return response()->json([
                'success' => false,
                'message' => 'The selected subscription does not belong to this email address or is not active.',
            ], 422);
        }

        // Get the order to verify it's a subscription
        $order = Order::findOrFail($validated['selected_order_id']);
        if ($order->order_type !== 'subscription') {
            return response()->json([
                'success' => false,
                'message' => 'Only subscription orders can be used for affiliate rewards.',
            ], 422);
        }

        // Check if order is active
        if (!$order->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'The selected subscription must be active.',
            ], 422);
        }

        // Find or get user
        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

        // Create affiliate
        $affiliate = Affiliate::create([
            'user_id' => $user?->id,
            'email' => $email,
            'selected_order_id' => $validated['selected_order_id'],
            'is_active' => true,
        ]);

        \Log::info('Affiliate registered via API', [
            'affiliate_id' => $affiliate->id,
            'email' => $email,
            'selected_order_id' => $validated['selected_order_id'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Affiliate account created successfully!',
            'data' => [
                'affiliate_id' => $affiliate->id,
                'email' => $affiliate->email,
                'referral_code' => $affiliate->referral_code,
                'referral_link' => $affiliate->referral_link,
            ],
        ]);
    }

    /**
     * Get affiliate dashboard data (API endpoint for Next.js)
     */
    public function dashboard(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string',
        ]);

        $email = strtolower($request->input('email'));
        $referralCode = strtoupper($request->input('code'));

        $affiliate = Affiliate::whereRaw('LOWER(email) = ?', [$email])
            ->where('referral_code', $referralCode)
            ->with(['selectedOrder.pricingPlan', 'referrals.order', 'referrals.referredUser', 'referrals.grantedBy'])
            ->first();

        if (!$affiliate) {
            return response()->json([
                'success' => false,
                'message' => 'Affiliate not found. Please check your email and referral code.',
            ], 404);
        }

        $referrals = $affiliate->referrals()->with(['order', 'referredUser', 'grantedBy'])
            ->latest()
            ->get()
            ->map(function($referral) {
                return [
                    'id' => $referral->id,
                    'order_number' => $referral->order->order_number ?? null,
                    'customer_name' => $referral->referredUser->name ?? 'N/A',
                    'customer_email' => $referral->referredUser->email ?? 'N/A',
                    'status' => $referral->status,
                    'reward_granted' => $referral->reward_granted,
                    'reward_granted_at' => $referral->reward_granted_at ? $referral->reward_granted_at->format('Y-m-d H:i:s') : null,
                    'created_at' => $referral->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'affiliate_id' => $affiliate->id,
                'email' => $affiliate->email,
                'referral_code' => $affiliate->referral_code,
                'referral_link' => $affiliate->referral_link,
                'selected_subscription' => [
                    'order_number' => $affiliate->selectedOrder->order_number ?? null,
                    'plan_name' => $affiliate->selectedOrder->pricingPlan->display_name ?? 'N/A',
                    'expires_at' => $affiliate->selectedOrder->expires_at ? $affiliate->selectedOrder->expires_at->format('Y-m-d') : null,
                ],
                'total_referrals' => $affiliate->total_referrals,
                'total_rewards_earned' => $affiliate->total_rewards_earned,
                'pending_rewards_count' => $affiliate->pending_rewards_count,
                'is_active' => $affiliate->is_active,
                'referrals' => $referrals,
            ],
        ]);
    }

    /**
     * Fetch subscriptions by email (API endpoint for Next.js)
     */
    public function fetchSubscriptions(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');
        $subscriptions = $this->affiliateService->getSubscriptionsByEmail($email);

        // Format subscriptions for JSON response
        $formattedSubscriptions = $subscriptions->map(function($subscription) {
            return [
                'id' => $subscription->id,
                'order_number' => $subscription->order_number,
                'plan_name' => $subscription->pricingPlan->display_name ?? 'Subscription',
                'status' => $subscription->status,
                'is_active' => $subscription->isActive(),
                'is_expired' => $subscription->isExpired(),
                'expires_at' => $subscription->expires_at ? $subscription->expires_at->format('M d, Y') : null,
                'days_until_expiry' => $subscription->daysUntilExpiry(),
            ];
        });

        return response()->json([
            'success' => true,
            'subscriptions' => $formattedSubscriptions,
        ]);
    }
}
