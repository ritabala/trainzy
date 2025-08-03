<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Currency;
use App\Models\Gym;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gym = Gym::first();
        
        Currency::insert([
            [
                'gym_id' => $gym->id,
                'name' => 'United States Dollar',
                'symbol' => '$',
                'code' => 'USD',
                'decimal_places' => 2,
                'decimal_point' => '.',
                'thousands_separator' => ',',
            ],
            [
                'gym_id' => $gym->id,
                'name' => 'Euro',
                'symbol' => '€',
                'code' => 'EUR',
                'decimal_places' => 2,
                'decimal_point' => '.',
                'thousands_separator' => ',',
            ],
            [
                'gym_id' => $gym->id,
                'name' => 'British Pound',
                'symbol' => '£',
                'code' => 'GBP',
                'decimal_places' => 2,
                'decimal_point' => '.',
                'thousands_separator' => ',',
            ],
            [
                'gym_id' => $gym->id,
                'name' => 'Indian Rupee',
                'symbol' => '₹',
                'code' => 'INR',
                'decimal_places' => 2,
                'decimal_point' => '.',
                'thousands_separator' => ',',
            ]
        ]);

        $gym->update([
            'currency_id' => Currency::where('code', 'USD')->first()->id,
        ]);
    }
}