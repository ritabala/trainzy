<?php

namespace App\Models;

use Laravel\Cashier\Billable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Exceptions\IncompletePayment;

class Gym extends Model
{
    use Billable;
    
    protected $guarded = ['id'];
    protected $appends = ['logo_url'];

    /**
     * Ensure the gym has a default payment method
     */
    public function ensureDefaultPaymentMethod()
    {
        if (!$this->hasDefaultPaymentMethod()) {
            throw new IncompletePayment('Please add a payment method first.');
        }
    }

    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return global_settings()->logo_url;
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function packageSubscriptions()
    {
        return $this->hasMany(GymPackageSubscription::class);
    }

    public function activePackageSubscription()
    {
        return $this->packageSubscriptions()
            ->where('is_active', true)
            ->where('starts_on', '<=', now())
            ->where(function ($query) {
                $query->where('ends_on', '>=', now())
                    ->orWhereNull('ends_on');
            })
            ->latest()
            ->first();
    }

    public function packagePayments()
    {
        return $this->hasMany(PackagePayment::class);
    }

    public function activityClasses()
    {
        return $this->hasMany(ActivityClass::class);
    }

    public function gymListings()
    {
        return $this->hasOne(GymListing::class);
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }
}
