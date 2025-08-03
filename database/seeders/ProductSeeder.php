<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Tax;
use Illuminate\Database\Seeder;
use App\Models\Gym;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gyms = Gym::all();

        foreach ($gyms as $gym) {
            $products = [
                [
                    'gym_id' => $gym->id,
                    'name' => 'Protein Powder',
                    'product_code' => 'PROT' . fake()->unique()->numberBetween(1000, 9999),
                    'description' => 'Whey protein powder for muscle building',
                    'price' => 49.99,
                    'quantity' => 100,
                    'expiry_date' => '2025-12-31',
                    'taxes' => ['VAT', 'Service Tax']
                ],
                [
                    'gym_id' => $gym->id,
                    'name' => 'Pre-Workout',
                    'product_code' => 'PRE' . fake()->unique()->numberBetween(1000, 9999),
                    'description' => 'Energy boosting pre-workout supplement',
                    'price' => 39.99,
                    'quantity' => 50,
                    'expiry_date' => '2025-12-31',
                    'taxes' => ['IGST']
                ],
                [
                    'gym_id' => $gym->id,
                    'name' => 'Resistance Bands Set',
                    'product_code' => 'EQ' . fake()->unique()->numberBetween(1000, 9999),
                    'description' => 'Set of 5 resistance bands for strength training',
                    'price' => 29.99,
                    'quantity' => 30,
                    'expiry_date' => null,
                    'taxes' => ['SGST', 'CGST']
                ],
                [
                    'gym_id' => $gym->id,
                    'name' => 'Yoga Mat',
                    'product_code' => 'YOG' . fake()->unique()->numberBetween(1000, 9999),
                    'description' => 'Non-slip yoga mat for comfortable practice',
                    'price' => 19.99,
                    'quantity' => 20,
                    'expiry_date' => null,
                ]
                
            ];

            foreach ($products as $productData) {
                $taxes = $productData['taxes'] ?? [];
                unset($productData['taxes']);
                
                $product = Product::create($productData);
                
                if (!empty($taxes)) {
                    $taxIds = Tax::where('gym_id', $gym->id)->whereIn('tax_name', $taxes)->pluck('id')->toArray();
                    if (!empty($taxIds)) {
                        $product->taxes()->attach($taxIds, ['gym_id' => $gym->id]);
                    }
                }
            }
        }
    }
} 