<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Gym;

class BodyMetricSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gyms = Gym::all();

        foreach ($gyms as $gym) {
            $types = [
                [
                    'name' => 'Body Weight', 
                    'slug' => 'body-weight', 
                    'description' => 'Body weight in kilograms',
                    'unit' => 'kg', 
                    'is_active' => true,
                    'display_order' => 1
                ],
                [
                    'name' => 'Body Fat', 
                    'slug' => 'body-fat', 
                    'description' => 'Body fat percentage',
                    'unit' => '%', 
                    'is_active' => true,
                    'display_order' => 2
                ],
                [   
                    'name' => 'Height', 
                    'slug' => 'height', 
                    'description' => 'Height in centimeters',
                    'unit' => 'cm', 
                    'is_active' => true,
                    'display_order' => 3
                ],
                [
                    'name' => 'BMI', 
                    'slug' => 'bmi', 
                    'description' => 'Body Mass Index',
                    'unit' => '', 
                    'is_active' => true,
                    'display_order' => 4
                ],
                [
                    'name' => 'Waist', 
                    'slug' => 'waist', 
                    'description' => 'Waist circumference in inches',
                    'unit' => 'in', 
                    'is_active' => true,
                    'display_order' => 5
                ],
                [
                    'name' => 'Neck', 
                    'slug' => 'neck', 
                    'description' => 'Neck circumference in inches',
                    'unit' => 'in', 
                    'is_active' => true,
                    'display_order' => 6
                ],
                [
                    'name' => 'Chest', 
                    'slug' => 'chest', 
                    'description' => 'Chest circumference in inches',
                    'unit' => 'in', 
                    'is_active' => true,
                    'display_order' => 7
                ],
                [
                    'name' => 'Upper Arm', 
                    'slug' => 'upper-arm', 
                    'description' => 'Upper arm circumference in inches',
                    'unit' => 'in', 
                    'is_active' => true,
                    'display_order' => 8
                ],
                [
                    'name' => 'Quadriceps', 
                    'slug' => 'quadriceps', 
                    'description' => 'Quadriceps circumference in inches',
                    'unit' => 'in', 
                    'is_active' => true,
                    'display_order' => 9
                ],
                [
                    'name' => 'Muscle Mass', 
                    'slug' => 'muscle-mass', 
                    'description' => 'Muscle mass in kilograms',
                    'unit' => 'kg', 
                    'is_active' => true,
                    'display_order' => 10
                ],
            ];

            $now = now();
            foreach ($types as $type) {
                DB::table('body_metric_types')->insert(array_merge($type, [
                    'gym_id' => $gym->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }
    }
}
