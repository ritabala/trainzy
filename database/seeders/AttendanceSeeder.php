<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\User;
use App\Models\ActivityClass;
use App\Models\AvailableTimeSlot;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\Gym;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $gyms = Gym::all();
        
        foreach ($gyms as $gym) {

            $members = User::whereHas('roles', fn ($q) => $q->where('name', 'member-' . $gym->id))->get();
            $staff = User::whereHas('roles', function ($q) use ($gym) {
                $q->whereIn('name', ['admin-' . $gym->id, 'staff-' . $gym->id]);
            })->get(); 
            $activityClasses = ActivityClass::where('gym_id', $gym->id)->get();

            // Seed 20 member attendances
            foreach ($members->random(min(20, $members->count())) as $member) {
                $activityClass = $activityClasses->random();
                $timeSlot = AvailableTimeSlot::where('activity_class_id', $activityClass->id)
                    ->where('status', 'active')
                    ->inRandomOrder()
                    ->first();

                if ($timeSlot) {
                    Attendance::create([
                        'gym_id' => $gym->id,
                        'user_id' => $member->id,
                        'role_type' => 'member',
                        'activity_class_id' => $activityClass->id,
                        'available_time_slot_id' => $timeSlot->id,
                        'check_in_at' => $in = Carbon::now()->subHours(rand(1, 4)),
                        'check_out_at' => $in->copy()->addMinutes(60),
                        'status' => 'present',
                        'method' => 'scanner',
                        'notes' => null,
                    ]);
                }
            }

            // Seed 10 staff attendances
            foreach ($staff->random(min(10, $staff->count())) as $person) {
                $activityClass = $activityClasses->random();
                $timeSlot = AvailableTimeSlot::where('activity_class_id', $activityClass->id)
                    ->where('status', 'active')
                    ->inRandomOrder()
                    ->first();

                if ($timeSlot) {
                    $role = $person->roles->first()->name; // Get the actual role (admin or staff)
                    $roleType = str_contains($role, 'admin') ? 'admin' : 'staff';
                    Attendance::create([
                        'gym_id' => $gym->id,
                        'user_id' => $person->id,
                        'role_type' => $roleType,
                        'activity_class_id' => $activityClass->id,
                        'available_time_slot_id' => $timeSlot->id,
                        'check_in_at' => $in = Carbon::now()->subHours(rand(1, 2)),
                        'check_out_at' => $in->copy()->addMinutes(90),
                        'status' => 'present',
                        'method' => 'manual',
                        'notes' => 'Staff marked manually',
                    ]);
                }
            }
        }
    }
}
