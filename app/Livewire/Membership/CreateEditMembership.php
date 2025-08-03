<?php

namespace App\Livewire\Membership;

use App\Models\Membership;
use App\Models\Frequency;
use App\Models\Service;
use App\Models\ActivityClass;
use Livewire\Component;
use App\Models\MembershipFrequency;
use App\Models\MembershipService;
use App\Models\MembershipActivityClass;
use App\Models\UserMembership;
use Jantinnerezo\LivewireAlert\LivewireAlert;


class CreateEditMembership extends Component
{
    use LivewireAlert;

    public $membershipId;
    public $name;
    public $tagline;
    public $description;
    public $frequencies = [];
    public $services = [];
    public $activityClasses = [];
    public $availableFrequencies;
    public $availableServices;
    public $availableActivityClasses;
    public $is_active = true;
    public $selectedActivityClass = null;
    public $activityClassSearch = '';
    public $confirmationText = '';
    public $canConfirm = false;

    protected $listeners = ['save'];

    protected $rules = [
        'name' => 'required|min:3|max:255',
        'tagline' => 'required|max:255',
        'description' => 'required',
        'frequencies' => 'required|array|min:1',
        'frequencies.*.id' => 'required|exists:frequencies,id',
        'frequencies.*.price' => 'required|numeric|min:0',
        'services' => 'required|array|min:1',
        'services.*' => 'exists:services,id',
        'activityClasses' => 'required|array|min:1',
        'activityClasses.*' => 'exists:activity_classes,id',
        'is_active' => 'boolean'
    ];

    public function mount($membershipId = null)
    {
        $this->availableFrequencies = Frequency::orderBy('name')->get();
        $this->availableServices = Service::where('is_active', true)->orderBy('name')->get();
        $this->availableActivityClasses = ActivityClass::where('is_active', true)->orderBy('name')->get();
        
        if ($membershipId) {
            // Editing an existing membership
            $membership = Membership::with(['frequencies', 'services', 'activityClasses'])->findOrFail($membershipId);
            $this->membershipId = $membership->id;
            $this->name = $membership->name;
            $this->tagline = $membership->tagline;
            $this->description = $membership->description;
            $this->is_active = $membership->is_active;

            // Load frequencies and prices properly
            foreach ($membership->membershipFrequencies as $freq) {
                $this->frequencies[] = [
                    'id' => $freq->frequency_id,
                    'price' => $freq->price,
                ];
            }
            // Load selected services
            $this->services = $membership->membershipServices->pluck('service_id')->toArray();
            // Load selected activity classes
            $this->activityClasses = $membership->membershipActivityClasses->pluck('activity_class_id')->toArray();
        } else {
            // Creating a new membership
            $this->frequencies = [['id' => '', 'price' => '']];
        }
    }

    public function addFrequency()
    {
        $selectedFrequencies = array_column($this->frequencies, 'id');

        // Ensure the button gets disabled once all frequencies are selected
        if (count($selectedFrequencies) < $this->availableFrequencies->count()) {
            $this->frequencies[] = ['id' => '', 'price' => ''];
        }
    }

    public function removeFrequency($index)
    {
        if (count($this->frequencies) > 1) {
            unset($this->frequencies[$index]);
            $this->frequencies = array_values($this->frequencies);
        }
    }

    public function addActivityClass($activityClassId)
    {
        if (!in_array($activityClassId, $this->activityClasses)) {
            $this->activityClasses[] = $activityClassId;
        }
    }

    public function removeActivityClass($activityClassId)
    {
        $this->activityClasses = array_diff($this->activityClasses, [$activityClassId]);
    }

    public function toggleActivityClass($activityClassId)
    {
        if (in_array($activityClassId, $this->activityClasses)) {
            $this->removeActivityClass($activityClassId);
        } else {
            $this->addActivityClass($activityClassId);
        }
    }

    public function getFilteredActivityClassesProperty()
    {
        if (empty($this->activityClassSearch)) {
            return $this->availableActivityClasses;
        }

        $searchTerm = strtolower($this->activityClassSearch);
        return $this->availableActivityClasses->filter(function ($activityClass) use ($searchTerm) {
            return str_contains(strtolower($activityClass->name), $searchTerm) ||
                   str_contains(strtolower($activityClass->description ?? ''), $searchTerm);
        });
    }

    public function confirmMembershipUpdate($id)
    {
        $this->alert('warning', __('membership.confirm_update'), [
            'position' => 'center',
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'save',
            'showCancelButton' => true,
            'cancelButtonText' => __('common.cancel'),
            'confirmButtonText' => __('common.confirm'),
            'confirmButtonColor' => '#3085d6',
            'input' => 'text',
            'inputPlaceholder' => __('membership.sec_text'),
            'inputValidator' => '(value) => {
                if (value !== "confirm") {
                    return "Please type confirm to proceed";
                }
            }',
            'inputAttributes' => [
                'required' => 'required'
            ],
            'showClass' => [
                'popup' => 'animate__animated animate__fadeInDown'
            ],
            'hideClass' => [
                'popup' => 'animate__animated animate__fadeOutUp'
            ],
            'customClass' => [
                'popup' => 'text-sm leading-relaxed lg:w-1/2 lg:max-w-full'
            ]
        ]);
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->membershipId) {
                // Update Membership
                $membership = Membership::findOrFail($this->membershipId);
                $membership->update([
                    'name' => $this->name,
                    'tagline' => $this->tagline,
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                ]);

                // Handle Frequencies
                $existingFrequencies = MembershipFrequency::where('membership_id', $membership->id)
                    ->get()
                    ->keyBy('frequency_id');
                
                $newFrequencyIds = array_column($this->frequencies, 'id');
                
                // Remove frequencies that are no longer selected
                MembershipFrequency::where('membership_id', $membership->id)
                    ->whereNotIn('frequency_id', $newFrequencyIds)
                    ->delete();
                
                // Update or create frequencies
                foreach ($this->frequencies as $frequency) {
                    if (isset($existingFrequencies[$frequency['id']])) {
                        // Update existing frequency price
                        $existingFrequencies[$frequency['id']]->update([
                            'price' => $frequency['price']
                        ]);
                    } else {
                        // Create new frequency
                        MembershipFrequency::create([
                            'membership_id' => $membership->id,
                            'frequency_id' => $frequency['id'],
                            'price' => $frequency['price'],
                        ]);
                    }
                }

                // Handle Services
                $existingServices = MembershipService::where('membership_id', $membership->id)
                    ->pluck('service_id')
                    ->toArray();
                
                // Remove services that are no longer selected
                MembershipService::where('membership_id', $membership->id)
                    ->whereNotIn('service_id', $this->services)
                    ->delete();
                
                // Add new services
                $newServices = array_diff($this->services, $existingServices);
                foreach ($newServices as $serviceId) {
                    MembershipService::create([
                        'membership_id' => $membership->id,
                        'service_id' => $serviceId,
                    ]);
                }

                // Handle Activity Classes
                $existingActivityClasses = MembershipActivityClass::where('membership_id', $membership->id)
                    ->pluck('activity_class_id')
                    ->toArray();
                
                // Remove activity classes that are no longer selected
                MembershipActivityClass::where('membership_id', $membership->id)
                    ->whereNotIn('activity_class_id', $this->activityClasses)
                    ->delete();
                
                // Add new activity classes
                $newActivityClasses = array_diff($this->activityClasses, $existingActivityClasses);
                foreach ($newActivityClasses as $activityClassId) {
                    MembershipActivityClass::create([
                        'membership_id' => $membership->id,
                        'activity_class_id' => $activityClassId,
                    ]);
                }

                // Update all user memberships with the new membership details
                $userMemberships = UserMembership::where('membership_id', $membership->id)->get();
                foreach ($userMemberships as $userMembership) {
                    $userMembership->update([
                        'membership_name' => $this->name,
                        'membership_tagline' => $this->tagline,
                        'membership_description' => $this->description,
                    ]);
                }
            } else {
                // Create Membership
                $membership = Membership::create([
                    'name' => $this->name,
                    'tagline' => $this->tagline,
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                ]);

                // Create MembershipFrequency
                foreach ($this->frequencies as $frequency) {
                    MembershipFrequency::create([
                        'membership_id' => $membership->id,
                        'frequency_id' => $frequency['id'],
                        'price' => $frequency['price'],
                    ]);
                }

                // Create MembershipService
                foreach ($this->services as $serviceId) {
                    MembershipService::create([
                        'membership_id' => $membership->id,
                        'service_id' => $serviceId,
                    ]);
                }

                // Create MembershipActivityClass
                foreach ($this->activityClasses as $activityClassId) {
                    MembershipActivityClass::create([
                        'membership_id' => $membership->id,
                        'activity_class_id' => $activityClassId,
                    ]);
                }
            }

            session()->flash('message', $this->membershipId ? __('membership.updated') : __('membership.created'));
            return $this->redirect(route('memberships.index'));
        } catch (\Exception $e) {
            session()->flash('error', ($this->membershipId ? __('membership.failed_to_update') : __('membership.failed_to_create')) . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.membership.create-edit-membership');
    }
}
