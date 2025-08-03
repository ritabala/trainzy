<?php

namespace App\Observers;

use App\Models\ProgressPhoto;

class ProgressPhotoObserver
{
    public function creating(ProgressPhoto $progressPhoto): void
    {
        if (gym()) {
            $progressPhoto->gym_id = gym()->id;
        }
    }

    /**
     * Handle the ProgressPhoto "created" event.
     */
    public function created(ProgressPhoto $progressPhoto): void
    {
        //
    }

    /**
     * Handle the ProgressPhoto "updated" event.
     */
    public function updated(ProgressPhoto $progressPhoto): void
    {
        //
    }

    /**
     * Handle the ProgressPhoto "deleted" event.
     */
    public function deleted(ProgressPhoto $progressPhoto): void
    {
        //
    }

    /**
     * Handle the ProgressPhoto "restored" event.
     */
    public function restored(ProgressPhoto $progressPhoto): void
    {
        //
    }

    /**
     * Handle the ProgressPhoto "force deleted" event.
     */
    public function forceDeleted(ProgressPhoto $progressPhoto): void
    {
        //
    }
}
