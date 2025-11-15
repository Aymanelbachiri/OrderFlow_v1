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
        'shield_domain_id',
        'use_shield_domain',
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
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'use_shield_domain' => 'boolean',
            'smtp_port' => 'integer',
        ];
    }

    /**
     * Get the shield domain for this source
     */
    public function shieldDomain()
    {
        return $this->belongsTo(ShieldDomain::class);
    }

    /**
     * Check if source should use shield domain
     */
    public function shouldUseShieldDomain(): bool
    {
        return $this->use_shield_domain 
            && $this->shieldDomain 
            && $this->shieldDomain->isActive();
    }

    /**
     * Get the shield domain URL if available
     */
    public function getShieldDomainUrl(): ?string
    {
        if ($this->shouldUseShieldDomain()) {
            return $this->shieldDomain->getUrl();
        }
        return null;
    }
}


