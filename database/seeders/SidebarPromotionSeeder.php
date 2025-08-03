<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SidebarPromotion;

class SidebarPromotionSeeder extends Seeder
{
    public function run(): void
    {
        SidebarPromotion::truncate();
        SidebarPromotion::insert([
            [
                'type' => 'flash_offer',
                'title' => 'Flash Sale: 50% Off',
                'description' => 'Get 50% off on all memberships for the next 24 hours!',
                'expires_in' => '24 hours',
                'trainer_count' => null,
                'savings' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'top_trainers',
                'title' => 'Meet Our Top Trainers',
                'description' => 'Train with the best certified trainers in the city.',
                'expires_in' => null,
                'trainer_count' => 12,
                'savings' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'corporate',
                'title' => 'Corporate Wellness Savings',
                'description' => 'Special rates for corporate groups. Save more together!',
                'expires_in' => null,
                'trainer_count' => null,
                'savings' => 'Save up to 30%',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
