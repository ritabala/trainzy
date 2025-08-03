<?php

namespace Database\Seeders;

use App\Models\Membership;
use App\Models\Frequency;
use App\Models\Service;
use App\Models\ActivityClass;
use App\Models\Gym;
use App\Models\MembershipFrequency;
use App\Models\MembershipService;
use App\Models\MembershipActivityClass;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gyms = Gym::all();
        $frequencies = Frequency::all();


        $monthlyFrequency = $frequencies->filter(function ($frequency) {
            return $frequency->slug === 'monthly';
        })->first();
        $quarterlyFrequency = $frequencies->filter(function ($frequency) {
            return $frequency->slug === 'quarterly';
        })->first();
        $halfYearlyFrequency = $frequencies->filter(function ($frequency) {
            return $frequency->slug === 'half-yearly';
        })->first();
        $yearlyFrequency = $frequencies->filter(function ($frequency) {
            return $frequency->slug === 'yearly';
        })->first();


        foreach ($gyms as $gym) {
            // Get all services and activity classes with their IDs
            $services = Service::where('gym_id', $gym->id)->get()->pluck('id')->toArray();
            $activityClasses = ActivityClass::where('gym_id', $gym->id)->get()->pluck('id')->toArray();

            $memberships = [
                [
                    'name' => 'Basic Membership',
                    'tagline' => 'Perfect for beginners',
                    'description' => 'Access to basic gym facilities and equipment. Perfect for those just starting their fitness journey.',
                    'is_active' => true,
                    'gym_id' => $gym->id,
                    'frequencies' => [
                        ['frequency_id' => $monthlyFrequency->id, 'price' => 999.00],
                        ['frequency_id' => $quarterlyFrequency->id, 'price' => 2799.00],
                        ['frequency_id' => $halfYearlyFrequency->id, 'price' => 4999.00],
                        ['frequency_id' => $yearlyFrequency->id, 'price' => 8999.00],
                    ],
                    'services' => [$services[0]], // 5 Complimemntary Gym Access
                    'activity_classes' => [$activityClasses[0]], // Gym Access
                ],
                [
                    'name' => 'Premium Membership',
                    'tagline' => 'For serious fitness enthusiasts',
                    'description' => 'Full access to all gym facilities, including premium equipment and priority booking for classes.',
                    'is_active' => true,
                    'gym_id' => $gym->id,
                    'frequencies' => [
                        ['frequency_id' => $monthlyFrequency->id, 'price' => 1999.00],
                        ['frequency_id' => $quarterlyFrequency->id, 'price' => 5499.00],
                        ['frequency_id' => $halfYearlyFrequency->id, 'price' => 9999.00],
                        ['frequency_id' => $yearlyFrequency->id, 'price' => 17999.00],
                    ],
                    'services' => [
                        $services[0], // 5 Complimemntary Gym Access
                        $services[1], // 3 Complimemntary Swimming Pool Access
                        $services[2], // 2 Complimemntary Yoga
                    ],
                    'activity_classes' => [
                        $activityClasses[0], // Gym Access
                        $activityClasses[1], // Swimming Pool Access
                        $activityClasses[2], // Yoga
                    ],
                ],
                [
                    'name' => 'VIP Membership',
                    'tagline' => 'The ultimate fitness experience',
                    'description' => 'Exclusive access to all facilities, personal trainer sessions, and premium amenities.',
                    'is_active' => true,
                    'gym_id' => $gym->id,
                    'frequencies' => [
                        ['frequency_id' => $monthlyFrequency->id, 'price' => 2999.00],
                        ['frequency_id' => $quarterlyFrequency->id, 'price' => 7999.00],
                        ['frequency_id' => $halfYearlyFrequency->id, 'price' => 14999.00],
                        ['frequency_id' => $yearlyFrequency->id, 'price' => 26999.00],
                    ],
                    'services' => $services, // All services
                    'activity_classes' => $activityClasses, // All activity classes
                ],
            ];

            foreach ($memberships as $membershipData) {
                // Extract the related data
                $frequencies = $membershipData['frequencies'];
                $services = $membershipData['services'];
                $activityClasses = $membershipData['activity_classes'];
                
                // Remove the related data from the membership data
                unset($membershipData['frequencies']);
                unset($membershipData['services']);
                unset($membershipData['activity_classes']);
                
                // Create the membership
                $membership = Membership::create($membershipData);
                
                // Create membership frequencies
                foreach ($frequencies as $frequency) {
                    MembershipFrequency::create([
                        'membership_id' => $membership->id,
                        'frequency_id' => $frequency['frequency_id'],
                        'price' => $frequency['price'],
                        'gym_id' => $gym->id,
                    ]);
                }
                
                // Create membership services
                foreach ($services as $serviceId) {
                    MembershipService::create([
                        'membership_id' => $membership->id,
                        'service_id' => $serviceId,
                        'gym_id' => $gym->id,
                    ]);
                }
                
                // Create membership activity classes
                foreach ($activityClasses as $activityClassId) {
                    MembershipActivityClass::create([
                        'membership_id' => $membership->id,
                        'activity_class_id' => $activityClassId,
                        'gym_id' => $gym->id,
                    ]);
                }
            }
        }
    }
} 