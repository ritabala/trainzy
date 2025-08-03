<?php

namespace Database\Seeders;

use App\Models\ActivityClass;
use App\Models\User;
use App\Models\AvailableTimeSlot;
use Illuminate\Database\Seeder;
use App\Models\Gym;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AvailableTimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gyms = Gym::all();
        
        foreach ($gyms as $gym) {
            // Get some activity classes and instructors
            $activityClasses = ActivityClass::where('gym_id', $gym->id)->get();
            $instructors = User::whereHas('roles', function($query) use ($gym) {
                $query->whereIn('name', ['admin-' . $gym->id, 'staff-' . $gym->id]);
            })->take(2)->get(); // Only take 2 instructors

            if ($activityClasses->isEmpty() || $instructors->isEmpty()) {
                return;
            }

            $slotsCreated = 0;
            // Set the base date to 3 months ago
            $base2025 = today()->subMonths(3);
            $today = Carbon::today();

            // Create historical weekly patterns (inactive) for 2025 only
            foreach ($activityClasses as $activityClass) {
                foreach ($instructors as $instructor) {
                    // Pattern 1: Early 2025 (Jan 1) - Multiple slots per day
                    $pattern1Date = $base2025->copy();
                    for ($day = 0; $day <= 6; $day++) {
                        try {
                            // Morning slot
                            AvailableTimeSlot::create([
                                'gym_id' => $gym->id,
                                'activity_class_id' => $activityClass->id,
                                'user_id' => $instructor->id,
                                'start_time' => '09:00:00',
                                'end_time' => '10:00:00',
                                'type' => 'weekly',
                                'day_of_week' => $day,
                                'status' => 'active',
                                'created_at' => $pattern1Date,
                                'updated_at' => $pattern1Date
                            ]);
                            $slotsCreated++;
                            
                            // Afternoon slot
                            AvailableTimeSlot::create([
                                'gym_id' => $gym->id,
                                'activity_class_id' => $activityClass->id,
                                'user_id' => $instructor->id,
                                'start_time' => '14:00:00',
                                'end_time' => '15:00:00',
                                'type' => 'weekly',
                                'day_of_week' => $day,
                                'status' => 'active',
                                'created_at' => $pattern1Date,
                                'updated_at' => $pattern1Date
                            ]);
                            $slotsCreated++;
                        } catch (\Exception $e) {
                            Log::error('Error creating 2025 pattern1 time slot: ' . $e->getMessage());
                        }
                    }

                    // Pattern 2: Spring 2025 (Apr 1) - 3 slots per day
                    $pattern2Date = Carbon::create(2025, 4, 1);
                    for ($day = 0; $day <= 6; $day++) {
                        try {
                            // Morning slot
                            AvailableTimeSlot::create([
                                'gym_id' => $gym->id,
                                'activity_class_id' => $activityClass->id,
                                'user_id' => $instructor->id,
                                'start_time' => '08:00:00',
                                'end_time' => '09:00:00',
                                'type' => 'weekly',
                                'day_of_week' => $day,
                                'status' => 'active',
                                'created_at' => $pattern2Date,
                                'updated_at' => $pattern2Date
                            ]);
                            $slotsCreated++;
                            
                            // Afternoon slot
                            AvailableTimeSlot::create([
                                'gym_id' => $gym->id,
                                'activity_class_id' => $activityClass->id,
                                'user_id' => $instructor->id,
                                'start_time' => '13:00:00',
                                'end_time' => '14:00:00',
                                'type' => 'weekly',
                                'day_of_week' => $day,
                                'status' => 'active',
                                'created_at' => $pattern2Date,
                                'updated_at' => $pattern2Date
                            ]);
                            $slotsCreated++;
                            
                            // Evening slot
                            AvailableTimeSlot::create([
                                'gym_id' => $gym->id,
                                'activity_class_id' => $activityClass->id,
                                'user_id' => $instructor->id,
                                'start_time' => '18:00:00',
                                'end_time' => '19:00:00',
                                'type' => 'weekly',
                                'day_of_week' => $day,
                                'status' => 'active',
                                'created_at' => $pattern2Date,
                                'updated_at' => $pattern2Date
                            ]);
                            $slotsCreated++;
                        } catch (\Exception $e) {
                            Log::error('Error creating 2025 pattern2 time slot: ' . $e->getMessage());
                        }
                    }

                    // Pattern 3: Summer 2025 (Jul 1) - 4 slots per day
                    $pattern3Date = Carbon::create(2025, 7, 1);
                    for ($day = 0; $day <= 6; $day++) {
                        try {
                            // Early morning slot
                            AvailableTimeSlot::create([
                                'gym_id' => $gym->id,
                                'activity_class_id' => $activityClass->id,
                                'user_id' => $instructor->id,
                                'start_time' => '07:00:00',
                                'end_time' => '08:00:00',
                                'type' => 'weekly',
                                'day_of_week' => $day,
                                'status' => 'active',
                                'created_at' => $pattern3Date,
                                'updated_at' => $pattern3Date
                            ]);
                            $slotsCreated++;
                            
                            // Morning slot
                            AvailableTimeSlot::create([
                                'gym_id' => $gym->id,
                                'activity_class_id' => $activityClass->id,
                                'user_id' => $instructor->id,
                                'start_time' => '10:00:00',
                                'end_time' => '11:00:00',
                                'type' => 'weekly',
                                'day_of_week' => $day,
                                'status' => 'active',
                                'created_at' => $pattern3Date,
                                'updated_at' => $pattern3Date
                            ]);
                            $slotsCreated++;
                            
                            // Afternoon slot
                            AvailableTimeSlot::create([
                                'gym_id' => $gym->id,
                                'activity_class_id' => $activityClass->id,
                                'user_id' => $instructor->id,
                                'start_time' => '15:00:00',
                                'end_time' => '16:00:00',
                                'type' => 'weekly',
                                'day_of_week' => $day,
                                'status' => 'active',
                                'created_at' => $pattern3Date,
                                'updated_at' => $pattern3Date
                            ]);
                            $slotsCreated++;
                            
                            // Evening slot
                            AvailableTimeSlot::create([
                                'gym_id' => $gym->id,
                                'activity_class_id' => $activityClass->id,
                                'user_id' => $instructor->id,
                                'start_time' => '19:00:00',
                                'end_time' => '20:00:00',
                                'type' => 'weekly',
                                'day_of_week' => $day,
                                'status' => 'active',
                                'created_at' => $pattern3Date,
                                'updated_at' => $pattern3Date
                            ]);
                            $slotsCreated++;
                        } catch (\Exception $e) {
                            Log::error('Error creating 2025 pattern3 time slot: ' . $e->getMessage());
                        }
                    }
                }
            }

            // Create date-specific slots for 2025 only (multiple slots per day)
            $dateSpecificDates = [
                Carbon::create(2025, 2, 10),
                Carbon::create(2025, 4, 15),
                Carbon::create(2025, 6, 1), // This will have 6 slots
                Carbon::create(2025, 8, 20),
                Carbon::create(2025, 11, 5)
            ];
            
            foreach ($dateSpecificDates as $date) {
                foreach ($activityClasses as $activityClass) {
                    foreach ($instructors as $instructor) {
                        try {
                            if ($date->eq(Carbon::create(2025, 6, 1))) {
                                // 6 slots for June 1, 2025
                                $slotTimes = [
                                    ['08:00:00', '09:00:00'],
                                    ['09:30:00', '10:30:00'],
                                    ['11:00:00', '12:00:00'],
                                    ['13:00:00', '14:00:00'],
                                    ['15:00:00', '16:00:00'],
                                    ['18:00:00', '19:00:00'],
                                ];
                                foreach ($slotTimes as [$start, $end]) {
                                    AvailableTimeSlot::create([
                                        'gym_id' => $gym->id,
                                        'activity_class_id' => $activityClass->id,
                                        'user_id' => $instructor->id,
                                        'start_time' => $start,
                                        'end_time' => $end,
                                        'type' => 'date_specific',
                                        'date' => $date->format('Y-m-d'),
                                        'status' => 'active'
                                    ]);
                                    $slotsCreated++;
                                }
                            } else {
                                // 2-3 slots for other dates
                                // Slot 1
                                AvailableTimeSlot::create([
                                    'gym_id' => $gym->id,
                                    'activity_class_id' => $activityClass->id,
                                    'user_id' => $instructor->id,
                                    'start_time' => '12:00:00',
                                    'end_time' => '13:00:00',
                                    'type' => 'date_specific',
                                    'date' => $date->format('Y-m-d'),
                                    'status' => 'active'
                                ]);
                                $slotsCreated++;
                                // Slot 2
                                AvailableTimeSlot::create([
                                    'gym_id' => $gym->id,
                                    'activity_class_id' => $activityClass->id,
                                    'user_id' => $instructor->id,
                                    'start_time' => '15:00:00',
                                    'end_time' => '16:00:00',
                                    'type' => 'date_specific',
                                    'date' => $date->format('Y-m-d'),
                                    'status' => 'active'
                                ]);
                                $slotsCreated++;
                                // Slot 3 (optional, for variety)
                                if ($date->month % 2 === 0) { // Only for even months
                                    AvailableTimeSlot::create([
                                        'gym_id' => $gym->id,
                                        'activity_class_id' => $activityClass->id,
                                        'user_id' => $instructor->id,
                                        'start_time' => '18:00:00',
                                        'end_time' => '19:00:00',
                                        'type' => 'date_specific',
                                        'date' => $date->format('Y-m-d'),
                                        'status' => 'active'
                                    ]);
                                    $slotsCreated++;
                                }
                            }
                        } catch (\Exception $e) {
                            Log::error('Error creating 2025 date-specific time slot: ' . $e->getMessage());
                        }
                    }
                }
            }

            // Log the total number of slots created
            Log::info("AvailableTimeSlotSeeder: Created {$slotsCreated} time slots");
            echo "Created {$slotsCreated} AvailableTimeSlot records\n";
        }
    }
} 