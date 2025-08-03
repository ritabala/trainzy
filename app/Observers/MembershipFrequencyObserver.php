<?php

namespace App\Observers;

use App\Models\MembershipFrequency;

class MembershipFrequencyObserver
{
    public function creating(MembershipFrequency $membershipFrequency): void
    {
        if (gym()) {
            $membershipFrequency->gym_id = gym()->id;
        }
    }

    /**
     * Handle the MembershipFrequency "created" event.
     */
    public function created(MembershipFrequency $membershipFrequency): void
    {
        //
    }

    /**
     * Handle the MembershipFrequency "updated" event.
     */
    public function updated(MembershipFrequency $membershipFrequency): void
    {
        //
    }

    /**
     * Handle the MembershipFrequency "deleted" event.
     */
    public function deleted(MembershipFrequency $membershipFrequency): void
    {
        //
    }

    /**
     * Handle the MembershipFrequency "restored" event.
     */
    public function restored(MembershipFrequency $membershipFrequency): void
    {
        //
    }

    /**
     * Handle the MembershipFrequency "force deleted" event.
     */
    public function forceDeleted(MembershipFrequency $membershipFrequency): void
    {
        //
    }
}
