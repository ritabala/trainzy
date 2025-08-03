<?php

namespace App\Observers;

use App\Models\Tax;

class TaxObserver
{
    public function creating(Tax $tax): void
    {
        if (gym()) {
            $tax->gym_id = gym()->id;
        }
    }

    /**
     * Handle the Tax "created" event.
     */
    public function created(Tax $tax): void
    {
        //
    }

    /**
     * Handle the Tax "updated" event.
     */
    public function updated(Tax $tax): void
    {
        //
    }

    /**
     * Handle the Tax "deleted" event.
     */
    public function deleted(Tax $tax): void
    {
        //
    }

    /**
     * Handle the Tax "restored" event.
     */
    public function restored(Tax $tax): void
    {
        //
    }

    /**
     * Handle the Tax "force deleted" event.
     */
    public function forceDeleted(Tax $tax): void
    {
        //
    }
}
