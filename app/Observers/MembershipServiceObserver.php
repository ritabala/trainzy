<?php

namespace App\Observers;

use App\Models\MembershipService;

class MembershipServiceObserver
{
    public function creating(MembershipService $membershipService): void
    {
        if (gym()) {
            $membershipService->gym_id = gym()->id;
        }
    }

    /**
     * Handle the MembershipService "created" event.
     */
    public function created(MembershipService $membershipService): void
    {
        //
    }

    /**
     * Handle the MembershipService "updated" event.
     */
    public function updated(MembershipService $membershipService): void
    {
        //
    }

    /**
     * Handle the MembershipService "deleted" event.
     */
    public function deleted(MembershipService $membershipService): void
    {
        //
    }

    /**
     * Handle the MembershipService "restored" event.
     */
    public function restored(MembershipService $membershipService): void
    {
        //
    }

    /**
     * Handle the MembershipService "force deleted" event.
     */
    public function forceDeleted(MembershipService $membershipService): void
    {
        //
    }
}
