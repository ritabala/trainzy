<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LatestOffer;

class LatestOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LatestOffer::insert([
            [
                'type' => 'Discount',
                'title' => 'Summer Special',
                'description' => 'Get 20% off on all memberships this summer!',
                'expires_at' => now()->addDays(30),
                'trainer_count' => 5,
                'savings' => 100.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'Bundle',
                'title' => 'Trainer + Nutrition Combo',
                'description' => 'Sign up for a trainer and get a free nutrition plan.',
                'expires_at' => now()->addDays(2),
                'trainer_count' => 2,
                'savings' => 50.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'Limited Time',
                'title' => 'First Month Free',
                'description' => 'Join now and get your first month absolutely free!',
                'expires_at' => now()->addHours(12),
                'trainer_count' => 1,
                'savings' => 75.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'Flash Deal',
                'title' => 'Expired Offer',
                'description' => 'This offer has already expired.',
                'expires_at' => now()->subDays(1),
                'trainer_count' => 0,
                'savings' => 0.00,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(1),
            ],
            [
                'type' => 'Annual',
                'title' => 'Year-Long Savings',
                'description' => 'Save big with our annual membership offer!',
                'expires_at' => now()->addMonths(6),
                'trainer_count' => 10,
                'savings' => 200.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
