<?php

namespace App\Livewire\Gyms;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\GymListing;
use App\Models\GymListingImage;
use Illuminate\Support\Facades\Storage;

class ViewGymListing extends Component
{
    use WithFileUploads;
    
    public $gym_listing;
    public $listing;
    public $showImageModal = false;
    public $newImages = [];
    public $mainImageIndex = 0;
    
    // Reviews management
    public $reviewToDelete = null;
    public $showDeleteReviewModal = false;

    public function mount($gym_listing)
    {
        $this->listing = GymListing::with(['gym', 'gymFacilities.facility', 'timings', 'reviews.user', 'images'])->findOrFail($gym_listing);
    }

    public function editBasicInfo()
    {
        return redirect()->route('gym-listings.edit', ['gym_listing' => $this->listing->id]);
    }

    public function editDescription()
    {
        return redirect()->route('gym-listings.edit', ['gym_listing' => $this->listing->id])->with('edit_section', 'description');
    }

    public function editFacilities()
    {
        return redirect()->route('gym-listings.edit', ['gym_listing' => $this->listing->id])->with('edit_section', 'facilities');
    }

    public function editTimings()
    {
        return redirect()->route('gym-listings.edit', ['gym_listing' => $this->listing->id])->with('edit_section', 'timings');
    }

    public function setMainImage($imageId)
    {
        $this->listing->images()->where('is_main', true)->update(['is_main' => false]);
        $this->listing->images()->where('id', $imageId)->update(['is_main' => true]);
    }

    public function deleteImage($imageId)
    {
        $image = $this->listing->images()->find($imageId);
        if ($image) {
            Storage::disk('public')->delete($image->image);
            $image->delete();
        }

        $this->js('window.location.reload()');
    }

    public function addImages()
    {
        $this->showImageModal = true;
        $this->newImages = [];
        $this->mainImageIndex = 0;
    }

    public function closeImageModal()
    {
        $this->showImageModal = false;
        $this->newImages = [];
        $this->mainImageIndex = 0;
    }

    public function uploadImages()
    {
        $this->validate([
            'newImages.*' => 'image|max:2048', // 2MB max per image
        ]);

        try {
            foreach ($this->newImages as $index => $image) {
                $imagePath = $image->store('gym-listings', 'public');
                
                $isMain = ($index == $this->mainImageIndex);
                
                // If this is the main image, unset any existing main image
                if ($isMain) {
                    $this->listing->images()->where('is_main', true)->update(['is_main' => false]);
                }
                
                $this->listing->images()->create([
                    'image' => $imagePath,
                    'is_main' => $isMain,
                ]);
            }

            // Refresh the listing data
            $this->listing = GymListing::with(['gym', 'gymFacilities.facility', 'timings', 'reviews.user', 'images'])->findOrFail($this->listing->id);
            
            $this->closeImageModal();
            session()->flash('message', 'Images uploaded successfully!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error uploading images: ' . $e->getMessage());
        }
    }
    
    public function deleteReview($reviewId)
    {
        $this->reviewToDelete = $reviewId;
        $this->showDeleteReviewModal = true;
    }
    
    public function confirmDeleteReview()
    {
        if ($this->reviewToDelete) {
            $review = $this->listing->reviews()->find($this->reviewToDelete);
            if ($review) {
                $review->delete();
                session()->flash('message', 'Review deleted successfully!');
            }
        }
        
        $this->showDeleteReviewModal = false;
        $this->reviewToDelete = null;
        
        // Refresh the listing data
        $this->listing = GymListing::with(['gym', 'gymFacilities.facility', 'timings', 'reviews.user', 'images'])->findOrFail($this->listing->id);
    }
    
    public function cancelDeleteReview()
    {
        $this->showDeleteReviewModal = false;
        $this->reviewToDelete = null;
    }

    public function render()
    {
        return view('livewire.gyms.view-gym-listing', [
            'listing' => $this->listing
        ]);
    }
} 