<?php

namespace App\Observers;

use App\Models\UserMembership;

class UserMembershipObserver
{
    public function creating(UserMembership $userMembership): void
    {
        if (gym()) {
            $userMembership->gym_id = gym()->id;
        }
    }

    /**
     * Handle the UserMembership "created" event.
     */
    public function created(UserMembership $userMembership): void
    {
        //
    }

    /**
     * Handle the UserMembership "updated" event.
     */
    public function updated(UserMembership $userMembership): void
    {
        //
    }

    /**
     * Handle the UserMembership "deleted" event.
     */
    public function deleted(UserMembership $userMembership): void
    {
        //
    }

    /**
     * Handle the UserMembership "restored" event.
     */
    public function restored(UserMembership $userMembership): void
    {
        //
    }

    /**
     * Handle the UserMembership "force deleted" event.
     */
    public function forceDeleted(UserMembership $userMembership): void
    {
        //
    }
}
