<?php

namespace Database\Seeders;

use App\Models\Frequency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FrequencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $frequencies = [
            [
                'name' => 'Daily',
                'slug' => 'daily',
                'description' => 'Daily frequency',
                'is_active' => true,
            ],
            [
                'name' => 'Weekly',
                'slug' => 'weekly',
                'description' => 'Weekly frequency',
                'is_active' => true,
            ],
            [
                'name' => 'Monthly',
                'slug' => 'monthly',
                'description' => 'Monthly frequency',
                'is_active' => true,
            ],
            [
                'name' => 'Quarterly',
                'slug' => 'quarterly',
                'description' => 'Quarterly frequency',
                'is_active' => true,
            ],
            [
                'name' => 'Half Yearly',
                'slug' => 'half-yearly',
                'description' => 'Half yearly frequency',
                'is_active' => true,
            ],
            [
                'name' => 'Yearly',
                'slug' => 'yearly',
                'description' => 'Yearly frequency',
                'is_active' => true,
            ],
        ];

        foreach ($frequencies as $frequency) {
            Frequency::create($frequency);
        }
    }
}
