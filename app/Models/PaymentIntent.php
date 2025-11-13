<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class PaymentIntent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admin_id',
        'pricing_plan_id',
        'reseller_credit_pack_id',
        'payment_intent_id',
        'payment_method',
        'amount',
        'currency',
        'status',
        'order_type',
        'order_data',
        'gateway_response',
        'expires_at',
        'completed_at',
    ];

    protected $casts = [
        'order_data' => 'array',
        'gateway_response' => 'array',
        'expires_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function pricingPlan()
    {
        return $this->belongsTo(PricingPlan::class);
    }

    public function resellerCreditPack()
    {
        return $this->belongsTo(ResellerCreditPack::class);
    }

    // Helper methods
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at < now();
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    /**
     * Generate unique order number for when order is created
     */
    public function generateOrderNumber()
    {
        $prefix = $this->order_type === 'credit_pack' ? 'CP' : 'ORD';
        return $prefix . '-' . date('Y') . '-' . str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Create order from payment intent after successful payment
     */
    public function createOrder()
    {
        if (!$this->isCompleted()) {
            throw new \Exception('Cannot create order from incomplete payment intent');
        }

        // Check if this payment intent has already been processed
        if ($this->status === 'processed') {
            throw new \Exception('Payment intent has already been processed');
        }

        // Check if an order already exists for this payment intent (idempotency)
        if (!empty($this->payment_intent_id)) {
            $existingOrder = Order::where('payment_id', $this->payment_intent_id)->first();
            if ($existingOrder) {
                return $existingOrder;
            }
        }

        $orderData = $this->order_data;
        $orderData['order_number'] = $this->generateOrderNumber();
        $orderData['status'] = 'pending'; // Will be activated by admin
        $orderData['payment_method'] = $this->payment_method;
        $orderData['amount'] = $this->amount;
        $orderData['payment_id'] = $this->payment_intent_id; // Store transaction ID

        // Ensure correct type and expiry handling for credit pack orders
        $orderData['order_type'] = $orderData['order_type'] ?? $this->order_type;
        if (($orderData['order_type'] ?? null) === 'credit_pack') {
            $orderData['expires_at'] = null;
        }

        // Handle renewal credentials if customer chose to keep existing credentials
        if (isset($orderData['credentials_option']) && $orderData['credentials_option'] === 'keep' && isset($orderData['renewal_credentials'])) {
            $renewalCreds = $orderData['renewal_credentials'];
            if (isset($renewalCreds['subscription_username'])) {
                $orderData['subscription_username'] = $renewalCreds['subscription_username'];
            }
            if (isset($renewalCreds['subscription_password'])) {
                $orderData['subscription_password'] = $renewalCreds['subscription_password'];
            }
            if (isset($renewalCreds['subscription_url'])) {
                $orderData['subscription_url'] = $renewalCreds['subscription_url'];
            }
            if (isset($renewalCreds['devices'])) {
                $orderData['devices'] = $renewalCreds['devices'];
            }
        }

        // Remove internal fields that shouldn't be stored in orders table
        // Note: 'source' is intentionally preserved and will be stored in the order
        // Also preserve renewal_of_order_id and renewal_of_order_number for tracking
        unset($orderData['credentials_option'], $orderData['renewal_credentials'], $orderData['customer']);

        // Ensure source is explicitly set if present in order_data
        // This is critical for renewals to use the correct source from the URL
        if (isset($this->order_data['source']) && !empty($this->order_data['source'])) {
            $orderData['source'] = $this->order_data['source'];
        } elseif (!isset($orderData['source'])) {
            // If source is not set at all, default to 'renewal' for renewal orders
            if (isset($orderData['subscription_type']) && $orderData['subscription_type'] === 'renewal') {
                $orderData['source'] = 'renewal';
            }
        }

        // Create the order (source will be included from order_data if present)
        $order = Order::create($orderData);

        // Create payment record
        Payment::create([
            'user_id' => $this->user_id,
            'order_id' => $order->id,
            'payment_id' => $this->payment_intent_id,
            'payment_method' => $this->payment_method,
            'status' => 'completed',
            'amount' => $this->amount,
            'currency' => $this->currency,
            'paid_at' => $this->completed_at,
            'gateway_response' => $this->gateway_response,
        ]);

        // Mark payment intent as processed
        $this->update(['status' => 'processed']);

        return $order;
    }

    /**
     * Scope for expired payment intents
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    /**
     * Scope for pending payment intents
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
