<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Affiliate;
use App\Models\AffiliateReferral;
use App\Services\AffiliateService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AffiliateController extends Controller
{
    protected $affiliateService;

    public function __construct(AffiliateService $affiliateService)
    {
        $this->affiliateService = $affiliateService;
    }

    /**
     * Display a listing of affiliates
     */
    public function index(Request $request)
    {
        $query = Affiliate::with(['selectedOrder.pricingPlan', 'user']);

        // Filter by active status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Search by email or referral code
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }

        $affiliates = $query->latest()->paginate(20);

        // Get statistics
        $totalAffiliates = Affiliate::count();
        $activeAffiliates = Affiliate::where('is_active', true)->count();
        $totalReferrals = AffiliateReferral::count();
        $pendingRewards = AffiliateReferral::where('status', 'pending')
            ->where('reward_granted', false)
            ->count();

        return view('admin.affiliates.index', compact(
            'affiliates',
            'totalAffiliates',
            'activeAffiliates',
            'totalReferrals',
            'pendingRewards'
        ));
    }

    /**
     * Display the specified affiliate
     */
    public function show(Affiliate $affiliate)
    {
        $affiliate->load([
            'selectedOrder.pricingPlan',
            'user',
            'referrals.order',
            'referrals.referredUser',
            'referrals.grantedBy'
        ]);

        return view('admin.affiliates.show', compact('affiliate'));
    }

    /**
     * Approve and grant reward for a referral
     */
    public function approveReward(Request $request, Affiliate $affiliate, AffiliateReferral $referral)
    {
        // Verify referral belongs to affiliate
        if ($referral->affiliate_id !== $affiliate->id) {
            return redirect()->back()
                ->with('error', 'Referral does not belong to this affiliate.');
        }

        // Check if already granted
        if ($referral->reward_granted) {
            return redirect()->back()
                ->with('error', 'Reward has already been granted for this referral.');
        }

        try {
            // Approve the referral
            $referral->approve(auth()->id(), $request->input('notes'));

            // Grant the reward (extend subscription by 1 month)
            $this->affiliateService->grantReward($referral, auth()->id());

            Log::info('Affiliate reward approved and granted by admin', [
                'admin_id' => auth()->id(),
                'affiliate_id' => $affiliate->id,
                'referral_id' => $referral->id,
                'order_id' => $referral->order_id,
            ]);

            return redirect()->back()
                ->with('success', 'Reward approved and granted successfully. Affiliate subscription extended by 1 month.');

        } catch (\Exception $e) {
            Log::error('Failed to approve and grant affiliate reward', [
                'admin_id' => auth()->id(),
                'affiliate_id' => $affiliate->id,
                'referral_id' => $referral->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to grant reward: ' . $e->getMessage());
        }
    }

    /**
     * Reject a referral reward
     */
    public function rejectReward(Request $request, Affiliate $affiliate, AffiliateReferral $referral)
    {
        // Verify referral belongs to affiliate
        if ($referral->affiliate_id !== $affiliate->id) {
            return redirect()->back()
                ->with('error', 'Referral does not belong to this affiliate.');
        }

        // Check if already granted
        if ($referral->reward_granted) {
            return redirect()->back()
                ->with('error', 'Cannot reject a referral that has already been granted a reward.');
        }

        try {
            $referral->reject(auth()->id(), $request->input('notes'));

            Log::info('Affiliate reward rejected by admin', [
                'admin_id' => auth()->id(),
                'affiliate_id' => $affiliate->id,
                'referral_id' => $referral->id,
                'order_id' => $referral->order_id,
                'notes' => $request->input('notes'),
            ]);

            return redirect()->back()
                ->with('success', 'Referral reward rejected successfully.');

        } catch (\Exception $e) {
            Log::error('Failed to reject affiliate reward', [
                'admin_id' => auth()->id(),
                'affiliate_id' => $affiliate->id,
                'referral_id' => $referral->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to reject referral: ' . $e->getMessage());
        }
    }

    /**
     * Update affiliate status
     */
    public function update(Request $request, Affiliate $affiliate)
    {
        $validated = $request->validate([
            'is_active' => 'boolean',
        ]);

        $affiliate->update($validated);

        Log::info('Affiliate status updated', [
            'admin_id' => auth()->id(),
            'affiliate_id' => $affiliate->id,
            'is_active' => $affiliate->is_active,
        ]);

        return redirect()->back()
            ->with('success', 'Affiliate status updated successfully.');
    }

    /**
     * Grant direct reward to affiliate (create referral record and send email)
     */
    public function grantDirectReward(Request $request, Affiliate $affiliate)
    {
        // Check if affiliate is active and has active subscription
        if (!$affiliate->is_active) {
            return redirect()->back()
                ->with('error', 'Cannot grant reward to inactive affiliate.');
        }

        if (!$affiliate->selectedOrder || !$affiliate->selectedOrder->isActive()) {
            return redirect()->back()
                ->with('error', 'Affiliate does not have an active subscription.');
        }

        try {
            $selectedOrder = $affiliate->selectedOrder;

            // Check if referral record already exists for this order
            $referral = AffiliateReferral::where('order_id', $selectedOrder->id)->first();
            
            if ($referral) {
                // Update existing referral record
                if ($referral->reward_granted) {
                    return redirect()->back()
                        ->with('error', 'Reward has already been granted for this affiliate.');
                }
                
                $referral->update([
                    'status' => 'approved',
                    'reward_granted' => true,
                    'reward_granted_at' => now(),
                    'granted_by_admin_id' => auth()->id(),
                    'notes' => ($referral->notes ? $referral->notes . ' | ' : '') . 'Direct reward granted by admin',
                ]);
                
                Log::info('Existing referral record updated with reward', [
                    'referral_id' => $referral->id,
                    'order_id' => $selectedOrder->id,
                ]);
            } else {
                // Create new referral record for this direct reward
                $referral = AffiliateReferral::create([
                    'affiliate_id' => $affiliate->id,
                    'order_id' => $selectedOrder->id,
                    'referred_user_id' => $selectedOrder->user_id,
                    'status' => 'approved',
                    'reward_granted' => true,
                    'reward_granted_at' => now(),
                    'granted_by_admin_id' => auth()->id(),
                    'notes' => 'Direct reward granted by admin',
                ]);
                
                Log::info('New referral record created with reward', [
                    'referral_id' => $referral->id,
                    'order_id' => $selectedOrder->id,
                ]);
            }

            // Update affiliate stats
            $affiliate->increment('total_referrals');
            $affiliate->increment('total_rewards_earned');

            // Mark the selected device as rewarded (for tracking purposes only)
            if ($selectedOrder->devices && is_array($selectedOrder->devices) && count($selectedOrder->devices) > 1) {
                $this->markSelectedDeviceAsRewarded($affiliate, $selectedOrder);
            }

            // Send congratulations email
            $this->sendCongratulationsEmail($affiliate, $selectedOrder);

            Log::info('Direct affiliate reward granted by admin', [
                'admin_id' => auth()->id(),
                'affiliate_id' => $affiliate->id,
                'referral_id' => $referral->id,
                'order_id' => $selectedOrder->id,
                'reward_type' => 'direct_admin_grant',
            ]);

            return redirect()->back()
                ->with('success', 'Reward granted successfully! Referral record created, affiliate notified via email.');

        } catch (\Exception $e) {
            Log::error('Failed to grant direct affiliate reward', [
                'admin_id' => auth()->id(),
                'affiliate_id' => $affiliate->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to grant reward: ' . $e->getMessage());
        }
    }

    /**
     * Mark the selected device as rewarded for tracking purposes only
     */
    private function markSelectedDeviceAsRewarded($affiliate, $selectedOrder)
    {
        $devices = $selectedOrder->devices;
        $selectedDeviceId = $affiliate->selected_device_id;
        $deviceMarked = false;

        // Find and mark the selected device as rewarded
        foreach ($devices as $index => &$device) {
            $deviceId = $device['id'] ?? $index;
            
            if ($deviceId == $selectedDeviceId) {
                // Mark device as rewarded for tracking (no expiry extension)
                $device['reward_granted'] = true;
                $device['last_reward_date'] = now()->toDateTimeString();
                $device['reward_count'] = ($device['reward_count'] ?? 0) + 1;
                
                $deviceMarked = true;
                
                Log::info('Device marked as rewarded (tracking only)', [
                    'device_id' => $deviceId,
                    'device_index' => $index,
                    'reward_count' => $device['reward_count'],
                ]);
                
                break;
            }
        }

        if ($deviceMarked) {
            // Update the order with modified devices array
            $selectedOrder->update(['devices' => $devices]);
        }
    }

    /**
     * Delete the specified affiliate
     */
    public function destroy(Affiliate $affiliate)
    {
        try {
            // Log the deletion attempt
            Log::info('Affiliate deletion initiated by admin', [
                'admin_id' => auth()->id(),
                'affiliate_id' => $affiliate->id,
                'affiliate_email' => $affiliate->email,
                'referral_code' => $affiliate->referral_code,
                'total_referrals' => $affiliate->total_referrals,
            ]);

            // Delete all associated referral records first
            $referralCount = $affiliate->referrals()->count();
            $affiliate->referrals()->delete();

            // Delete the affiliate
            $affiliate->delete();

            Log::info('Affiliate deleted successfully by admin', [
                'admin_id' => auth()->id(),
                'affiliate_id' => $affiliate->id,
                'deleted_referrals' => $referralCount,
            ]);

            return redirect()->route('admin.affiliates.index')
                ->with('success', "Affiliate deleted successfully. {$referralCount} associated referral records were also removed.");

        } catch (\Exception $e) {
            Log::error('Failed to delete affiliate', [
                'admin_id' => auth()->id(),
                'affiliate_id' => $affiliate->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to delete affiliate: ' . $e->getMessage());
        }
    }

    /**
     * Send congratulations email to affiliate
     */
    private function sendCongratulationsEmail(Affiliate $affiliate, \App\Models\Order $order)
    {
        try {
            // Create and send the congratulations email
            $congratulationsMail = new \App\Mail\AffiliateCongratulationsMail($affiliate, $order);
            
            // Use source-specific SMTP configuration if available
            if ($congratulationsMail->mailerName) {
                \Illuminate\Support\Facades\Mail::mailer($congratulationsMail->mailerName)->to($affiliate->email)->send($congratulationsMail);
            } else {
                \Illuminate\Support\Facades\Mail::to($affiliate->email)->send($congratulationsMail);
            }

            Log::info('Congratulations email sent to affiliate', [
                'affiliate_id' => $affiliate->id,
                'email' => $affiliate->email,
                'mailer' => $congratulationsMail->mailerName ?? 'default',
                'source' => $order->source ?? 'unknown',
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send congratulations email to affiliate', [
                'affiliate_id' => $affiliate->id,
                'email' => $affiliate->email,
                'error' => $e->getMessage(),
            ]);
            
            // Don't throw exception, just log the error
            // The reward was still granted successfully
        }
    }
}
