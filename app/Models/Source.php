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
        'is_active',
        'admin_id',
        'smtp_config',
        'email_variables',
    ];

    // Relationships
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'smtp_config' => 'array',
            'email_variables' => 'array',
        ];
    }

    /**
     * Get SMTP configuration value
     */
    public function getSmtpConfig(string $key, $default = null)
    {
        return $this->smtp_config[$key] ?? $default;
    }

    /**
     * Get email variable value
     */
    public function getEmailVariable(string $key, $default = null)
    {
        return $this->email_variables[$key] ?? $default;
    }

    /**
     * Get company name (from email variables or fallback to app name)
     */
    public function getCompanyName(): string
    {
        return $this->getEmailVariable('company_name', config('app.name'));
    }

    /**
     * Get website URL (from email variables or fallback to app URL)
     */
    public function getWebsiteUrl(): string
    {
        return $this->getEmailVariable('website_url', config('app.url'));
    }

    /**
     * Get support email (from email variables or fallback to default)
     */
    public function getSupportEmail(): string
    {
        return $this->getEmailVariable('support_email', config('mail.from.address', 'support@example.com'));
    }

    /**
     * Get contact email (from email variables or fallback to support email)
     */
    public function getContactEmail(): string
    {
        return $this->getEmailVariable('contact_email', $this->getSupportEmail());
    }

    /**
     * Get team name (from email variables or fallback to company name)
     */
    public function getTeamName(): string
    {
        return $this->getEmailVariable('team_name', $this->getCompanyName() . ' Team');
    }
}


