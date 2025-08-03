<?php

namespace App\Livewire\Member;

use App\Models\Membership;
use App\Models\MembershipFrequency;
use App\Models\User;
use App\Models\UserMembership;
use App\Models\Frequency;
use App\Models\AvailableTimeSlot;
use App\Models\MemberEnrollment;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use App\Models\MembershipActivityClass;
use App\Traits\HasPackageLimitCheck;

class CreateEditMember extends Component
{
    use WithFileUploads, HasPackageLimitCheck;

    // Personal Details Form
    public $fullName;
    public $email;
    public $phoneNumber;
    public $dateOfBirth;
    public $gender;
    public $address;
    public $city;
    public $state;
    public $emergencyContactName;
    public $emergencyContactPhone;
    public $profilePhoto;

    // Membership Details Form
    public $selectedMembershipId;
    public $selectedFrequencyId;
    public $membershipStartDate;
    public $membershipExpiryDate;
    public $membershipStatus = 'active';
    public $autoRenewalStatus;
    public $lastRenewalDate;
    public $nextRenewalDate;

    // Component State
    public $currentStep = 1;
    public $userId = null;
    public $isEdit = false;
    public $membershipServices = [];
    public $membershipActivityClasses = [];
    public $availableTimeSlots = [];
    public $availableFrequencies = [];
    public $user = null;

    // New properties for activity classes and time slots
    public $selectedActivityClasses = [];
    public $selectedTimeSlots = [];
    public $selectedDays = []; // Array to store selected days per activity class
    public $dayNames=[];

    // Role properties for HasPackageLimitCheck trait
    public $memberRole;
    public $staffRole;

    public $validActivityTimeSlots = []; // New property to store valid activity class and time slot combinations

    protected function getPersonalDetailsRules()
    {
        $emailRule = $this->isEdit
            ? ['required', 'email', Rule::unique('users', 'email')->ignore($this->userId)]
            : 'required|email|unique:users,email';

        return [
            'fullName' => 'required|string|min:3|max:255',
            'email' => $emailRule,
            'phoneNumber' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20',
            'dateOfBirth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'emergencyContactName' => 'nullable|string|max:255',
            'emergencyContactPhone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20',
            'profilePhoto' => 'nullable|image|max:1024', // 1MB Max
        ];
    }

    protected function getMembershipRules()
    {
        return [
            'selectedMembershipId' => 'required|exists:memberships,id',
            'selectedFrequencyId' => 'required|exists:membership_frequencies,frequency_id',
            'membershipStartDate' => 'required|date|after_or_equal:today',
            'membershipExpiryDate' => 'required|date|after_or_equal:membershipStartDate',
            'selectedActivityClasses' => 'array',
            'selectedTimeSlots' => 'array',
            'selectedDays' => 'array',
        ];
    }

    public function mount($userId = null)
    {
        // Initialize role properties from trait
        $this->memberRole = 'member-' . gym()->id;
        $this->staffRole = ['staff-' . gym()->id, 'admin-' . gym()->id];

        $this->isEdit = $userId !== null;
        $this->userId = $userId;
        $this->dayNames = trans('members.weekdays_long');

        if ($this->isEdit) {
            $this->user = User::with('roles')
                ->whereHas('roles', function($query) {
                    $query->where('name', $this->memberRole);
                })
                ->findOrFail($this->userId);
            $this->loadUserData();
        }
    }

    public function loadUserData()
    {
        // Load personal details
        $this->fullName = $this->user->name;
        $this->email = $this->user->email;
        $this->phoneNumber = $this->user->phone_number;
        $this->dateOfBirth = $this->user->date_of_birth;
        $this->gender = $this->user->gender;
        $this->address = $this->user->address;
        $this->city = $this->user->city;
        $this->state = $this->user->state;
        $this->emergencyContactName = $this->user->emergency_contact_name;
        $this->emergencyContactPhone = $this->user->emergency_contact_phone;
    }

    public function validateStep1()
    {
        $this->validate($this->getPersonalDetailsRules());
    }

    public function continue()
    {
        // Check package member limit before proceeding
        if (!$this->canCreateResource('members', $this->userId, $this->isEdit)) {
            return;
        }
        $this->validateStep1();
        $this->currentStep = 2;
    }

    public function previous()
    {
        $this->currentStep = 1;
    }

    public function updatedSelectedMembershipId($value)
    {
        $this->resetDates();
        $this->selectedFrequencyId = null;
        $this->availableFrequencies = [];
        $this->membershipServices = [];

        if ($value) {
            $membership = Membership::with('membershipFrequencies.frequency', 'membershipActivityClasses.activityClass')
                ->where('is_active', true)
                ->find($value);

            if ($membership) {
                $this->availableFrequencies = $membership->membershipFrequencies->map(function ($mf) {
                    return [
                        'id' => $mf->frequency_id,
                        'name' => $mf->frequency->name
                    ];
                })->toArray();
            }
        }

        $this->updateMembershipServices();
        $this->updateSelectedActivityClasses();
    }

    private function calculateExpiryDate($startDate, $frequencySlug)
    {
        $expiryDate = clone $startDate;

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

    public function updatedSelectedFrequencyId($value)
    {
        if (!$value) {
            $this->resetDates();
            $this->updateMembershipServices();
            $this->updateSelectedActivityClasses();
            return;
        }

        $frequency = Frequency::where('id', $value)->first();
        if (!$frequency) {
            return;
        }

        if ($this->membershipStartDate) {
            $startDate = \Carbon\Carbon::parse($this->membershipStartDate);
            $expiryDate = $this->calculateExpiryDate($startDate, $frequency->slug);
            $this->membershipExpiryDate = $expiryDate->format('Y-m-d');
        } else {
            $startDate = \Carbon\Carbon::now();
            $this->membershipStartDate = $startDate->format('Y-m-d');
            $expiryDate = $this->calculateExpiryDate($startDate, $frequency->slug);
            $this->membershipExpiryDate = $expiryDate->format('Y-m-d');
        }

        $this->updateMembershipServices();
        $this->updateSelectedActivityClasses();
    }

    public function updatedMembershipStartDate($value)
    {
        if (!$value || !$this->selectedFrequencyId) {
            return;
        }

        $frequency = Frequency::where('id', $this->selectedFrequencyId)->first();
        if (!$frequency) {
            return;
        }

        $startDate = \Carbon\Carbon::parse($value);
        $expiryDate = $this->calculateExpiryDate($startDate, $frequency->slug);
        $this->membershipExpiryDate = $expiryDate->format('Y-m-d');
    }

    private function resetDates()
    {
        $this->membershipStartDate = null;
        $this->membershipExpiryDate = null;
    }

    public function updateMembershipServices()
    {
        if ($this->selectedMembershipId) {
            $membership = Membership::find($this->selectedMembershipId);
            $this->membershipServices = $membership->services ?? [];
        }
    }

    public function updateSelectedActivityClasses()
    {
        if ($this->selectedMembershipId) {
            $membership = Membership::find($this->selectedMembershipId);
            $this->membershipActivityClasses = $membership->activityClasses ?? [];
            $this->availableTimeSlots = [];
            foreach ($this->membershipActivityClasses as $activityClass) {
                $availableSlots = AvailableTimeSlot::where('activity_class_id', $activityClass->id)
                                ->where('status', 'active')
                                ->where('type', 'weekly')
                                ->get();
                $this->availableTimeSlots[$activityClass->id] = $availableSlots;
            }
        }
    }

    public function cancel()
    {
        return redirect()->route('members.index');
    }

    public function save($returnBoolean = false)
    {
        // Check package member limit before proceeding
        if (!$this->canCreateResource('members', $this->userId, $this->isEdit)) {
            return $returnBoolean ? false : null;
        }

        if ($this->currentStep == 2) {
            $this->validate(array_merge(
                $this->getPersonalDetailsRules(),
                $this->getMembershipRules()
            ));

            $selectedDayNames = collect($this->selectedDays ?? [])
                ->map(fn($dayNumber) => $this->dayNames[$dayNumber])
                ->toArray();

            // Reset valid activity time slots
            $this->validActivityTimeSlots = [];

            // Additional validation to ensure each selected activity class has at least one time slot
            if (!empty($this->selectedActivityClasses)) {
                $timeSlots = AvailableTimeSlot::whereIn('id', $this->selectedTimeSlots)->get();
            
                foreach ($this->selectedActivityClasses as $membershipActivityClassId) {
                    $membershipActivityClass = MembershipActivityClass::find($membershipActivityClassId);
                    if (!$membershipActivityClass) continue;

                    $activityClassId = $membershipActivityClass->activity_class_id;
                    
                    // Check if there are any available time slots for this activity class
                    $availableSlots = AvailableTimeSlot::where('activity_class_id', $activityClassId)
                        ->where('status', 'active')
                        ->where('type', 'weekly')
                        ->get();

                    // If there are available slots but none selected for this class
                    if ($availableSlots->isNotEmpty() && 
                        !$timeSlots->contains(function ($slot) use ($activityClassId) {
                            return $slot->activity_class_id == $activityClassId;
                        })) {
                        $className = $membershipActivityClass->activityClass->name;
                        $this->addError('selectedTimeSlots', __('members.select_time_slot', ['class' => $className]));
                        return;
                    }

                    // Store valid combinations
                    $validSlots = $timeSlots->filter(function ($slot) use ($activityClassId) {
                        return $slot->activity_class_id == $activityClassId;
                    })->pluck('id')->toArray();

                    if (!empty($validSlots)) {
                        $this->validActivityTimeSlots[$membershipActivityClassId] = $validSlots;
                    }
                }
            }
        } else {
            $this->validateStep1();
        }

        try {
            DB::beginTransaction();

            $userData = [
                'name' => $this->fullName,
                'email' => $this->email,
                'phone_number' => $this->phoneNumber,
                'date_of_birth' => $this->dateOfBirth,
                'gender' => $this->gender,
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'emergency_contact_name' => $this->emergencyContactName,
                'emergency_contact_phone' => $this->emergencyContactPhone,
            ];

            if ($this->isEdit) {
                $user = User::findOrFail($this->userId);
                $user->update($userData);
            } else {
                $userData['password'] = bcrypt('password');
                $user = User::create($userData);
                $user->assignRole('member-' . gym()->id);
                $this->userId = $user->id;
            }

            // Handle profile photo upload
            if ($this->profilePhoto) {
                $user->updateProfilePhoto($this->profilePhoto);
            }

            if ($this->currentStep == 2) {
                $frequency = MembershipFrequency::where('frequency_id', $this->selectedFrequencyId)
                    ->where('membership_id', $this->selectedMembershipId)
                    ->firstOrFail();
                
                $membershipData = [
                    'user_id' => $user->id,
                    'membership_id' => $this->selectedMembershipId,
                    'membership_frequency_id' => $frequency->id,
                    'membership_start_date' => $this->membershipStartDate,
                    'membership_expiry_date' => $this->membershipExpiryDate,
                    'membership_status' => $this->membershipStatus,
                ];

                if ($this->isEdit && $user->latestMembership) {
                    $user->latestMembership->update($membershipData);
                } else {
                    $newMembership = UserMembership::create($membershipData);
                    $user->update(['latest_membership_id' => $newMembership->id]);
                }

                // Store selected activity classes and time slots using valid combinations
                foreach ($this->validActivityTimeSlots as $membershipActivityClassId => $timeSlotIds) {
                    foreach ($timeSlotIds as $slotId) {
                        MemberEnrollment::create([
                            'user_id' => $user->id,
                            'available_time_slot_id' => $slotId,
                            'enrolled_at' => now(),
                            'status' => 'enrolled',
                        ]);
                    }
                }
            }

            DB::commit();
            session()->flash('message', $this->isEdit ? __('members.updated') : __('members.created'));
            
            if ($returnBoolean) {
                return true;
            }
            return redirect()->route('members.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', __('members.save_error') . $e->getMessage());
            
            if ($returnBoolean) {
                return false;
            }
            return null;
        }
    }

    public function generateInvoice()
    {
        $result = $this->save(true);
        if ($result) {
            session(['userId' => $this->userId]);
            // $userMembershipId = UserMembership::where('user_id', $this->userId)->latest()->first()->id;
            return redirect()->route('invoices.create', ['user_id' => $this->userId]);
        }
        // If save failed, the error message is already set in the save method
        return null;
    }

    public function savePersonalDetails()
    {
        // Check package member limit before proceeding
        if (!$this->canCreateResource('members', $this->userId, $this->isEdit)) {
            return;
        }
        
        $this->validate($this->getPersonalDetailsRules());

        try {
            DB::beginTransaction();

            $userData = [
                'name' => $this->fullName,
                'email' => $this->email,
                'phone_number' => $this->phoneNumber,
                'date_of_birth' => $this->dateOfBirth,
                'gender' => $this->gender,
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'emergency_contact_name' => $this->emergencyContactName,
                'emergency_contact_phone' => $this->emergencyContactPhone,
            ];

            if ($this->isEdit) {
                $user = User::findOrFail($this->userId);
                $user->update($userData);
            } else {
                $userData['password'] = bcrypt('password');
                $user = User::create($userData);
                $user->assignRole('member-' . gym()->id);
                $this->userId = $user->id;
            }

            // Handle profile photo upload
            if ($this->profilePhoto) {
                $path = $this->profilePhoto->store('profile-photos/member', 'public');
                if ($user->profile_photo_path) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
                $user->profile_photo_path = $path;
                $user->save();
            }

            DB::commit();
            session()->flash('message', $this->isEdit ? __('members.created_details') : __('members.created'));
            return redirect()->route('members.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', ($this->isEdit ? __('common.failed_to_update') : __('common.failed_to_create') ). ' member details: ' . $e->getMessage());
            return null;
        }
    }

    public function deleteProfilePhoto()
    {
        try {
            $user = User::findOrFail($this->userId);
            $user->deleteProfilePhoto();
            session()->flash('message', __('members.delete_profile_photo'));
        } catch (\Exception $e) {
            session()->flash('error', __('members.failed_delete_profile_photo') . $e->getMessage());
        }
    }

    // Method to toggle activity class selection
    public function selectActivityClass($classId)
    {
        if (in_array($classId, $this->selectedActivityClasses)) {
            $this->selectedActivityClasses = array_diff($this->selectedActivityClasses, [$classId]);
        } else {
            $this->selectedActivityClasses[] = $classId;
        }
    }

    // Method to select a time slot ensuring no overlap
    public function selectTimeSlot($slotId)
    {
        // Fetch the time slot details
        $timeSlot = AvailableTimeSlot::find($slotId);
        if (!$timeSlot) return;

        // Check if the slot is already selected (for unselecting)
        if (in_array($slotId, $this->selectedTimeSlots)) {
            $this->selectedTimeSlots = array_diff($this->selectedTimeSlots, [$slotId]);
            return;
        }

        // Check for existing slot on the same day for the same activity class
        foreach ($this->selectedTimeSlots as $selectedSlotId) {
            $selectedSlot = AvailableTimeSlot::find($selectedSlotId);
            if ($selectedSlot && 
                $selectedSlot->activity_class_id == $timeSlot->activity_class_id && 
                $selectedSlot->day_of_week == $timeSlot->day_of_week) {
                // Remove the existing slot for this activity class and day
                $this->selectedTimeSlots = array_diff($this->selectedTimeSlots, [$selectedSlotId]);
                break;
            }
        }

        // Add the new time slot
        $this->selectedTimeSlots[] = $slotId;
    }

    // Method to select a day of the week for a specific activity class
    public function selectDay($day, $activityClassId)
    {
        $this->selectedDays[$activityClassId] = $day;
    }

    public function render()
    {
        return view('livewire.member.create-edit-member', [
            'memberships' => Membership::with('membershipServices.service', 
                                              'membershipFrequencies.frequency', 
                                              'activityClasses.availableTimeSlots')            
                                       ->where('is_active', true)->get(),
        ]);
    }
}
