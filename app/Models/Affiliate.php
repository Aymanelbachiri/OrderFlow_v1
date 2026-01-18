<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Affiliate extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id',
        'email',
        'referral_code',
        'selected_order_id',
        'selected_device_id',
        'is_active',
        'total_referrals',
        'total_rewards_earned',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'total_referrals' => 'integer',
            'total_rewards_earned' => 'integer',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['email', 'referral_code', 'is_active', 'selected_order_id'])
            ->logOnlyDirty();
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function selectedOrder()
    {
        return $this->belongsTo(Order::class, 'selected_order_id');
    }

    public function referrals()
    {
        return $this->hasMany(AffiliateReferral::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWithActiveSubscription($query)
    {
        return $query->whereHas('selectedOrder', function($q) {
            $q->where('status', 'active')
              ->where('order_type', 'subscription')
              ->where('expires_at', '>', now());
        });
    }

    // Helper methods
    public function generateReferralCode()
    {
        do {
            // Generate 8-12 character alphanumeric code (uppercase letters + numbers)
            $code = strtoupper(Str::random(10));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }

    public function hasActiveSubscription()
    {
        if (!$this->selectedOrder) {
            return false;
        }

        return $this->selectedOrder->isActive() && 
               $this->selectedOrder->order_type === 'subscription';
    }

    public function canReferUser($email)
    {
        // Prevent self-referral
        if (strtolower($this->email) === strtolower($email)) {
            return false;
        }

        // Must have active subscription
        if (!$this->hasActiveSubscription()) {
            return false;
        }

        // Must be active affiliate
        if (!$this->is_active) {
            return false;
        }

        return true;
    }

    public function getReferralLinkAttribute()
    {
        $baseUrl = config('app.url');
        return $baseUrl . '/checkout?ref=' . $this->referral_code;
    }

    public function getPendingRewardsCountAttribute()
    {
        return $this->referrals()
            ->where('status', 'pending')
            ->where('reward_granted', false)
            ->count();
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Generate referral code before creating
        static::creating(function ($affiliate) {
            if (empty($affiliate->referral_code)) {
                $affiliate->referral_code = $affiliate->generateReferralCode();
            }
        });
    }
}
