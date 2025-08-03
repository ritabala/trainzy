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

class GymListingManagement extends Component
{
    use WithFileUploads, LivewireAlert;

    // Gym Selection
    public $selectedGymId = null;
    public $gymSearch = '';
    public $showGymSelector = true;
    
    // Listing Form
    public $showListingForm = false;
    public $isEditing = false;
    public $listingId = null;
    
    // Form Fields
    #[Rule('required|string|max:255')]
    public $description = '';
    
    #[Rule('boolean')]
    public $is_sponsored = false;
    
    #[Rule('array')]
    public $selectedBadges = [];
    
    #[Rule('array')]
    public $selectedFacilities = [];
    
    #[Rule('nullable|image|max:2048')]
    public $mainImage;
    
    // Available options
    public $badges = [];
    public $facilities = [];
    public $gyms = [];
    
    protected $listeners = [
        'gymSelected' => 'selectGym',
        'listingCreated' => 'refreshListings',
        'listingUpdated' => 'refreshListings',
        'listingDeleted' => 'refreshListings',
        'editListing' => 'handleEditListing',
        'deleteListing' => 'handleDeleteListing',
        'viewListing' => 'handleViewListing'
    ];

    public function mount()
    {
        $this->badges = GymListing::BADGES;
        $this->facilities = Facility::all();
        $this->loadGyms();
    }

    public function loadGyms()
    {
        $this->gyms = Gym::when($this->gymSearch, function ($query) {
            $query->where('name', 'like', '%' . $this->gymSearch . '%')
                  ->orWhere('email', 'like', '%' . $this->gymSearch . '%');
        })->get();
    }

    public function updatedGymSearch()
    {
        $this->loadGyms();
    }

    public function selectGym($gymId)
    {
        $this->selectedGymId = $gymId;
        $this->showGymSelector = false;
        
        // Check if gym already has a listing
        $existingListing = GymListing::where('gym_id', $gymId)->first();
        
        if ($existingListing) {
            $this->alert('info', 'This gym already has a listing. You can edit it or create a new one.');
        }
    }

    public function backToGymSelection()
    {
        $this->selectedGymId = null;
        $this->showGymSelector = true;
        $this->showListingForm = false;
        $this->resetForm();
    }

    public function createListing()
    {
        $this->isEditing = false;
        $this->listingId = null;
        $this->showListingForm = true;
        $this->resetForm();
    }

    public function editListing($listingId)
    {
        $listing = GymListing::findOrFail($listingId);
        
        $this->isEditing = true;
        $this->listingId = $listingId;
        $this->selectedGymId = $listing->gym_id;
        $this->showListingForm = true;
        
        // Populate form
        $this->description = $listing->description ?? '';
        $this->is_sponsored = $listing->is_sponsored ?? false;
        $this->selectedBadges = json_decode($listing->badges, true) ?? [];
        $this->selectedFacilities = $listing->facilities->pluck('id')->toArray();
    }

    public function handleEditListing($data)
    {
        $this->editListing($data['listingId']);
    }

    public function handleDeleteListing($data)
    {
        $this->deleteListing($data['listingId']);
    }

    public function handleViewListing($data)
    {
        // For now, just show an alert. You can implement a detailed view later
        $listing = GymListing::with('gym')->find($data['listingId']);
        $this->alert('info', "Viewing listing for: " . $listing->gym->name);
    }

    public function saveListing()
    {
        $this->validate();

        try {
            $data = [
                'gym_id' => $this->selectedGymId,
                'description' => $this->description,
                'is_sponsored' => $this->is_sponsored,
                'badges' => json_encode($this->selectedBadges),
            ];

            // Handle main image upload
            if ($this->mainImage) {
                $imagePath = $this->mainImage->store('gym-listings', 'public');
                $data['main_image_url'] = $imagePath;
            }

            if ($this->isEditing) {
                $listing = GymListing::findOrFail($this->listingId);
                $listing->update($data);
                
                // Sync facilities
                $listing->facilities()->sync($this->selectedFacilities);
                
                $this->alert('success', 'Gym listing updated successfully!');
            } else {
                $listing = GymListing::create($data);
                
                // Attach facilities
                $listing->facilities()->attach($this->selectedFacilities);
                
                $this->alert('success', 'Gym listing created successfully!');
            }

            $this->showListingForm = false;
            $this->resetForm();
            $this->dispatch('listingCreated');

        } catch (\Exception $e) {
            $this->alert('error', 'Error saving gym listing: ' . $e->getMessage());
        }
    }

    public function deleteListing($listingId)
    {
        $this->alert('warning', 'Are you sure you want to delete this listing?', [
            'position' => 'center',
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'confirmDeleteListing',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'confirmButtonText' => 'Yes, Delete',
            'data' => [
                'listing_id' => $listingId,
            ]
        ]);
    }

    public function confirmDeleteListing($data)
    {
        try {
            $listing = GymListing::findOrFail($data['listing_id']);
            $listing->delete();
            
            $this->alert('success', 'Gym listing deleted successfully!');
            $this->dispatch('listingDeleted');
            
        } catch (\Exception $e) {
            $this->alert('error', 'Error deleting gym listing: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->description = '';
        $this->is_sponsored = false;
        $this->selectedBadges = [];
        $this->selectedFacilities = [];
        $this->mainImage = null;
        $this->resetValidation();
    }

    public function toggleBadge($badge)
    {
        if (in_array($badge, $this->selectedBadges)) {
            $this->selectedBadges = array_diff($this->selectedBadges, [$badge]);
        } else {
            $this->selectedBadges[] = $badge;
        }
    }

    public function render()
    {
        $selectedGym = $this->selectedGymId ? Gym::find($this->selectedGymId) : null;
        $existingListing = $this->selectedGymId ? GymListing::where('gym_id', $this->selectedGymId)->first() : null;

        return view('livewire.gyms.gym-listing-management', [
            'selectedGym' => $selectedGym,
            'existingListing' => $existingListing,
            'gyms' => $this->gyms
        ]);
    }
} 