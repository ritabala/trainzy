<?php

namespace App\Observers;

use App\Models\RecurringSession;

class RecurringSessionObserver
{
    public function creating(RecurringSession $recurringSession): void
    {
        if (gym()) {
            $recurringSession->gym_id = gym()->id;
        }
    }

    /**
     * Handle the RecurringSession "created" event.
     */
    public function created(RecurringSession $recurringSession): void
    {
        //
    }

    /**
     * Handle the RecurringSession "updated" event.
     */
    public function updated(RecurringSession $recurringSession): void
    {
        //
    }

    /**
     * Handle the RecurringSession "deleted" event.
     */
    public function deleted(RecurringSession $recurringSession): void
    {
        //
    }

    /**
     * Handle the RecurringSession "restored" event.
     */
    public function restored(RecurringSession $recurringSession): void
    {
        //
    }

    /**
     * Handle the RecurringSession "force deleted" event.
     */
    public function forceDeleted(RecurringSession $recurringSession): void
    {
        //
    }
}
