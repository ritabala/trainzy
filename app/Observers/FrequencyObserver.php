<?php

namespace App\Observers;

use App\Models\Frequency;

class FrequencyObserver
{
    public function creating(Frequency $frequency): void
    {
        if (gym()) {
            $frequency->gym_id = gym()->id;
        }
    }

    /**
     * Handle the Frequency "created" event.
     */
    public function created(Frequency $frequency): void
    {
        //
    }

    /**
     * Handle the Frequency "updated" event.
     */
    public function updated(Frequency $frequency): void
    {
        //
    }

    /**
     * Handle the Frequency "deleted" event.
     */
    public function deleted(Frequency $frequency): void
    {
        //
    }

    /**
     * Handle the Frequency "restored" event.
     */
    public function restored(Frequency $frequency): void
    {
        //
    }

    /**
     * Handle the Frequency "force deleted" event.
     */
    public function forceDeleted(Frequency $frequency): void
    {
        //
    }
}
