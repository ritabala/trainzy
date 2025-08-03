<?php

namespace App\Console\Commands;

use App\Models\Gym;
use App\Models\UserMembership;
use Illuminate\Console\Command;

class RenewMembersips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:renew-memberships';

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

            $memberships = UserMembership::where('auto_renewal', 1)
            ->whereDate('next_renewal_date', '=', $date->format('Y-m-d'))
            ->where('gym_id', $gym->id)
            ->whereDoesntHave('childMembership')
            ->get();

            foreach ($memberships as $membership) {
                $membership->renew();
             
                
                $this->info('USER MEMBERSHIP ID: ' . $membership->id . ' Renewed');
            }
            
        }
    }
}

