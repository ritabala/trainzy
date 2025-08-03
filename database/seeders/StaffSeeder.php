<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Staff;
use App\Models\StaffType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Gym;
use App\Models\StaffDetails;
use App\Services\QrCodeService;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gyms = Gym::all();

        foreach ($gyms as $gym) {
            // Get staff types
            $headTrainerType = StaffType::where('name', 'Head Trainer')->where('gym_id', $gym->id)->first();
            $personalTrainerType = StaffType::where('name', 'Personal Trainer')->where('gym_id', $gym->id)->first();
            $groupClassInstructorType = StaffType::where('name', 'Group Class Instructor')->where('gym_id', $gym->id)->first();
            $rehabSpecialistType = StaffType::where('name', 'Rehabilitation Specialist')->where('gym_id', $gym->id)->first();

            // Sample staff data
            $staffMembers = [
                [
                    // User table data
                    'user' => [
                        'gym_id' => $gym->id,
                        'name' => fake()->name(),
                        'email' => fake()->email(),
                        'password' => Hash::make('password123'),
                        'phone_number' => fake()->phoneNumber(),
                        'date_of_birth' => fake()->date(),
                        'gender' => fake()->randomElement(['male', 'female']),
                        'address' => fake()->address(),
                        'city' => fake()->city(),
                        'state' => fake()->state(),
                        'emergency_contact_name' => fake()->name(),
                        'emergency_contact_phone' => fake()->phoneNumber(),
                        'is_active' => true,
                    ],
                    // Staff details data
                    'staff_details' => [
                        'gym_id' => $gym->id,
                        'staff_type_id' => $headTrainerType->id,
                        'date_of_joining' => fake()->date(),
                        'blood_group' => fake()->randomElement(['A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-']),
                        'specialization' => 'Personal Training & Nutrition',
                        'certifications' => [
                            'NASM Certified Personal Trainer',
                            'Precision Nutrition Level 2 Coach',
                            'CrossFit Level 2 Trainer',
                            'First Aid & CPR Certified'
                        ],
                        'medical_history' => 'No significant medical history',
                    ]
                ],
                [
                    'user' => [
                        'gym_id' => $gym->id,
                        'name' => fake()->name(),
                        'email' => fake()->email(),
                        'password' => Hash::make('password123'),
                        'phone_number' => fake()->phoneNumber(),
                        'date_of_birth' => fake()->date(),
                        'gender' => fake()->randomElement(['male', 'female']),
                        'address' => fake()->address(),
                        'city' => fake()->city(),
                        'state' => fake()->state(),
                        'emergency_contact_name' => fake()->name(),
                        'emergency_contact_phone' => fake()->phoneNumber(),
                        'is_active' => true,
                    ],
                    'staff_details' => [
                        'gym_id' => $gym->id,
                        'staff_type_id' => $personalTrainerType->id,
                        'date_of_joining' => fake()->date(),
                        'blood_group' => fake()->randomElement(['A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-']),
                        'specialization' => 'Strength & Conditioning',
                        'certifications' => [
                            'CSCS (Certified Strength and Conditioning Specialist)',
                            'USA Weightlifting Level 2 Coach',
                            'Sports Performance Coach',
                            'First Aid & CPR Certified'
                        ],
                        'medical_history' => 'No significant medical history',
                    ]
                ],
                [
                    'user' => [
                        'gym_id' => $gym->id,
                        'name' => fake()->name(),
                        'email' => fake()->email(),
                        'password' => Hash::make('password123'),
                        'phone_number' => fake()->phoneNumber(),
                        'date_of_birth' => fake()->date(),
                        'gender' => fake()->randomElement(['male', 'female']),
                        'address' => fake()->address(),
                        'city' => fake()->city(),
                        'state' => fake()->state(),
                        'emergency_contact_name' => fake()->name(),
                        'emergency_contact_phone' => fake()->phoneNumber(),
                        'is_active' => true,
                    ],
                    'staff_details' => [
                        'gym_id' => $gym->id,
                        'staff_type_id' => $groupClassInstructorType->id,
                        'date_of_joining' => fake()->date(),
                        'blood_group' => fake()->randomElement(['A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-']),
                        'specialization' => 'Group Fitness & Yoga',
                        'certifications' => [
                            'ACE Group Fitness Instructor',
                            'RYT-500 Yoga Teacher',
                            'Les Mills Certified Instructor',
                            'First Aid & CPR Certified'
                        ],
                        'medical_history' => 'No significant medical history',
                    ]
                ],
                [
                    'user' => [
                        'gym_id' => $gym->id,
                        'name' => fake()->name(),
                        'email' => fake()->email(),
                        'password' => Hash::make('password123'),
                        'phone_number' => fake()->phoneNumber(),
                        'date_of_birth' => fake()->date(),
                        'gender' => fake()->randomElement(['male', 'female']),
                        'address' => fake()->address(),
                        'city' => fake()->city(),
                        'state' => fake()->state(),
                        'emergency_contact_name' => fake()->name(),
                        'emergency_contact_phone' => fake()->phoneNumber(),
                        'is_active' => true,
                    ],
                    'staff_details' => [
                        'gym_id' => $gym->id,
                        'staff_type_id' => $groupClassInstructorType->id,
                        'date_of_joining' => fake()->date(),
                        'blood_group' => fake()->randomElement(['A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-']),
                        'specialization' => 'General Fitness Training',
                        'certifications' => [
                            'ACE Certified Personal Trainer', 
                            'Group Fitness Instructor',
                            'First Aid & CPR Certified'
                        ],
                        'medical_history' => 'No significant medical history',
                    ]
                ],
                [
                    'user' => [
                        'gym_id' => $gym->id,
                        'name' => fake()->name(),
                        'email' => fake()->email(),
                        'password' => Hash::make('password123'),
                        'phone_number' => fake()->phoneNumber(),
                        'date_of_birth' => fake()->date(),
                        'gender' => fake()->randomElement(['male', 'female']),
                        'address' => fake()->address(),
                        'city' => fake()->city(),
                        'state' => fake()->state(),
                        'emergency_contact_name' => fake()->name(),
                        'emergency_contact_phone' => fake()->phoneNumber(),
                        'is_active' => true,
                    ],
                    'staff_details' => [
                        'gym_id' => $gym->id,
                        'staff_type_id' => $rehabSpecialistType->id,
                        'date_of_joining' => fake()->date(),
                        'blood_group' => fake()->randomElement(['A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-']),
                        'specialization' => 'General Fitness Training',
                        'certifications' => [
                            'ACE Certified Personal Trainer',
                            'Group Fitness Instructor',
                            'First Aid & CPR Certified'
                        ],
                        'medical_history' => 'No significant medical history',
                    ]
                ],
                [
                    'user' => [
                        'gym_id' => $gym->id,
                        'name' => fake()->name(),
                        'email' => fake()->email(),
                        'password' => Hash::make('password123'),
                        'phone_number' => fake()->phoneNumber(),
                        'date_of_birth' => fake()->date(),
                        'gender' => fake()->randomElement(['male', 'female']),
                        'address' => fake()->address(),
                        'city' => fake()->city(),
                        'state' => fake()->state(),
                        'emergency_contact_name' => fake()->name(),
                        'emergency_contact_phone' => fake()->phoneNumber(),
                        'is_active' => true,
                    ],
                    'staff_details' => [
                        'gym_id' => $gym->id,
                        'staff_type_id' => $groupClassInstructorType->id,
                        'date_of_joining' => fake()->date(),
                        'blood_group' => fake()->randomElement(['A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-']),
                        'specialization' => 'General Fitness Training',
                        'certifications' => [
                            'ACE Certified Personal Trainer',
                            'Group Fitness Instructor',
                            'First Aid & CPR Certified'
                        ],
                        'medical_history' => 'No significant medical history',
                    ]
                ] 
            ];

            // Create new staff members
            $qrCodeService = app(QrCodeService::class);
            foreach ($staffMembers as $staffData) {
                // Check if user already exists
                $existingUser = User::where('email', $staffData['user']['email'])->first();
                
                if (!$existingUser) {
                    $profilePhotoPath = $staffData['user']['gender'] == 'male' ? 'profile-photos/staff/male.svg' :
                    'profile-photos/staff/female.svg';
                    // Create user account
                    $user = User::create([
                        'gym_id' => $staffData['user']['gym_id'],
                        'name' => $staffData['user']['name'],
                        'email' => $staffData['user']['email'],
                        'password' => $staffData['user']['password'],
                        'phone_number' => $staffData['user']['phone_number'],
                        'date_of_birth' => $staffData['user']['date_of_birth'],
                        'gender' => $staffData['user']['gender'],
                        'address' => $staffData['user']['address'],
                        'city' => $staffData['user']['city'],
                        'state' => $staffData['user']['state'],
                        'emergency_contact_name' => $staffData['user']['emergency_contact_name'],
                        'emergency_contact_phone' => $staffData['user']['emergency_contact_phone'],
                        'is_active' => $staffData['user']['is_active'],
                        'profile_photo_path' => $profilePhotoPath,
                    ]);

                    // Assign Staff role using Spatie Permission
                    $user->assignRole('staff-' . $staffData['user']['gym_id']);

                    // Generate scan_code using QrCodeService
                    $qrCodeService->getOrCreateScanCode($user);

                    // Create staff details using relationship
                    $user->staffDetail()->create([
                        'user_id' => $user->id,
                        'gym_id' => $staffData['staff_details']['gym_id'],
                        'staff_type_id' => $staffData['staff_details']['staff_type_id'],
                        'date_of_joining' => $staffData['staff_details']['date_of_joining'],
                        'blood_group' => $staffData['staff_details']['blood_group'],
                        'specialization' => $staffData['staff_details']['specialization'],
                        'certifications' => $staffData['staff_details']['certifications'],
                        'medical_history' => $staffData['staff_details']['medical_history'],
                    ]);

                    Log::info("Created new staff member: {$staffData['user']['email']}");
                } else {
                    Log::info("Staff member already exists: {$staffData['user']['email']}");
                }
            }
        }
    }
} 