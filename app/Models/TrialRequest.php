<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrialRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'email',
        'phone',
        'country',
        'server',
        'server_type',
        'trial_duration',
        'has_whatsapp',
        'requested_countries',
        'source',
        'status',
        'notes',
        'processed_at',
        'processed_by',
        'trial_username',
        'trial_password',
        'trial_url',
        'credentials_sent',
        'credentials_sent_at',
        'trial_expires_at',
        'followup_sent',
        'followup_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'has_whatsapp' => 'boolean',
            'processed_at' => 'datetime',
            'credentials_sent' => 'boolean',
            'credentials_sent_at' => 'datetime',
            'trial_expires_at' => 'datetime',
            'followup_sent' => 'boolean',
            'followup_sent_at' => 'datetime',
        ];
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
