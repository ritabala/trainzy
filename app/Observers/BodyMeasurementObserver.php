<?php

namespace App\Observers;

use App\Models\BodyMeasurement;

class BodyMeasurementObserver
{
    public function creating(BodyMeasurement $bodyMeasurement): void
    {
        if (gym()) {
            $bodyMeasurement->gym_id = gym()->id;
        }
    }

    /**
     * Handle the BodyMeasurement "created" event.
     */
    public function created(BodyMeasurement $bodyMeasurement): void
    {
        //
    }

    /**
     * Handle the BodyMeasurement "updated" event.
     */
    public function updated(BodyMeasurement $bodyMeasurement): void
    {
        //
    }

    /**
     * Handle the BodyMeasurement "deleted" event.
     */
    public function deleted(BodyMeasurement $bodyMeasurement): void
    {
        //
    }

    /**
     * Handle the BodyMeasurement "restored" event.
     */
    public function restored(BodyMeasurement $bodyMeasurement): void
    {
        //
    }

    /**
     * Handle the BodyMeasurement "force deleted" event.
     */
    public function forceDeleted(BodyMeasurement $bodyMeasurement): void
    {
        //
    }
}
