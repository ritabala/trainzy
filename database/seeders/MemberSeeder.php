<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Membership;
use App\Models\MembershipFrequency;
use App\Models\UserMembership;
use App\Models\MemberEnrollment;
use App\Models\AvailableTimeSlot;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use App\Services\QrCodeService;
use App\Models\Gym;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gyms = Gym::all();

        foreach ($gyms as $gym) {
            // Set a fixed reference date for all calculations
            $referenceDate = Carbon::create(now()); // change this to the current date

            // Get all memberships and frequencies
            $memberships = Membership::with('frequencies')->where('gym_id', $gym->id)->where('is_active', true)->get();

            // Create new members with different scenarios
            $newMembers = [
                [
                    'gym_id' => $gym->id,
                    'name' => fake()->name(),
                    'email' => fake()->email(),
                    'phone_number' => fake()->phoneNumber(),
                    'date_of_birth' => fake()->date(),
                    'gender' => 'male',
                    'address' => fake()->address(),
                    'city' => fake()->city(),
                    'state' => fake()->state(),
                    'emergency_contact_name' => fake()->name(),
                    'emergency_contact_phone' => fake()->phoneNumber(),
                    'created_at' => $referenceDate->copy()->subMonths(6),
                ],
                [
                    'gym_id' => $gym->id,
                    'name' => fake()->name(),
                    'email' => fake()->email(),
                    'phone_number' => fake()->phoneNumber(),
                    'date_of_birth' => fake()->date(),
                    'gender' => 'female',
                    'address' => fake()->address(),
                    'city' => fake()->city(),
                    'state' => fake()->state(),
                    'emergency_contact_name' => fake()->name(),
                    'emergency_contact_phone' => fake()->phoneNumber(),
                    'created_at' => $referenceDate->copy()->subMonths(3),
                ],
                [
                    'gym_id' => $gym->id,
                    'name' => fake()->name(),
                    'email' => fake()->email(),
                    'phone_number' => fake()->phoneNumber(),
                    'date_of_birth' => fake()->date(),
                    'gender' => 'male',
                    'address' => fake()->address(),
                    'city' => fake()->city(),
                    'state' => fake()->state(),
                    'emergency_contact_name' => fake()->name(),
                    'emergency_contact_phone' => fake()->phoneNumber(),
                    'created_at' => $referenceDate->copy()->subMonths(4),
                ],
                [
                    'gym_id' => $gym->id,
                    'name' => fake()->name(),
                    'email' => fake()->email(),
                    'phone_number' => fake()->phoneNumber(),
                    'date_of_birth' => fake()->date(),
                    'gender' => 'female',
                    'address' => fake()->address(),
                    'city' => fake()->city(),
                    'state' => fake()->state(),
                    'emergency_contact_name' => fake()->name(),
                    'emergency_contact_phone' => fake()->phoneNumber(),
                    'created_at' => $referenceDate->copy()->subMonths(5),
                ],
                [
                    'gym_id' => $gym->id,
                    'name' => fake()->name(),
                    'email' => fake()->email(),
                    'phone_number' => fake()->phoneNumber(),
                    'date_of_birth' => fake()->date(),
                    'gender' => 'male',
                    'address' => fake()->address(),
                    'city' => fake()->city(),
                    'state' => fake()->state(),
                    'emergency_contact_name' => fake()->name(),
                    'emergency_contact_phone' => fake()->phoneNumber(),
                    'created_at' => $referenceDate->copy()->subMonths(7),
                ],
                [
                    'gym_id' => $gym->id,
                    'name' => fake()->name(),
                    'email' => fake()->email(),
                    'phone_number' => fake()->phoneNumber(),
                    'date_of_birth' => fake()->date(),
                    'gender' => 'female',
                    'address' => fake()->address(),
                    'city' => fake()->city(),
                    'state' => fake()->state(),
                    'emergency_contact_name' => fake()->name(),
                    'emergency_contact_phone' => fake()->phoneNumber(),
                    'created_at' => $referenceDate->copy()->subMonths(8),
                ],
                [
                    'gym_id' => $gym->id,
                    'name' => fake()->name(),
                    'email' => fake()->email(),
                    'phone_number' => fake()->phoneNumber(),
                    'date_of_birth' => fake()->date(),
                    'gender' => 'female',
                    'address' => fake()->address(),
                    'city' => fake()->city(),
                    'state' => fake()->state(),
                    'emergency_contact_name' => fake()->name(),
                    'emergency_contact_phone' => fake()->phoneNumber(),
                    'created_at' => $referenceDate->copy()->subMonths(6),
                ]
            ];

            $qrCodeService = app(QrCodeService::class);
            foreach ($newMembers as $index => $memberData) {
                $member = User::create([
                    'gym_id' => $memberData['gym_id'],
                    'name' => $memberData['name'],
                    'email' => $memberData['email'],
                    'password' => bcrypt('password'),
                    'phone_number' => $memberData['phone_number'],
                    'date_of_birth' => $memberData['date_of_birth'],
                    'gender' => $memberData['gender'],
                    'address' => $memberData['address'],
                    'city' => $memberData['city'],
                    'state' => $memberData['state'],
                    'emergency_contact_name' => $memberData['emergency_contact_name'],
                    'emergency_contact_phone' => $memberData['emergency_contact_phone'],
                    'is_active' => true,
                    'created_at' => $memberData['created_at'],
                ]);

                // Generate scan_code using QrCodeService
                $qrCodeService->getOrCreateScanCode($member);

                $member->assignRole('member-' . $memberData['gym_id']);

                // Create different membership scenarios based on index
                switch ($index) {
                    case 0: // Member with continuous manual renewals
                        $this->createContinuousManualRenewals($member, $memberships);
                        break;
                    case 1: // Member without any membership
                        // Do nothing - member will have no memberships
                        break;
                    case 2: // Member with expired membership
                        $this->createExpiredMembership($member, $memberships);
                        break;
                    case 3: // Member with active membership and upcoming renewal
                        $this->createActiveWithUpcomingMembership($member, $memberships);
                        break;
                    case 4: // Member with continuous auto-renewals
                        $this->createContinuousAutoRenewals($member, $memberships);
                        break;
                    case 5: // Member with mixed renewal history
                        // $this->createMixedRenewalHistory($member, $memberships);
                        break;
                    case 6: // Member with active membership and upcoming renewal
                        $this->createActiveWithUpcomingMembership($member, $memberships);
                        break;
                }
            }

            // Reset the test now date
            Carbon::setTestNow();
        }
    }


    /**
     * Calculate expiry date based on frequency
     */
    private function calculateExpiryDate(Carbon $startDate, string $frequencySlug): Carbon
    {
        $expiryDate = $startDate->copy();

        switch ($frequencySlug) {
            case 'monthly':
                return $expiryDate->addMonth()->subDay();
            case 'quarterly':
                return $expiryDate->addMonths(3)->subDay();
            case 'half-yearly':
                return $expiryDate->addMonths(6)->subDay();
            case 'yearly':
                return $expiryDate->addYear()->subDay();
            case 'weekly':
                return $expiryDate->addWeek()->subDay();
            case 'daily':
                return $expiryDate->addDay();
            default:
                return $expiryDate->addMonth()->subDay();
        }
    }

    /**
     * Create enrollments for activity classes
     */
    private function createEnrollments(User $member, Membership $membership): void
    {
        // Get available time slots for the membership's activity classes
        $timeSlots = AvailableTimeSlot::whereHas('activityClass', function ($query) use ($membership) {
            $query->whereHas('memberships', function ($q) use ($membership) {
                $q->where('memberships.id', $membership->id);
            });
        })->where('status', 'active')
          ->where('type', 'weekly')
          ->get();

        // Create enrollments for random time slots (1-3 slots per member)
        $numSlots = rand(1, min(3, $timeSlots->count()));
        $selectedSlots = $timeSlots->random($numSlots);

        foreach ($selectedSlots as $slot) {
            MemberEnrollment::create([
                'user_id' => $member->id,
                'available_time_slot_id' => $slot->id,
                'enrolled_at' => now(),
                'status' => 'enrolled',
            ]);
        }
    }

    /**
     * Create a history of continuous manual monthly renewals
     */
    private function createContinuousManualRenewals(User $member, $memberships): void
    {
        $membership = $memberships->random();

        $membershipFrequency = $membership->membershipFrequencies()
            ->whereHas('frequency', function($query) {
                $query->where('slug', 'monthly');
            })
            ->first();

        if (!$membershipFrequency) {
            \Log::error("No monthly frequency found for member {$member->id}");
            return;
        }

        $startDate = Carbon::now()->startOfDay()->subMonths(4);
        $parentMembership = null;

        for ($i = 0; $i < 4; $i++) {
            $isRenewal = $i > 0;
            $isCurrent = $i === 3;

            $expiryDate = $this->calculateExpiryDate($startDate, 'monthly');

            $membershipRecord = UserMembership::create([
                'gym_id' => $member->gym_id,
                'user_id' => $member->id,
                'membership_id' => $membership->id,
                'membership_frequency_id' => $membershipFrequency->id,
                'membership_start_date' => $startDate,
                'membership_expiry_date' => $expiryDate,
                'membership_status' => $isCurrent ? 'active' : 'expired',
                'auto_renewal' => false,
                'last_renewal_date' => null,
                'next_renewal_date' => null,
                'parent_membership_id' => $parentMembership?->id,
                'created_at' => $startDate,
                'updated_at' => $startDate,
            ]);

            $parentMembership = $membershipRecord;
            $startDate = $expiryDate->copy()->addDay();
        }

        $member->update(['latest_membership_id' => $parentMembership->id]);
    }

    /**
     * Create a history of continuous quarterly auto-renewals
     */
    private function createContinuousAutoRenewals(User $member, $memberships): void
    {
        $membership = $memberships->random();

        // Enforce quarterly frequency
        $membershipFrequency = $membership->membershipFrequencies()
            ->whereHas('frequency', function($query) {
                $query->where('slug', 'quarterly');
            })
            ->first();

        if (!$membershipFrequency) {
            // Skip if no quarterly frequency exists for this membership
            return;
        }

        // Start 12 months ago = 4 quarters
        $startDate = Carbon::now()->subMonths(12)->startOfDay();
        $parentMembership = null;

        for ($i = 0; $i < 4; $i++) {
            $expiryDate = $this->calculateExpiryDate($startDate, 'quarterly');

            // Prevent future-dated memberships
            if ($expiryDate->isFuture()) {
                break;
            }

            $membershipRecord = UserMembership::create([
                'gym_id' => $member->gym_id,
                'user_id' => $member->id,
                'membership_id' => $membership->id,
                'membership_frequency_id' => $membershipFrequency->id,
                'membership_start_date' => $startDate,
                'membership_expiry_date' => $expiryDate,
                'membership_status' => $i === 3
                    ? ($expiryDate->lt(Carbon::now()) ? 'expired' : 'active')
                    : 'expired',
                'auto_renewal' => true,
                // 'last_renewal_date' => $i === 0 ? null : $parentMembership->membership_start_date,
                'last_renewal_date' => ($i === 0 || is_null($parentMembership->parent_membership_id)) ? null : $parentMembership->membership_start_date,
                'next_renewal_date' => $expiryDate->copy()->addDay(),
                'parent_membership_id' => $parentMembership?->id,
                'created_at' => $startDate,
                'updated_at' => $startDate,
            ]);

            $parentMembership = $membershipRecord;
            $startDate = $expiryDate->copy()->addDay();
        }

        if ($parentMembership) {
            $member->update(['latest_membership_id' => $parentMembership->id]);
        }
    }

    /**
     * Create a mixed renewal history (manual and auto)
     */
    private function createMixedRenewalHistory(User $member, $memberships): void
    {
        $membership = $memberships->random();
        $membershipFrequency = $membership->membershipFrequencies()
            ->whereHas('frequency', function($query) {
                $query->where('slug', 'half-yearly');
            })
            ->first();

        if (!$membershipFrequency) {
            // fallback or throw error if needed
            return;
        }

        // Start 8 months ago
        $startDate = Carbon::now()->subMonths(8);
        $parentMembership = null;

        for ($i = 0; $i < 4; $i++) {
            $expiryDate = $this->calculateExpiryDate($startDate, 'half-yearly');
            $autoRenewal = $i % 2 === 0; // even index: auto-renewal, odd: manual

            $membershipRecord = UserMembership::create([
                'gym_id' => $member->gym_id,
                'user_id' => $member->id,
                'membership_id' => $membership->id,
                'membership_frequency_id' => $membershipFrequency->id,
                'membership_start_date' => $startDate,
                'membership_expiry_date' => $expiryDate,
                'membership_status' => $i === 3 ? ($expiryDate->lt(Carbon::now()) ? 'expired' : 'active') : 'expired',
                'auto_renewal' => $autoRenewal,
                'last_renewal_date' => $i === 0 ? null : $parentMembership->membership_start_date,
                'next_renewal_date' => $autoRenewal ? $expiryDate->copy()->addDay() : null,
                'parent_membership_id' => $parentMembership?->id,
                'created_at' => $startDate,
                'updated_at' => $startDate,
            ]);

            $parentMembership = $membershipRecord;
            $startDate = $expiryDate->copy()->addDay();
        }

        // Update user's latest membership
        $member->update(['latest_membership_id' => $parentMembership->id]);
    }

    /**
     * Create an expired membership for a member
     */
    private function createExpiredMembership(User $member, $memberships): void
    {
        $membership = $memberships->random();
        $membershipFrequency = $membership->membershipFrequencies()
            ->whereHas('frequency', function($query) {
                $query->where('slug', 'monthly');
            })
            ->first();
    
        if (!$membershipFrequency) {
            // Optional: handle the case if monthly frequency is not found
            return;
        }
    
        // Set start date to 6 months ago
        $startDate = Carbon::now()->subMonths(6);
    
        // Calculate expiry date based on monthly frequency
        $expiryDate = $this->calculateExpiryDate($startDate, 'monthly');
    
        // Ensure expiry date is in the past (expired)
        if ($expiryDate->isFuture()) {
            // Move start date further back so that expiry is definitely in the past
            $startDate = Carbon::now()->subMonths(12);
            $expiryDate = $this->calculateExpiryDate($startDate, 'monthly');
        }
    
        $expiredMembership = UserMembership::create([
            'gym_id' => $member->gym_id,
            'user_id' => $member->id,
            'membership_id' => $membership->id,
            'membership_frequency_id' => $membershipFrequency->id,
            'membership_start_date' => $startDate,
            'membership_expiry_date' => $expiryDate,
            'membership_status' => 'expired',
            'auto_renewal' => false,
            'last_renewal_date' => null,
            'next_renewal_date' => null,
            'created_at' => $startDate,
            'updated_at' => $startDate,
        ]);
    
        // Update user's latest membership
        $member->update(['latest_membership_id' => $expiredMembership->id]);
    }
    

    /**
     * Create an active membership with upcoming renewal for a member
     */
    private function createActiveWithUpcomingMembership(User $member, $memberships): void
    {
        $membership = $memberships->random();
        $membershipFrequency = $membership->membershipFrequencies()
            ->whereHas('frequency', function($query) {
                $query->where('slug', 'monthly');
            })
            ->first();
    
        if (!$membershipFrequency) {
            // Optional: handle no monthly frequency found
            return;
        }
    
        // Active membership starting 1 month ago
        $startDate = Carbon::now()->subMonth();
        $expiryDate = $this->calculateExpiryDate($startDate, 'monthly');
    
        // Ensure expiry date is future and not too far out
        if ($expiryDate->lt(Carbon::now()) || $expiryDate->diffInDays(Carbon::now()) > 365) {
            $startDate = Carbon::now()->subDays(15);
            $expiryDate = $this->calculateExpiryDate($startDate, 'monthly');
        }
    
        $activeMembership = UserMembership::create([
            'gym_id' => $member->gym_id,
            'user_id' => $member->id,
            'membership_id' => $membership->id,
            'membership_frequency_id' => $membershipFrequency->id,
            'membership_start_date' => $startDate,
            'membership_expiry_date' => $expiryDate,
            'membership_status' => $expiryDate->lt(Carbon::now()) ? 'expired' : 'active',
            'auto_renewal' => true,
            'last_renewal_date' => null,
            'next_renewal_date' => $expiryDate->copy()->addDay(),
            'created_at' => $startDate,
            'updated_at' => $startDate,
        ]);
    
        // Create upcoming renewal record
        $upcomingStartDate = $expiryDate->copy()->addDay();
        $upcomingExpiryDate = $this->calculateExpiryDate($upcomingStartDate, 'monthly');
    
        UserMembership::create([
            'gym_id' => $member->gym_id,
            'user_id' => $member->id,
            'membership_id' => $membership->id,
            'membership_frequency_id' => $membershipFrequency->id,
            'membership_start_date' => $upcomingStartDate,
            'membership_expiry_date' => $upcomingExpiryDate,
            'membership_status' => 'upcoming',
            'auto_renewal' => true,
            'last_renewal_date' => null,
            'next_renewal_date' => $upcomingExpiryDate->copy()->addDay(),
            'parent_membership_id' => $activeMembership->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    
        // Update latest membership for user
        $member->update(['latest_membership_id' => $activeMembership->id]);
    }
    
} 