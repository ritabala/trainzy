<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GlobalSetting;

class GlobalSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GlobalSetting::create([
            'app_name' => 'Trainzy',
            'locale' => 'en',
            'currency_id' => 1,
        ]);
    }
}
