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
            'smtp_port' => 'integer',
        ];
    }
}


