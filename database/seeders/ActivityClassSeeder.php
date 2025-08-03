<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Gym;

class ActivityClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $gyms = Gym::all();

        foreach ($gyms as $gym) {
            DB::table('activity_classes')->insert([
                ['gym_id' => $gym->id, 'name' => 'Gym Access', 'description' => 'Access to the gym', 'duration' => 60, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['gym_id' => $gym->id, 'name' => 'Swimming Pool Access', 'description' => 'Access to the swimming pool', 'duration' => 35, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['gym_id' => $gym->id, 'name' => 'Yoga', 'description' => 'Yoga classes', 'duration' => 90, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['gym_id' => $gym->id, 'name' => 'Zumba', 'description' => 'Zumba classes', 'duration' => 20, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['gym_id' => $gym->id, 'name' => 'Aerobics', 'description' => 'Aerobics classes', 'duration' => 45, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()]
            ]);
        }
    }
}
