<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Carbon\Carbon;

class AffiliateReferral extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'affiliate_id',
        'order_id',
        'referred_user_id',
        'status',
        'reward_granted',
        'reward_granted_at',
        'granted_by_admin_id',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'reward_granted' => 'boolean',
            'reward_granted_at' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'reward_granted', 'granted_by_admin_id', 'notes'])
            ->logOnlyDirty();
    }

    // Relationships
    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function referredUser()
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }

    public function grantedBy()
    {
        return $this->belongsTo(User::class, 'granted_by_admin_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Helper methods
    public function approve($adminId = null, $notes = null)
    {
        $this->update([
            'status' => 'approved',
            'granted_by_admin_id' => $adminId ?? auth()->id(),
            'notes' => $notes ?? $this->notes,
        ]);

        return $this;
    }

    public function reject($adminId = null, $notes = null)
    {
        $this->update([
            'status' => 'rejected',
            'granted_by_admin_id' => $adminId ?? auth()->id(),
            'notes' => $notes ?? $this->notes,
        ]);

        return $this;
    }

    public function grantReward($adminId = null)
    {
        if ($this->reward_granted) {
            throw new \Exception('Reward has already been granted for this referral.');
        }

        if ($this->status !== 'approved') {
            throw new \Exception('Cannot grant reward for non-approved referral.');
        }

        $affiliate = $this->affiliate;
        $selectedOrder = $affiliate->selectedOrder;

        if (!$selectedOrder) {
            throw new \Exception('Affiliate does not have a selected subscription order.');
        }

        // Update referral record (no date extension, just mark as rewarded)
        $this->update([
            'reward_granted' => true,
            'reward_granted_at' => now(),
            'granted_by_admin_id' => $adminId ?? auth()->id(),
        ]);

        // Update affiliate stats
        $affiliate->increment('total_rewards_earned');

        // Mark the selected device as rewarded (for tracking purposes only)
        if ($selectedOrder->devices && is_array($selectedOrder->devices) && count($selectedOrder->devices) > 1) {
            $this->markSelectedDeviceAsRewarded($affiliate, $selectedOrder);
        }

        // Send congratulations email to affiliate
        try {
            \Mail::send(new \App\Mail\AffiliateCongratulationsMail($affiliate, $selectedOrder));
            
            \Log::info('Affiliate congratulations email sent', [
                'affiliate_id' => $affiliate->id,
                'affiliate_email' => $affiliate->email,
                'referral_id' => $this->id,
                'order_id' => $selectedOrder->id,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send affiliate congratulations email', [
                'affiliate_id' => $affiliate->id,
                'affiliate_email' => $affiliate->email,
                'referral_id' => $this->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Don't throw exception - reward should still be granted even if email fails
        }

        // Log the action
        \Log::info('Affiliate reward granted', [
            'affiliate_id' => $affiliate->id,
            'referral_id' => $this->id,
            'order_id' => $selectedOrder->id,
            'reward_type' => 'tracking_only',
            'granted_by' => $adminId ?? auth()->id(),
        ]);

        return $this;
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
                
                \Log::info('Device marked as rewarded (tracking only)', [
                    'device_id' => $deviceId,
                    'device_index' => $index,
                    'reward_count' => $device['reward_count'],
                ]);
                
                break;
            }
        }

        if (!$deviceMarked) {
            \Log::warning('Selected device not found for reward tracking', [
                'selected_device_id' => $selectedDeviceId,
                'available_devices' => count($devices),
            ]);
        } else {
            // Update the order with modified devices array
            $selectedOrder->update(['devices' => $devices]);
        }
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}
