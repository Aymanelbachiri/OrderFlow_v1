<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Billable, HasRoles, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'source',
        'password',
        'phone',
        'role',
        'is_active',
        'suspended_at',
        'suspension_reason',
        'reseller_panel_url',
        'reseller_panel_username',
        'reseller_panel_password',
        'available_credits',
        'is_super_admin',
        'admin_id',
        'created_by_admin_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'reseller_panel_password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'suspended_at' => 'datetime',
            'is_active' => 'boolean',
            'is_super_admin' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'role', 'is_active'])
            ->logOnlyDirty();
    }

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class, 'author_id');
    }

    // Admin relationships
    public function adminPermissions()
    {
        return $this->hasOne(AdminPermission::class, 'admin_id');
    }

    public function adminConfig()
    {
        return $this->hasOne(AdminConfig::class, 'admin_id');
    }

    public function createdAdmins()
    {
        return $this->hasMany(User::class, 'created_by_admin_id');
    }

    public function adminUser()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Admin data relationships (scoped to this admin)
    public function adminOrders()
    {
        return $this->hasMany(Order::class, 'admin_id');
    }

    public function adminPaymentIntents()
    {
        return $this->hasMany(PaymentIntent::class, 'admin_id');
    }

    public function adminSources()
    {
        return $this->hasMany(Source::class, 'admin_id');
    }

    public function adminCustomProducts()
    {
        return $this->hasMany(CustomProduct::class, 'admin_id');
    }

    public function adminPricingPlans()
    {
        return $this->hasMany(PricingPlan::class, 'admin_id');
    }

    public function adminResellerCreditPacks()
    {
        return $this->hasMany(ResellerCreditPack::class, 'admin_id');
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isSuperAdmin()
    {
        return $this->isAdmin() && $this->is_super_admin === true;
    }

    public function isRegularAdmin()
    {
        return $this->isAdmin() && !$this->isSuperAdmin();
    }

    public function isClient()
    {
        return $this->role === 'client';
    }

    public function isReseller()
    {
        return $this->role === 'reseller';
    }

    public function isSuspended()
    {
        return !$this->is_active || $this->suspended_at !== null;
    }

    /**
     * Get admin permissions (with defaults if not set)
     */
    public function getPermissions()
    {
        if (!$this->isAdmin()) {
            return null;
        }

        return $this->adminPermissions ?? AdminPermission::create([
            'admin_id' => $this->id,
        ]);
    }

    /**
     * Get admin configuration (with defaults if not set)
     */
    public function getConfig()
    {
        if (!$this->isAdmin()) {
            return null;
        }

        return $this->adminConfig ?? AdminConfig::create([
            'admin_id' => $this->id,
        ]);
    }

    /**
     * Check if admin has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true; // Super admin has all permissions
        }

        $permissions = $this->getPermissions();
        if (!$permissions) {
            return false;
        }

        return $permissions->$permission ?? false;
    }

    /**
     * Check if admin can create more resources of a type
     */
    public function canCreateResource(string $type): bool
    {
        if ($this->isSuperAdmin()) {
            return true; // Super admin has unlimited resources
        }

        $permissions = $this->getPermissions();
        if (!$permissions) {
            return false;
        }

        $maxKey = 'max_' . $type;
        $maxCount = $permissions->$maxKey;

        if ($maxCount === null) {
            return true; // No limit set
        }

        // Get current count
        $currentCount = match($type) {
            'sources' => $this->adminSources()->count(),
            'custom_products' => $this->adminCustomProducts()->count(),
            'reseller_credit_packs' => $this->adminResellerCreditPacks()->count(),
            default => 0,
        };

        return $currentCount < $maxCount;
    }
}
