<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminPermission extends Model
{
    protected $fillable = [
        'admin_id',
        'can_manage_sources',
        'can_create_custom_products',
        'can_send_renewal_emails',
        'can_manage_pricing_plans',
        'can_manage_reseller_credit_packs',
        'can_manage_payment_config',
        'can_view_orders',
        'can_manage_orders',
        'max_sources',
        'max_custom_products',
        'max_reseller_credit_packs',
    ];

    protected $casts = [
        'can_manage_sources' => 'boolean',
        'can_create_custom_products' => 'boolean',
        'can_send_renewal_emails' => 'boolean',
        'can_manage_pricing_plans' => 'boolean',
        'can_manage_reseller_credit_packs' => 'boolean',
        'can_manage_payment_config' => 'boolean',
        'can_view_orders' => 'boolean',
        'can_manage_orders' => 'boolean',
        'max_sources' => 'integer',
        'max_custom_products' => 'integer',
        'max_reseller_credit_packs' => 'integer',
    ];

    /**
     * Get the admin user that owns these permissions
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
