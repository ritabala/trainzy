<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Gym;

class StaffTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gyms = Gym::all();

        foreach ($gyms as $gym) {
            $staffTypes = [
                [
                    'gym_id' => $gym->id,
                    'name' => 'Head Trainer',
                    'description' => 'Oversees training programs and staff trainers.',
                    'is_active' => true,
                ],
                [
                    'gym_id' => $gym->id,
                    'name' => 'Personal Trainer',
                    'description' => 'Provides one-on-one training sessions.',
                    'is_active' => true,
                ],
                [
                    'gym_id' => $gym->id,
                    'name' => 'Group Class Instructor',
                    'description' => 'Conducts group fitness classes (e.g., yoga, Zumba, HIIT).',
                    'is_active' => true,
                ],
                [
                    'gym_id' => $gym->id,
                    'name' => 'Rehabilitation Specialist',
                    'description' => 'Assists with injury recovery and therapy exercises.',
                    'is_active' => true,
                ],
            ];

            foreach ($staffTypes as $type) {
                DB::table('staff_types')->insert([
                    'gym_id' => $type['gym_id'],
                    'slug' => Str::slug($type['name']),
                    'name' => $type['name'],
                    'description' => $type['description'],
                    'is_active' => $type['is_active'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
} 