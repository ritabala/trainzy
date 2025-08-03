<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GymGuide;

class GymGuideSeeder extends Seeder
{
    public function run(): void
    {
        GymGuide::truncate();
        GymGuide::insert([
            [
                'title' => 'How to Choose the Right Gym',
                'description' => 'Essential factors to consider when selecting your perfect fitness home.',
                'icon' => '<svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>',
                'link' => 'https://example.com/guides/choose-gym',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Top 10 Gyms in Your City',
                'description' => 'Discover the highest-rated fitness centers in your area.',
                'icon' => '<svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                'link' => 'https://example.com/guides/top-gyms',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Gym vs Personal Training Studios',
                'description' => 'Compare the benefits of traditional gyms vs specialized training facilities.',
                'icon' => '<svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>',
                'link' => 'https://example.com/guides/gym-vs-pt',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
