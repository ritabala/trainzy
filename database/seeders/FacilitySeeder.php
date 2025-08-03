<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            'weight_training' => '🏋️‍♀️',
            'cardio' => '🚴',
            'yoga' => '🧘‍♀️',
            'zumba' => '💃',
            'pilates' => '🧘‍♀️',
            'spinning' => '🚴',
            'boxing' => '🥊',
            'martial_arts' => '🥋',
            'parking' => '🅿️',
            'shower' => '🚿',
            'locker_room' => '🔒',
            'sauna' => '🔥',
            'steam_room' => '🔥',
            'jacuzzi' => '🚿',
            'crossfit' => '💪',
            'zumba_yoga' => '🧘‍♀️',
            'swimming_pool' => '🏊',
            'cafe' => '☕',
            'wifi' => '🌐',
            'childcare' => '👶',
            'personal_trainer' => '💪',
        ];

        foreach ($facilities as $name => $icon) {
            Facility::create([
                'name' => $name,
                'icon' => $icon,
            ]);
        }
    }
}
