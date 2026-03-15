<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PricingPlan extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'server_type',
        'custom_label',
        'plan_type',
        'device_count',
        'duration_months',
        'price',
        'features',
        'payment_link',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
            'features' => 'array',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'server_type', 'custom_label', 'device_count', 'duration_months', 'price', 'is_active'])
            ->logOnlyDirty();
    }

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Scopes
    public function scopeRegular($query)
    {
        return $query->where('plan_type', 'regular');
    }

    public function scopeReseller($query)
    {
        return $query->where('plan_type', 'reseller');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helper methods
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    public function getDisplayNameAttribute()
    {
        $planTypeLabel = $this->plan_type === 'reseller' ? 'Reseller ' : '';
        $serverLabel = $this->server_type === 'generic'
            ? ($this->custom_label ?: 'Custom')
            : ucfirst($this->server_type);
        return $planTypeLabel . $serverLabel . ' - ' . $this->device_count . ' Device(s) - ' . $this->duration_months . ' Month(s)';
    }

    public function getServerLabelAttribute()
    {
        return $this->server_type === 'generic'
            ? ($this->custom_label ?: 'Custom')
            : ucfirst($this->server_type);
    }
}
