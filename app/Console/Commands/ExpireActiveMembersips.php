<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Gym;
use App\Models\UserMembership;
use App\Notifications\MembershipExpired;

class ExpireActiveMembersips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-active-membersips';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $gyms = Gym::select('id', 'name')->get();

        foreach ($gyms as $gym) {
            $date = now($gym->timezone);

            $memberships = UserMembership::where('membership_status', 'active')
            ->where('auto_renewal', 0)
            ->whereDate('membership_expiry_date', '<', $date->format('Y-m-d'))
            ->where('gym_id', $gym->id)
            ->get();

            foreach ($memberships as $membership) {
                $membership->membership_status = 'expired';
                $membership->save();

                $membership->user->notify(new MembershipExpired($membership));
                $this->info('USER MEMBERSHIP ID: ' . $membership->id . ' Expired');
            }
            
        }
    }
}
