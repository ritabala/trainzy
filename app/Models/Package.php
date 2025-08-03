<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Package extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'price',
        'billing_cycle',
        'max_members',
        'max_staff',
        'max_classes',
        'is_active',
        'plan_type',
        'package_name',
        'package_type',
        'currency_id',
        'monthly_price',
        'annual_price',
        'lifetime_price',
        'trial_days',
        'trial_message',
        'notification_before_days',
        'stripe_product_id',
        'stripe_monthly_price_id',
        'stripe_annual_price_id',
        'stripe_lifetime_price_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'max_members' => 'integer',
        'max_staff' => 'integer',
        'max_classes' => 'integer',
        'is_active' => 'boolean',
        'monthly_price' => 'decimal:2',
        'annual_price' => 'decimal:2',
        'lifetime_price' => 'decimal:2',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($package) {
            if (is_null($package->modules)) {
                $package->modules = static::$defaultModules;
            }
        });
    }

    /**
     * Get the subscriptions for the package.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(GymPackageSubscription::class);
    }

    /**
     * Get the modules for the package.
     */
    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'package_modules')
            ->withTimestamps();
    }

    /**
     * Get the currency for the package.
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(GlobalCurrency::class, 'currency_id');
    }

    /**
     * Scope a query to only include active packages.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include monthly billing cycle packages.
     */
    public function scopeMonthly($query)
    {
        return $query->where('billing_cycle', 'monthly');
    }

    /**
     * Scope a query to only include yearly billing cycle packages.
     */
    public function scopeYearly($query)
    {
        return $query->where('billing_cycle', 'yearly');
    }
}
