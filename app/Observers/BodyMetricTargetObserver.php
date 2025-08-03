<?php

namespace App\Observers;

use App\Models\BodyMetricTarget;

class BodyMetricTargetObserver
{
    public function creating(BodyMetricTarget $bodyMetricTarget): void
    {
        if (gym()) {
            $bodyMetricTarget->gym_id = gym()->id;
        }
    }

    /**
     * Handle the BodyMetricTarget "created" event.
     */
    public function created(BodyMetricTarget $bodyMetricTarget): void
    {
        //
    }

    /**
     * Handle the BodyMetricTarget "updated" event.
     */
    public function updated(BodyMetricTarget $bodyMetricTarget): void
    {
        //
    }

    /**
     * Handle the BodyMetricTarget "deleted" event.
     */
    public function deleted(BodyMetricTarget $bodyMetricTarget): void
    {
        //
    }

    /**
     * Handle the BodyMetricTarget "restored" event.
     */
    public function restored(BodyMetricTarget $bodyMetricTarget): void
    {
        //
    }

    /**
     * Handle the BodyMetricTarget "force deleted" event.
     */
    public function forceDeleted(BodyMetricTarget $bodyMetricTarget): void
    {
        //
    }
}
