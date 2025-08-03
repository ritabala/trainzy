<?php

use App\Models\Gym;
use App\Models\Currency;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\GymPackageSubscription;
use App\Models\GlobalSetting;
use App\Models\User;
use App\Models\GlobalCurrency;

function user()
{
    if (session()->has('user')) {
        return session()->get('user');
    }

    session()->put('user', auth()->user());

    return session()->get('user');
}


function gym()
{ 
    if (session()->has('gym')) {
        return session()->get('gym');
    }

    if (user()) {
        if (user()->gym_id) {
            session()->put('gym', Gym::find(user()->gym_id));
            return session()->get('gym');
        }
    }

    return false;
}

function global_settings()
{
    return Cache::remember('global_settings', 60 * 60 * 24, function () {
        return GlobalSetting::first();
    });
}

function currency()
{
    return Cache::remember('currency', 60 * 60 * 24, function () {
        return Currency::find(gym()->currency_id);
    });
}

function currency_format($amount, $currency_id = null)
{
    $currency = $currency_id ? Currency::find($currency_id) : currency();
    return $currency->symbol . number_format($amount, $currency->decimal_places, $currency->decimal_point, $currency->thousands_separator);
}

function global_currency_format($amount, $currency_id = null)
{
    $currency = $currency_id ? GlobalCurrency::find($currency_id) : global_settings()->currency;
    return $currency->symbol . number_format($amount, $currency->decimal_places, $currency->decimal_point, $currency->thousands_separator);
}

function has_module_access($module_name)
{
    if (!auth()->check()) {
        return false;
    }

    $user = user();
    
    // If user is super admin, they have access to all modules
    if ($user->isSuperAdmin()) {
        return true;
    }

    // Get the current gym
    $currentGym = gym();
    if (!$currentGym) {
        return false;
    }

        
    $activeSubscription = Cache::remember('active_package_subscription_' . $currentGym->id, 60 * 60 * 24, function () use ($currentGym) {
        // Get the gym's active package subscription
        $activeSubscription = GymPackageSubscription::where('gym_id', $currentGym->id)
            ->where('is_active', true)
            ->where(function($query) {
                $query->whereNull('ends_on')
                    ->orWhere('ends_on', '>', now());
            })
            ->with('package')
            ->latest('starts_on')
            ->first();

        return $activeSubscription;
    });

    if (!$activeSubscription || !$activeSubscription->package) {
        return false;
    }

    $package = $activeSubscription->package;

    // Get all active modules for the gym's package
    $packageModules = Cache::remember('package_modules_' . $package->id, 60 * 60 * 24, function () use ($package) {
        return $package->modules()
            ->where('status', 'active')
            ->pluck('name')
            ->toArray();
    });

    return in_array($module_name, $packageModules);
}
