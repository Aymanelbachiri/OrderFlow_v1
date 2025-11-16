<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CustomProduct extends Model
{
    use HasFactory, LogsActivity;

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'price',
        'product_type',
        'is_active',
        'stock_quantity',
        'metadata',
        'custom_fields',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
            'stock_quantity' => 'integer',
            'metadata' => 'array',
            'custom_fields' => 'array',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'price', 'product_type', 'is_active', 'stock_quantity'])
            ->logOnlyDirty();
    }

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('stock_quantity')
              ->orWhere('stock_quantity', '>', 0);
        });
    }

    // Helper methods
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    public function isAvailable()
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->stock_quantity === null) {
            return true; // Unlimited stock
        }

        return $this->stock_quantity > 0;
    }

    public function decrementStock()
    {
        if ($this->stock_quantity !== null && $this->stock_quantity > 0) {
            $this->decrement('stock_quantity');
        }
    }

    // Auto-generate slug when creating
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }
}

