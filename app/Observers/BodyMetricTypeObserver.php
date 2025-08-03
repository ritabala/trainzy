<?php

namespace App\Observers;

use App\Models\BodyMetricType;

class BodyMetricTypeObserver
{
    public function creating(BodyMetricType $bodyMetricType): void
    {
        if (gym()) {
            $bodyMetricType->gym_id = gym()->id;
        }
    }

    /**
     * Handle the BodyMetricType "created" event.
     */
    public function created(BodyMetricType $bodyMetricType): void
    {
        //
    }

    /**
     * Handle the BodyMetricType "updated" event.
     */
    public function updated(BodyMetricType $bodyMetricType): void
    {
        //
    }

    /**
     * Handle the BodyMetricType "deleted" event.
     */
    public function deleted(BodyMetricType $bodyMetricType): void
    {
        //
    }

    /**
     * Handle the BodyMetricType "restored" event.
     */
    public function restored(BodyMetricType $bodyMetricType): void
    {
        //
    }

    /**
     * Handle the BodyMetricType "force deleted" event.
     */
    public function forceDeleted(BodyMetricType $bodyMetricType): void
    {
        //
    }
}
