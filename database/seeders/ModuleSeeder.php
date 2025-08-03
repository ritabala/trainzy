<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Module;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default modules
        $defaultModules = [
            'dashboard' => false,
            'services' => false,
            'activity_classes' => false,
            'staff_schedule' => false,
            'memberships' => false,
            'members' => false,
            'staff' => false,
            'invoices' => false,
            'payments' => false,
            'reports' => false,
            'role_management' => false,
            'staff_attendance' => false,
            'member_attendance' => false,
            'settings' => false,
        ];

        // Additional modules
        $additionalModules = [
            'scan_attendance' => false,
            'messages' => false,
            'body_metrics' => false,
        ];

        // Insert default modules
        foreach ($defaultModules as $moduleName => $isEnabled) {
            Module::create([
                'name' => $moduleName,
                'status' => 'active',
                'is_additional' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert additional modules
        foreach ($additionalModules as $moduleName => $isEnabled) {
            Module::create([
                'name' => $moduleName,
                'status' => 'active',
                'is_additional' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
} 