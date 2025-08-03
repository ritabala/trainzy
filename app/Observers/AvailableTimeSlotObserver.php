<?php

namespace App\Observers;

use App\Models\AvailableTimeSlot;

class AvailableTimeSlotObserver
{
    public function creating(AvailableTimeSlot $availableTimeSlot): void
    {
        if (gym()) {
            $availableTimeSlot->gym_id = gym()->id;
        }
    }

    /**
     * Handle the AvailableTimeSlot "created" event.
     */
    public function created(AvailableTimeSlot $availableTimeSlot): void
    {
        //
    }

    /**
     * Handle the AvailableTimeSlot "updated" event.
     */
    public function updated(AvailableTimeSlot $availableTimeSlot): void
    {
        //
    }

    /**
     * Handle the AvailableTimeSlot "deleted" event.
     */
    public function deleted(AvailableTimeSlot $availableTimeSlot): void
    {
        //
    }

    /**
     * Handle the AvailableTimeSlot "restored" event.
     */
    public function restored(AvailableTimeSlot $availableTimeSlot): void
    {
        //
    }

    /**
     * Handle the AvailableTimeSlot "force deleted" event.
     */
    public function forceDeleted(AvailableTimeSlot $availableTimeSlot): void
    {
        //
    }
}
