<?php

namespace Database\Seeders;

use App\Models\Gym;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Currency;
use Illuminate\Support\Str;

class GymSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $gyms = [
            'Fitness First',
        ];

        foreach ($gyms as $gym) { 
            $gymName = $gym;
            $gymSlug = Str::slug($gymName);

            Gym::create([
                'slug' => $gymSlug,
                'name' => $gymName,
                'address' => fake()->address(),
                'phone' => fake()->phoneNumber(),
                'email' => fake()->email(),
                'website' => fake()->url(),
                'timezone' => 'Asia/Kolkata',
                'locale' => 'en'
            ]);
        }
    }
}
