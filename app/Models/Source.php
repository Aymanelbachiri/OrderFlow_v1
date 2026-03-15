<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'return_url',
        'renewal_url',
        'is_active',
        // Order Notification Email
        'use_own_notify_email',
        'notify_email',
        // SMTP Configuration
        'smtp_mailer',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'smtp_from_address',
        'smtp_from_name',
        // Email Template Variables
        'company_name',
        'contact_email',
        'website',
        'phone_number',
        'team_name',
        // HotPlayer Integration
        'hotplayer_api_key',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'use_own_notify_email' => 'boolean',
            'smtp_port' => 'integer',
        ];
    }

    /**
     * Get the email address to send order notifications to for this source.
     * Returns the source's own email if toggled on, otherwise null (use global admin).
     */
    public function getNotifyEmail(): ?string
    {
        if ($this->use_own_notify_email && $this->notify_email) {
            return $this->notify_email;
        }

        return null;
    }

    /**
     * Agents assigned to this source.
     */
    public function agents()
    {
        return $this->belongsToMany(User::class, 'agent_source');
    }
}


