<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminConfig extends Model
{
    protected $fillable = [
        'admin_id',
        'payment_config',
        'smtp_config',
        'settings',
    ];

    protected $casts = [
        'payment_config' => 'array',
        'smtp_config' => 'array',
        'settings' => 'array',
    ];

    /**
     * Get the admin user that owns this configuration
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get payment configuration value
     */
    public function getPaymentConfig(string $key, $default = null)
    {
        return $this->payment_config[$key] ?? $default;
    }

    /**
     * Get SMTP configuration value
     */
    public function getSmtpConfig(string $key, $default = null)
    {
        return $this->smtp_config[$key] ?? $default;
    }

    /**
     * Get setting value
     */
    public function getSetting(string $key, $default = null)
    {
        return $this->settings[$key] ?? $default;
    }
}
