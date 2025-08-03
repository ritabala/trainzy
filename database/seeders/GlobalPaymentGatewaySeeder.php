<?php

namespace Database\Seeders;

use App\Models\GlobalPaymentGateway;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GlobalPaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GlobalPaymentGateway::create([
            'stripe_status' => false,
            'offline_method_status' => false,
        ]);
    }
}
