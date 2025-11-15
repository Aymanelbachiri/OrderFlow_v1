<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShieldDomain extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain',
        'template_name',
        'status',
        'cloudflare_zone_id',
        'cloudflare_pages_project_id',
        'cloudflare_nameservers',
        'dns_configured',
        'dns_configured_at',
        'config',
    ];

    protected function casts(): array
    {
        return [
            'cloudflare_nameservers' => 'array',
            'config' => 'array',
            'dns_configured' => 'boolean',
            'dns_configured_at' => 'datetime',
        ];
    }

    /**
     * Get all sources using this shield domain
     */
    public function sources()
    {
        return $this->hasMany(Source::class);
    }

    /**
     * Check if shield domain is active and ready
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->dns_configured;
    }

    /**
     * Get the full URL for this shield domain
     */
    public function getUrl(): string
    {
        return 'https://' . $this->domain;
    }
}
