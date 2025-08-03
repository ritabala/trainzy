<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Gym;
use App\Models\UserMembership;

class ActivateUpcomingMembersips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:activate-upcoming-membersips';

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

            $memberships = UserMembership::where('membership_status', 'upcoming')
            ->whereDate('membership_start_date', '=', $date->format('Y-m-d'))
            ->where('gym_id', $gym->id)
            ->get();

            foreach ($memberships as $membership) {
                $membership->activateUpcoming();
                $this->info('USER MEMBERSHIP ID: ' . $membership->id . ' Activated');
            }
            
        }
    }
}
