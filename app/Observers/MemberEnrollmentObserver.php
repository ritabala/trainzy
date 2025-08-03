<?php

namespace App\Observers;

use App\Models\MemberEnrollment;

class MemberEnrollmentObserver
{
    public function creating(MemberEnrollment $memberEnrollment): void
    {
        if (gym()) {
            $memberEnrollment->gym_id = gym()->id;
        }
    }

    /**
     * Handle the MemberEnrollment "created" event.
     */
    public function created(MemberEnrollment $memberEnrollment): void
    {
        //
    }

    /**
     * Handle the MemberEnrollment "updated" event.
     */
    public function updated(MemberEnrollment $memberEnrollment): void
    {
        //
    }

    /**
     * Handle the MemberEnrollment "deleted" event.
     */
    public function deleted(MemberEnrollment $memberEnrollment): void
    {
        //
    }

    /**
     * Handle the MemberEnrollment "restored" event.
     */
    public function restored(MemberEnrollment $memberEnrollment): void
    {
        //
    }

    /**
     * Handle the MemberEnrollment "force deleted" event.
     */
    public function forceDeleted(MemberEnrollment $memberEnrollment): void
    {
        //
    }
}
