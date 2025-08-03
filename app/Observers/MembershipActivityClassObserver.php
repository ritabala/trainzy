<?php

namespace App\Observers;

use App\Models\MembershipActivityClass;

class MembershipActivityClassObserver
{
    public function creating(MembershipActivityClass $membershipActivityClass): void
    {
        if (gym()) {
            $membershipActivityClass->gym_id = gym()->id;
        }
    }

    /**
     * Handle the MembershipActivityClass "created" event.
     */
    public function created(MembershipActivityClass $membershipActivityClass): void
    {
        //
    }

    /**
     * Handle the MembershipActivityClass "updated" event.
     */
    public function updated(MembershipActivityClass $membershipActivityClass): void
    {
        //
    }

    /**
     * Handle the MembershipActivityClass "deleted" event.
     */
    public function deleted(MembershipActivityClass $membershipActivityClass): void
    {
        //
    }

    /**
     * Handle the MembershipActivityClass "restored" event.
     */
    public function restored(MembershipActivityClass $membershipActivityClass): void
    {
        //
    }

    /**
     * Handle the MembershipActivityClass "force deleted" event.
     */
    public function forceDeleted(MembershipActivityClass $membershipActivityClass): void
    {
        //
    }
}
