<?php

namespace App\Livewire\Gyms;

use Livewire\Component;
use App\Models\Gym;
use App\Models\GymListing;
use App\Models\Facility;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class CreateEditGymListing extends Component
{
    use WithFileUploads, LivewireAlert;

    public $gym;
    public $listingId = null;
    public $isEditing = false;

    // Available options
    public $badges = [];
    public $facilities = [];
    public $gyms = [];
    public $selectedGym = null;
    public $description = '';
    public $is_sponsored = false;
    public $selectedBadges = [];
    public $selectedFacilities = [];
    public $days = [];
    
    // Timing properties
    public $timings = [];

    protected $rules = [
        'gym' => 'required|exists:gyms,id',
        'description' => 'required',
        'is_sponsored' => 'boolean',
        'selectedBadges' => 'array',
        'selectedFacilities' => 'array',
        'timings.*.open_time' => 'nullable',
        'timings.*.close_time' => 'nullable',
    ];


    public function mount($gym = null, $listing = null)
    {
        $this->badges = GymListing::BADGES;
        $this->facilities = Facility::all();
        $this->gyms = Gym::all();
        $this->days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        if ($listing) {
            $this->isEditing = true;
            $this->listingId = $listing;
            $listingModel = GymListing::with('timings')->findOrFail($listing);
            $this->gym = $listingModel->gym_id;
            $this->description = $listingModel->about ?? '';
            $this->is_sponsored = $listingModel->is_sponsored ?? false;
            $this->selectedBadges = json_decode($listingModel->badges, true) ?? [];
            $this->selectedFacilities = $listingModel->gymFacilities->pluck('facility_id')->toArray();
            
            // Load existing timings
            $this->loadTimings($listingModel);
        } elseif ($gym) {
            $this->gym = $gym;
        }

        $this->selectedGym = $this->gym ? Gym::find($this->gym) : null;
        
        // Initialize timings for all days
        $this->initializeTimings();
    }

    private function initializeTimings()
    {
        foreach ($this->days as $day) {
            if (!isset($this->timings[$day])) {
                $this->timings[$day] = [
                    'open_time' => '',
                    'close_time' => ''
                ];
            }
        }
    }

    private function loadTimings($listingModel)
    {
        $existingTimings = $listingModel->timings;
        
        foreach ($this->days as $day) {
            $timing = $existingTimings->where('day', $day)->first();
            $this->timings[$day] = [
                'open_time' => $timing ? $timing->open_time : '',
                'close_time' => $timing ? $timing->close_time : ''
            ];
        }
    }

    public function updatedGymId()
    {
        $this->selectedGym = $this->gym ? Gym::find($this->gym) : null;
    }

    public function saveListing()
    {
        $this->validate();

        $data = [
            'gym_id' => $this->gym,
            'about' => $this->description,
            'is_sponsored' => $this->is_sponsored,
            'badges' => json_encode($this->selectedBadges),
        ];

        if ($this->isEditing) {
            $listing = GymListing::findOrFail($this->listingId);
            $listing->update($data);
            $listing->gymFacilities()->delete();
            $listing->timings()->delete();

            $listing->gymFacilities()->createMany(array_map(function ($facility) {
                return [
                    'facility_id' => $facility,
                ];
            }, $this->selectedFacilities));
            
            // Save timings
            $this->saveTimings($listing);
            
            $this->alert('success', 'Gym listing updated successfully!');
        } else {
            $listing = GymListing::create($data);
            $listing->gymFacilities()->createMany(array_map(function ($facility) {
                return [
                    'facility_id' => $facility,
                ];
            }, $this->selectedFacilities));
            
            // Save timings
            $this->saveTimings($listing);
            
            $this->alert('success', 'Gym listing created successfully!');
        }

        return redirect()->route('gym-listings.index');
    }

    private function saveTimings($listing)
    {
        $timingData = [];
        
        foreach ($this->timings as $day => $timing) {
            if (!empty($timing['open_time']) && !empty($timing['close_time'])) {
                $timingData[] = [
                    'day' => $day,
                    'open_time' => $timing['open_time'],
                    'close_time' => $timing['close_time']
                ];
            }
        }
        
        if (!empty($timingData)) {
            $listing->timings()->createMany($timingData);
        }
    }

    public function render()
    {
        return view('livewire.gyms.create-edit-gym-listing');
    }
} 