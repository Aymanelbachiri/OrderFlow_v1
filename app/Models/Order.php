<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id',
        'pricing_plan_id',
        'reseller_credit_pack_id',
        'custom_product_id',
        'source',
        'order_number',
        'order_type',
        'subscription_type',
        'status',
        'amount',
        'payment_method',
        'payment_email_sent',
        'payment_id',
        'payment_details',
        'starts_at',
        'expires_at',
        'subscription_username',
        'subscription_password',
        'subscription_url',
        'devices',
        'reseller_username',
        'reseller_password',
        'reseller_login_url',
        'credentials_sent',
        'credentials_sent_at',
        'completed_at',
        'notes',
        'referral_code',
        'affiliate_referral_id',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_details' => 'array',
            'devices' => 'array',
            'payment_email_sent' => 'boolean',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'credentials_sent' => 'boolean',
            'credentials_sent_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'amount', 'payment_method', 'expires_at'])
            ->logOnlyDirty();
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pricingPlan()
    {
        return $this->belongsTo(PricingPlan::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function renewalNotifications()
    {
        return $this->hasMany(RenewalNotification::class);
    }

    public function resellerCreditPack()
    {
        return $this->belongsTo(ResellerCreditPack::class);
    }

    public function customProduct()
    {
        return $this->belongsTo(CustomProduct::class);
    }

    public function affiliateReferral()
    {
        return $this->belongsTo(AffiliateReferral::class);
    }

    // Helper methods
    public function isActive()
    {
        return $this->status === 'active' && $this->expires_at > now();
    }

    public function isExpired()
    {
        return $this->expires_at < now();
    }

    public function isResellerOrder()
    {
        return $this->order_type === 'credit_pack';
    }

    public function isCustomProduct()
    {
        return $this->order_type === 'custom_product';
    }

    public function getSubscriptionTypeDisplayAttribute()
    {
        return match($this->subscription_type) {
            'new' => 'New Subscription',
            'renewal' => 'Renewal',
            default => 'New Subscription'
        };
    }

    public function daysUntilExpiry()
    {
        return $this->expires_at ? now()->diffInDays($this->expires_at, false) : null;
    }

    public function getTrafficSourceAttribute()
    {
        return !empty($this->referral_code) ? 'Referred' : 'Organic';
    }

    public function generateOrderNumber()
    {
        return 'ORD-' . date('Y') . '-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate expiry date based on pricing plan duration
     */
    public function calculateExpiryDate()
    {
        // Credit pack orders should not have expiry dates
        if ($this->order_type === 'credit_pack') {
            return null;
        }

        if (!$this->pricingPlan || !$this->pricing_plan_id) {
            return $this->expires_at; // Return existing expiry date if no plan
        }

        $startDate = $this->starts_at ?: now();
        $durationMonths = $this->pricingPlan->duration_months ?? 1;
        
        return $startDate->copy()->addMonths($durationMonths);
    }

    /**
     * Calculate amount based on pricing plan price
     */
    public function calculateAmount()
    {
        if (!$this->pricingPlan || !$this->pricing_plan_id) {
            return $this->amount; // Return existing amount if no plan
        }

        return $this->pricingPlan->price ?? 0;
    }

    /**
     * Update expiry date and amount when pricing plan changes
     */
    public function updateExpiryDate()
    {
        $newExpiryDate = $this->calculateExpiryDate();
        $newAmount = $this->calculateAmount();
        $needsUpdate = false;
        
        if ($newExpiryDate && $newExpiryDate != $this->expires_at) {
            $this->expires_at = $newExpiryDate;
            $needsUpdate = true;
        }
        
        if ($newAmount && $newAmount != $this->amount) {
            $this->amount = $newAmount;
            $needsUpdate = true;
        }
        
        if ($needsUpdate) {
            $this->save();
        }
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Update expiry date and amount when pricing_plan_id changes
        static::updating(function ($order) {
            if ($order->isDirty('pricing_plan_id') && $order->pricing_plan_id) {
                try {
                    // Only set expiry date for subscription orders, not credit pack orders
                    if ($order->order_type !== 'credit_pack') {
                        $order->expires_at = $order->calculateExpiryDate();
                    }
                    $order->amount = $order->calculateAmount();
                } catch (\Exception $e) {
                    // Log error but don't break the update
                    \Log::warning('Failed to update order calculations: ' . $e->getMessage());
                }
            }
        });

        // Set expiry date and amount when creating new order
        static::creating(function ($order) {
            if ($order->pricing_plan_id) {
                try {
                    // Only set expiry date for subscription orders, not credit pack orders
                    if ($order->order_type !== 'credit_pack' && !$order->expires_at) {
                        $order->expires_at = $order->calculateExpiryDate();
                    }
                    if (!$order->amount || $order->amount == 0) {
                        $order->amount = $order->calculateAmount();
                    }
                } catch (\Exception $e) {
                    // Log error but don't break the creation
                    \Log::warning('Failed to set order calculations on creation: ' . $e->getMessage());
                }
            }
        });
    }
}
