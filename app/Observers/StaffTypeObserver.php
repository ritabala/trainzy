<?php

namespace App\Observers;

use App\Models\StaffType;

class StaffTypeObserver
{
    public function creating(StaffType $staffType): void
    {
        if (gym()) {
            $staffType->gym_id = gym()->id;
        }
    }

    /**
     * Handle the StaffType "created" event.
     */
    public function created(StaffType $staffType): void
    {
        //
    }

    /**
     * Handle the StaffType "updated" event.
     */
    public function updated(StaffType $staffType): void
    {
        //
    }

    /**
     * Handle the StaffType "deleted" event.
     */
    public function deleted(StaffType $staffType): void
    {
        //
    }

    /**
     * Handle the StaffType "restored" event.
     */
    public function restored(StaffType $staffType): void
    {
        //
    }

    /**
     * Handle the StaffType "force deleted" event.
     */
    public function forceDeleted(StaffType $staffType): void
    {
        //
    }
}
