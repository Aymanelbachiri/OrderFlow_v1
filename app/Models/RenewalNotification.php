<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RenewalNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'days_before_expiry',
        'sent',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'sent' => 'boolean',
            'sent_at' => 'datetime',
        ];
    }

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
