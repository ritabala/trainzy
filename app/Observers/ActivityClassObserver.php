<?php

namespace App\Observers;

use App\Models\ActivityClass;

class ActivityClassObserver
{
    public function creating(ActivityClass $activityClass): void
    {
        if (gym()) {
            $activityClass->gym_id = gym()->id;
        }
    }

    /**
     * Handle the ActivityClass "created" event.
     */
    public function created(ActivityClass $activityClass): void
    {
        //
    }

    /**
     * Handle the ActivityClass "updated" event.
     */
    public function updated(ActivityClass $activityClass): void
    {
        //
    }

    /**
     * Handle the ActivityClass "deleted" event.
     */
    public function deleted(ActivityClass $activityClass): void
    {
        //
    }

    /**
     * Handle the ActivityClass "restored" event.
     */
    public function restored(ActivityClass $activityClass): void
    {
        //
    }

    /**
     * Handle the ActivityClass "force deleted" event.
     */
    public function forceDeleted(ActivityClass $activityClass): void
    {
        //
    }
}
