<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Gym;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gyms = Gym::all();

        foreach ($gyms as $gym) {
            DB::table('taxes')->insert([
                [
                    'gym_id' => $gym->id,
                    'tax_name' => 'VAT',
                    'tax_percent' => 20.00,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'gym_id' => $gym->id,
                    'tax_name' => 'Service Tax',
                    'tax_percent' => 5.00,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'gym_id' => $gym->id,
                    'tax_name' => 'IGST',
                    'tax_percent' => 12.00,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'gym_id' => $gym->id,
                    'tax_name' => 'SGST',
                    'tax_percent' => 6.00,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'gym_id' => $gym->id,
                    'tax_name' => 'CGST',
                    'tax_percent' => 6.00,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],

            ]);
        }
    }
} 