<?php

namespace App\Observers;

use App\Models\BodyMeasurementValue;

class BodyMeasurementValueObserver
{
    public function creating(BodyMeasurementValue $bodyMeasurementValue): void
    {
        if (gym()) {
            $bodyMeasurementValue->gym_id = gym()->id;
        }
    }

    /**
     * Handle the BodyMeasurementValue "created" event.
     */
    public function created(BodyMeasurementValue $bodyMeasurementValue): void
    {
        //
    }

    /**
     * Handle the BodyMeasurementValue "updated" event.
     */
    public function updated(BodyMeasurementValue $bodyMeasurementValue): void
    {
        //
    }

    /**
     * Handle the BodyMeasurementValue "deleted" event.
     */
    public function deleted(BodyMeasurementValue $bodyMeasurementValue): void
    {
        //
    }

    /**
     * Handle the BodyMeasurementValue "restored" event.
     */
    public function restored(BodyMeasurementValue $bodyMeasurementValue): void
    {
        //
    }

    /**
     * Handle the BodyMeasurementValue "force deleted" event.
     */
    public function forceDeleted(BodyMeasurementValue $bodyMeasurementValue): void
    {
        //
    }
}
