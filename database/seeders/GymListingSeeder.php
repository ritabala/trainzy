<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gym;
use App\Models\Currency;
use App\Models\Facility;
use App\Models\User;
use App\Models\GymReview;
use App\Models\GymListing;

class GymListingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gyms = Gym::all();

        foreach ($gyms as $gym) {
            $gymListing = $gym->gymListings()->create([
                'address' => $gym->address,
                'phone' => $gym->phone,
                'latitude' => fake()->latitude(),
                'longitude' => fake()->longitude(),
                'is_open_24_7' => fake()->boolean(),
                'is_free_trial' => fake()->boolean(),
                'gender' => fake()->randomElement(['male', 'female', 'unisex']),
                'about' => fake()->paragraph(10),
                'badges' => json_encode(fake()->randomElements(GymListing::BADGES, 3)),
            ]);

            $facilities = Facility::inRandomOrder()->take(5)->pluck('id')->toArray();

            $gymListing->gymFacilities()->createMany(array_map(function ($facility) {
                return [
                    'facility_id' => $facility,
                ];
            }, $facilities));

            $gymListing->timings()->createMany(array_map(function ($day) {
                return [
                    'day' => $day,
                    'open_time' => '09:00',
                    'close_time' => '17:00',
                ];
            }, ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']));

            $users = User::all()->pluck('id')->toArray();

            for ($i = 0; $i < 10; $i++) {
                $gymListing->reviews()->create([
                    'review' => fake()->paragraph(),
                    'rating' => fake()->numberBetween(1, 5),
                    'title' => fake()->sentence(),
                    'tags' => json_encode(fake()->randomElements(GymReview::TAGS, 3)),
                    'user_id' => fake()->randomElement($users),
                ]);
            }
        }
        
    }
} 