<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GlobalCurrency;

class GlobalCurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GlobalCurrency::insert([
            [
                'name' => 'United States Dollar',
                'symbol' => '$',
                'code' => 'USD',
                'decimal_places' => 2,
                'decimal_point' => '.',
                'thousands_separator' => ',',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Euro',
                'symbol' => '€',
                'code' => 'EUR',
                'decimal_places' => 2,
                'decimal_point' => '.',
                'thousands_separator' => ',',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'British Pound',
                'symbol' => '£',
                'code' => 'GBP',
                'decimal_places' => 2,
                'decimal_point' => '.',
                'thousands_separator' => ',',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Indian Rupee',
                'symbol' => '₹',
                'code' => 'INR',
                'decimal_places' => 2,
                'decimal_point' => '.',
                'thousands_separator' => ',',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
} 