<?php

namespace App\Observers;

use App\Models\Currency;

class CurrencyObserver
{
    public function creating(Currency $currency): void
    {
        if (gym() && auth()->check() && !auth()->user()->isSuperAdmin()) {
            $currency->gym_id = gym()->id;
        }
    }

    /**
     * Handle the Currency "created" event.
     */
    public function created(Currency $currency): void
    {
        //
    }

    /**
     * Handle the Currency "updated" event.
     */
    public function updated(Currency $currency): void
    {
        //
    }

    /**
     * Handle the Currency "deleted" event.
     */
    public function deleted(Currency $currency): void
    {
        //
    }

    /**
     * Handle the Currency "restored" event.
     */
    public function restored(Currency $currency): void
    {
        //
    }

    /**
     * Handle the Currency "force deleted" event.
     */
    public function forceDeleted(Currency $currency): void
    {
        //
    }
}
