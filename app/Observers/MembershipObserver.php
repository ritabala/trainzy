<?php

namespace App\Observers;

use App\Models\Membership;

class MembershipObserver
{
    public function creating(Membership $membership): void
    {
        if (gym()) {
            $membership->gym_id = gym()->id;
        }
    }

    /**
     * Handle the Membership "created" event.
     */
    public function created(Membership $membership): void
    {
        //
    }

    /**
     * Handle the Membership "updated" event.
     */
    public function updated(Membership $membership): void
    {
        //
    }

    /**
     * Handle the Membership "deleted" event.
     */
    public function deleted(Membership $membership): void
    {
        //
    }

    /**
     * Handle the Membership "restored" event.
     */
    public function restored(Membership $membership): void
    {
        //
    }

    /**
     * Handle the Membership "force deleted" event.
     */
    public function forceDeleted(Membership $membership): void
    {
        //
    }
}
