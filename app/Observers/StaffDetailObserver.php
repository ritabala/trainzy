<?php

namespace App\Observers;

use App\Models\StaffDetail;

class StaffDetailObserver
{
    public function creating(StaffDetail $staffDetail): void
    {
        if (gym()) {
            $staffDetail->gym_id = gym()->id;
        }
    }

    /**
     * Handle the StaffDetail "created" event.
     */
    public function created(StaffDetail $staffDetail): void
    {
        //
    }

    /**
     * Handle the StaffDetail "updated" event.
     */
    public function updated(StaffDetail $staffDetail): void
    {
        //
    }

    /**
     * Handle the StaffDetail "deleted" event.
     */
    public function deleted(StaffDetail $staffDetail): void
    {
        //
    }

    /**
     * Handle the StaffDetail "restored" event.
     */
    public function restored(StaffDetail $staffDetail): void
    {
        //
    }

    /**
     * Handle the StaffDetail "force deleted" event.
     */
    public function forceDeleted(StaffDetail $staffDetail): void
    {
        //
    }
}
