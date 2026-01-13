<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\AffiliateReferral;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AffiliateService
{
    /**
     * Validate referral code
     * 
     * @param string $referralCode
     * @param string $customerEmail
     * @return array ['valid' => bool, 'affiliate' => Affiliate|null, 'message' => string]
     */
    public function validateReferralCode($referralCode, $customerEmail)
    {
        // Check format (alphanumeric, 8-12 chars)
        if (!preg_match('/^[A-Z0-9]{8,12}$/i', $referralCode)) {
            return [
                'valid' => false,
                'affiliate' => null,
                'message' => 'Invalid referral code format.'
            ];
        }

        // Find affiliate by referral code
        $affiliate = Affiliate::where('referral_code', strtoupper($referralCode))->first();

        if (!$affiliate) {
            return [
                'valid' => false,
                'affiliate' => null,
                'message' => 'Referral code not found.'
            ];
        }

        // Check if affiliate is active
        if (!$affiliate->is_active) {
            return [
                'valid' => false,
                'affiliate' => $affiliate,
                'message' => 'This referral code is no longer active.'
            ];
        }

        // Prevent self-referral
        if (!$affiliate->canReferUser($customerEmail)) {
            return [
                'valid' => false,
                'affiliate' => $affiliate,
                'message' => 'You cannot use your own referral code.'
            ];
        }

        // Check if affiliate has active subscription
        if (!$affiliate->hasActiveSubscription()) {
            return [
                'valid' => false,
                'affiliate' => $affiliate,
                'message' => 'Affiliate subscription is not active.'
            ];
        }

        // Check if customer already has subscriptions (one-time use per customer)
        // If they do, silently skip the referral instead of blocking checkout
        $existingOrders = Order::where('order_type', 'subscription')
            ->whereHas('user', function($q) use ($customerEmail) {
                $q->whereRaw('LOWER(email) = ?', [strtolower($customerEmail)]);
            })
            ->exists();

        if ($existingOrders) {
            return [
                'valid' => true, // Allow checkout to proceed
                'affiliate' => $affiliate,
                'message' => 'Referral code is valid.',
                'skip_referral' => true, // Flag to skip creating referral record
            ];
        }

        // Check if this specific referral code has already been used by this customer
        // If already used, return as valid but mark it for silent skip
        $existingReferral = AffiliateReferral::whereHas('order', function($q) use ($customerEmail) {
                $q->whereHas('user', function($userQuery) use ($customerEmail) {
                    $userQuery->whereRaw('LOWER(email) = ?', [strtolower($customerEmail)]);
                });
            })
            ->whereHas('affiliate', function($q) use ($referralCode) {
                $q->where('referral_code', strtoupper($referralCode));
            })
            ->exists();

        if ($existingReferral) {
            return [
                'valid' => true, // Allow checkout to proceed
                'affiliate' => $affiliate,
                'message' => 'Referral code is valid.',
                'skip_referral' => true, // Flag to skip creating referral record
            ];
        }

        return [
            'valid' => true,
            'affiliate' => $affiliate,
            'message' => 'Referral code is valid.'
        ];
    }

    /**
     * Create affiliate referral record when order is activated
     * 
     * @param Order $order
     * @return AffiliateReferral|null
     */
    public function createReferral(Order $order)
    {
        // Check if order has referral code
        if (empty($order->referral_code)) {
            return null;
        }

        // Prevent duplicate referral creation
        if ($order->affiliate_referral_id) {
            Log::warning('Order already has affiliate referral', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'affiliate_referral_id' => $order->affiliate_referral_id,
            ]);
            return $order->affiliateReferral;
        }

        // Check if this referral code has already been used by this customer
        $existingReferral = AffiliateReferral::whereHas('order', function($q) use ($order) {
                $q->whereHas('user', function($userQuery) use ($order) {
                    $userQuery->whereRaw('LOWER(email) = ?', [strtolower($order->user->email)]);
                });
            })
            ->whereHas('affiliate', function($q) use ($order) {
                $q->where('referral_code', strtoupper($order->referral_code));
            })
            ->exists();

        if ($existingReferral) {
            Log::info('Referral code already used by customer, skipping referral creation', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'referral_code' => $order->referral_code,
                'customer_email' => $order->user->email,
            ]);
            return null;
        }

        // Validate referral code
        $validation = $this->validateReferralCode($order->referral_code, $order->user->email);
        
        if (!$validation['valid']) {
            Log::warning('Invalid referral code during order activation', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'referral_code' => $order->referral_code,
                'customer_email' => $order->user->email,
                'message' => $validation['message'],
            ]);
            return null;
        }

        $affiliate = $validation['affiliate'];

        try {
            DB::beginTransaction();

            // Create affiliate referral record
            $referral = AffiliateReferral::create([
                'affiliate_id' => $affiliate->id,
                'order_id' => $order->id,
                'referred_user_id' => $order->user_id,
                'status' => 'pending',
                'reward_granted' => false,
            ]);

            // Update order with referral ID
            $order->update([
                'affiliate_referral_id' => $referral->id,
            ]);

            // Increment affiliate total referrals count
            $affiliate->increment('total_referrals');

            DB::commit();

            Log::info('Affiliate referral created', [
                'referral_id' => $referral->id,
                'affiliate_id' => $affiliate->id,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ]);

            return $referral;

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to create affiliate referral', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'affiliate_id' => $affiliate->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * Grant reward to affiliate (extend subscription by 1 month)
     * 
     * @param AffiliateReferral $referral
     * @param int|null $adminId
     * @return bool
     */
    public function grantReward(AffiliateReferral $referral, $adminId = null)
    {
        try {
            // Use the model's grantReward method which handles all the logic
            $referral->grantReward($adminId);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to grant affiliate reward', [
                'referral_id' => $referral->id,
                'admin_id' => $adminId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }

    /**
     * Prevent duplicate rewards per order
     * 
     * @param Order $order
     * @return bool
     */
    public function preventDuplicateReward(Order $order)
    {
        // Check if order already has an affiliate referral
        if ($order->affiliate_referral_id) {
            $referral = $order->affiliateReferral;
            
            // If reward already granted, prevent duplicate
            if ($referral && $referral->reward_granted) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get subscriptions for an email address
     * 
     * @param string $email
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSubscriptionsByEmail($email)
    {
        return Order::where('order_type', 'subscription')
            ->where('status', '!=', 'cancelled')
            ->where('status', '!=', 'completed')
            ->whereHas('user', function($q) use ($email) {
                $q->whereRaw('LOWER(email) = ?', [strtolower($email)]);
            })
            ->with(['user', 'pricingPlan'])
            ->latest('expires_at')
            ->get();
    }

    /**
     * Validate subscription ownership for affiliate registration
     * 
     * @param string $email
     * @param int $orderId
     * @return bool
     */
    public function validateSubscriptionOwnership($email, $orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('order_type', 'subscription')
            ->whereHas('user', function($q) use ($email) {
                $q->whereRaw('LOWER(email) = ?', [strtolower($email)]);
            })
            ->first();

        if (!$order) {
            return false;
        }

        // Check if order is active or not expired
        return $order->status === 'active' && 
               ($order->expires_at === null || $order->expires_at > now());
    }
}
