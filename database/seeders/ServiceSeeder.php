<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Gym;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $gyms = Gym::all();

        foreach ($gyms as $gym) {
            DB::table('services')->insert([
                ['gym_id' => $gym->id, 'name' => '5 Complimemntary Gym Access', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['gym_id' => $gym->id, 'name' => '3 Complimemntary Swimming Pool Access', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['gym_id' => $gym->id, 'name' => '2 Complimemntary Yoga', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['gym_id' => $gym->id, 'name' => '1 Complimemntary Zumba', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['gym_id' => $gym->id, 'name' => '4 Complimemntary Aerobics', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()]
            ]);
        }
    }
}
