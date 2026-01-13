<?php

namespace App\Http\Controllers;

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
     * Show affiliate registration page
     */
    public function register()
    {
        return view('affiliate.register');
    }

    /**
     * Store affiliate registration
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'selected_order_id' => 'required|exists:orders,id',
            'selected_device_id' => 'nullable|string|max:255',
        ]);

        $email = strtolower($validated['email']);

        // Check if affiliate already exists
        $existingAffiliate = Affiliate::whereRaw('LOWER(email) = ?', [$email])->first();
        
        if ($existingAffiliate) {
            return back()
                ->withErrors(['email' => 'An affiliate account already exists for this email address.'])
                ->withInput();
        }

        // Validate subscription ownership
        if (!$this->affiliateService->validateSubscriptionOwnership($email, $validated['selected_order_id'])) {
            return back()
                ->withErrors(['selected_order_id' => 'The selected subscription does not belong to this email address or is not active.'])
                ->withInput();
        }

        // Get the order to verify it's a subscription
        $order = Order::findOrFail($validated['selected_order_id']);
        if ($order->order_type !== 'subscription') {
            return back()
                ->withErrors(['selected_order_id' => 'Only subscription orders can be used for affiliate rewards.'])
                ->withInput();
        }

        // Check if order is active
        if (!$order->isActive()) {
            return back()
                ->withErrors(['selected_order_id' => 'The selected subscription must be active.'])
                ->withInput();
        }

        // Find or get user
        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

        // Create affiliate
        $affiliate = Affiliate::create([
            'user_id' => $user?->id,
            'email' => $email,
            'selected_order_id' => $validated['selected_order_id'],
            'selected_device_id' => $validated['selected_device_id'] ?? null,
            'is_active' => true,
        ]);

        \Log::info('Affiliate registered', [
            'affiliate_id' => $affiliate->id,
            'email' => $email,
            'selected_order_id' => $validated['selected_order_id'],
        ]);

        return redirect()->route('affiliate.dashboard')
            ->with('success', 'Affiliate account created successfully!')
            ->with('affiliate_email', $email)
            ->with('referral_code', $affiliate->referral_code);
    }

    /**
     * Show affiliate dashboard (public access)
     */
    public function dashboard(Request $request)
    {
        $email = $request->query('email');

        // If email provided, try to find affiliate
        if ($email) {
            $affiliate = Affiliate::whereRaw('LOWER(email) = ?', [strtolower($email)])
                ->with(['selectedOrder.pricingPlan', 'referrals.order', 'referrals.referredUser'])
                ->first();

            if ($affiliate) {
                return view('affiliate.dashboard', compact('affiliate'));
            }
        }

        // Show lookup form
        return view('affiliate.dashboard');
    }

    /**
     * Fetch subscriptions by email (AJAX endpoint)
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
                'devices' => $subscription->devices ?? [],
                'has_multiple_devices' => is_array($subscription->devices) && count($subscription->devices) > 1,
            ];
        });

        return response()->json([
            'success' => true,
            'subscriptions' => $formattedSubscriptions,
        ]);
    }
}
