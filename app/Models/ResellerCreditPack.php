<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ResellerCreditPack extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'price',
        'credits_amount',
        'features',
        'payment_methods',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
            'features' => 'array',
            'payment_methods' => 'array',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'price', 'credits_amount', 'features', 'payment_methods', 'is_active'])
            ->logOnlyDirty();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price ?? 0, 2);
    }

    /**
     * Get formatted price per credit
     */
    public function getFormattedPricePerCreditAttribute()
    {
        $price = $this->price ?? 0;
        $credits = $this->credits_amount ?? 0;

        if ($credits > 0 && $price > 0) {
            $pricePerCredit = $price / $credits;
            return '$' . number_format($pricePerCredit, 4);
        }
        return '$0.0000';
    }



    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class, 'pricing_plan_id');
    }
}
