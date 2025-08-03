<?php

namespace App\Livewire\Home;

use App\Models\Gym;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Cache;
use App\Models\Facility;
use App\Models\SidebarPromotion;
use App\Models\LatestOffer;
use App\Models\Frequency;
use Illuminate\Database\Eloquent\Builder;

class GymListing extends Component
{
    use WithPagination;

    // Search and filters
    public $search = '';
    public $location = '';
    public $priceRange = [0, 10000];
    public $selectedFacilities = [];
    public $openingHours = '';
    public $distance = 10;
    public $rating = 0;
    public $genderSpecific = '';
    public $membershipPlan = '';
    public $sortBy = 'nearest';
    public $viewMode = 'list'; // list or map

    // UI state
    public $showFilters = false;
    public $showSearchModal = false;
    public $selectedGyms = [];
    public $showCompareModal = false;

    // Available options
    public $facilities = [];
    
    // Sponsored content
    public $sponsoredGyms = [];
    public $featuredOffers = [];
    public $sidebarPromotions = [];

    public $membershipPlans = [];

    public $sortOptions = [
        'nearest' => 'Nearest',
        'lowest_price' => 'Lowest Price',
        'highest_rated' => 'Highest Rated',
        'most_popular' => 'Most Popular',
        'recently_added' => 'Recently Added'
    ];

    // Pagination settings
    public $perPage = 12;
    public $paginationTheme = 'tailwind';

    protected $queryString = [
        'search' => ['except' => ''],
        'location' => ['except' => ''],
        'priceRange' => ['except' => [0, 500]],
        'selectedFacilities' => ['except' => []],
        'openingHours' => ['except' => ''],
        'distance' => ['except' => 10],
        'rating' => ['except' => 0],
        'genderSpecific' => ['except' => ''],
        'membershipPlan' => ['except' => ''],
        'sortBy' => ['except' => 'nearest'],
        'viewMode' => ['except' => 'list'],
        'perPage' => ['except' => 12]
    ];

    public function mount()
    {
        // Cache facilities for 1 hour with eager loading
        $this->facilities = Cache::remember('facilities', 3600, function () {
            return Facility::select('id', 'name', 'icon')->orderBy('name')->get();
        });
        
        // Auto-detect location if not set
        if (empty($this->location)) {
            $this->detectLocation();
        }
        
        // Load sponsored content with caching
        $this->loadSponsoredContent();
        
        // Pre-warm cache for common queries
        $this->preWarmCache();
    }

    private function preWarmCache()
    {
        // Pre-warm cache for common filter combinations
        $commonFilters = [
            ['rating' => 4, 'sortBy' => 'highest_rated'],
            ['rating' => 0, 'sortBy' => 'lowest_price'],
            ['selectedFacilities' => [], 'sortBy' => 'nearest']
        ];
        
        foreach ($commonFilters as $filters) {
            $cacheKey = 'gym_listing_' . md5(serialize($filters));
            if (!Cache::has($cacheKey)) {
                // Pre-warm in background
                dispatch(function () use ($filters) {
                    $this->getFilteredGymsWithFilters($filters);
                })->afterResponse();
            }
        }
    }

    private function getFilteredGymsWithFilters($filters)
    {
        // Temporary set filters for cache warming
        $originalFilters = [
            'search' => $this->search,
            'location' => $this->location,
            'priceRange' => $this->priceRange,
            'selectedFacilities' => $this->selectedFacilities,
            'rating' => $this->rating,
            'sortBy' => $this->sortBy
        ];
        
        // Apply temporary filters
        foreach ($filters as $key => $value) {
            $this->$key = $value;
        }
        
        $result = $this->getFilteredGyms();
        
        // Restore original filters
        foreach ($originalFilters as $key => $value) {
            $this->$key = $value;
        }
        
        return $result;
    }

    public function detectLocation()
    {
        // In a real app, you'd use IP geolocation or browser geolocation
        // For now, we'll use a default location
        $this->location = '';
    }

    public function loadSponsoredContent()
    {
        // Cache sponsored content for 30 minutes
        $this->featuredOffers = Cache::remember('featured_offers', 1800, function () {
            return LatestOffer::select('id', 'title', 'description', 'savings', 'expires_at')->get();
        });

        $this->sidebarPromotions = Cache::remember('sidebar_promotions', 1800, function () {
            return SidebarPromotion::select('id', 'title', 'description', 'type', 'trainer_count', 'savings', 'expires_in')->get();
        });
    }

    // Debounced search methods for better performance
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedLocation()
    {
        $this->resetPage();
    }

    public function updatedPriceRange()
    {
        $this->resetPage();
    }

    public function updatedSelectedFacilities()
    {
        $this->resetPage();
    }

    public function updatedOpeningHours()
    {
        $this->resetPage();
    }

    public function updatedDistance()
    {
        $this->resetPage();
    }

    public function updatedRating()
    {
        $this->resetPage();
    }

    public function updatedGenderSpecific()
    {
        $this->resetPage();
    }

    public function updatedMembershipPlan()
    {
        $this->resetPage();
    }

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
        // Scroll to top when page changes
        $this->dispatch('scroll-to-top');
    }

    public function changePerPage($perPage)
    {
        $this->perPage = $perPage;
        $this->resetPage();
        // Scroll to top when per page changes
        $this->dispatch('scroll-to-top');
    }


    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function resetFilters()
    {
        $this->reset([
            'search', 'location', 'priceRange', 'selectedFacilities',
            'openingHours', 'distance', 'rating', 'genderSpecific',
            'membershipPlan'
        ]);
        $this->resetPage();
    }

    public function toggleGymSelection($gymId)
    {
        if (in_array($gymId, $this->selectedGyms)) {
            $this->selectedGyms = array_diff($this->selectedGyms, [$gymId]);
        } else {
            $this->selectedGyms[] = $gymId;
        }
    }

    public function compareGyms()
    {
        if (count($this->selectedGyms) >= 2) {
            $this->showCompareModal = true;
        }
    }

    public function viewGymDetails($gymId)
    {
        // In a real app, redirect to gym details page
        // For now, we'll just show an alert
        $this->dispatch('show-alert', [
            'type' => 'info',
            'message' => 'Gym details page would open here'
        ]);
    }

    public function bookFreeTrial($gymId)
    {
        // In a real app, redirect to booking page
        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'Free trial booking would open here'
        ]);
    }

    public function render()
    {
        // Get fresh results without caching
        $filteredData = $this->getFilteredGyms();

        // Add debugging information in development
        if (config('app.debug')) {
            \Log::info('GymListing pagination debug', [
                'total_gyms' => $filteredData['total'],
                'current_page' => $filteredData['gyms']->currentPage(),
                'per_page' => $filteredData['gyms']->perPage(),
                'has_pages' => $filteredData['gyms']->hasPages(),
                'last_page' => $filteredData['gyms']->lastPage(),
            ]);
        }

        return view('livewire.home.gym-listing', [
            'gyms' => $filteredData['gyms'],
            'totalGyms' => $filteredData['total'],
            'selectedGymsData' => $filteredData['selectedGyms'],
            'sponsoredGyms' => $this->sponsoredGyms,
            'featuredOffers' => $this->featuredOffers,
            'sidebarPromotions' => $this->sidebarPromotions
        ]);
    }

    private function getFilteredGyms()
    {
        // Build efficient query with eager loading and optimized selects
        $query = Gym::with([
            'currency:id,name,symbol',
            'gymListings:id,gym_id,is_sponsored,badges',
            'gymListings.facilities:id,name',
            'gymListings.reviews:id,gym_listing_id,rating',
            'packageSubscriptions:id,gym_id,amount'
        ])
        ->whereHas('gymListings')
        ->select('id', 'name', 'address', 'phone', 'email', 'website', 'slug', 'created_at');

        // Apply filters efficiently
        $this->applyFilters($query);

        // Apply sorting
        $this->applySorting($query);

        // Get paginated results with optimized count
        $gyms = $query->paginate($this->perPage);

        // Transform data efficiently using collection methods
        $transformedGyms = $gyms->getCollection()->map(function ($gym) {
            $gymListing = $gym->gymListings->first();
            $minPrice = $gym->packageSubscriptions->min('amount') ?? 0;
            
            return [
                'id' => $gym->id,
                'name' => $gym->name,
                'address' => $gym->address,
                'phone' => $gym->phone_number,
                'email' => $gym->email,
                'website' => $gym->website_url,
                'logo' => $gymListing->main_image_url,
                'price' => floatval($minPrice),
                'facilities' => $gymListing?->facilities->pluck('name')->toArray() ?? [],
                'facility_ids' => $gymListing?->facilities->pluck('id')->toArray() ?? [],
                'badges' => json_decode($gymListing?->badges, true) ?? [],
                'sponsored' => $gymListing?->is_sponsored ?? false,
                'slug' => $gym->slug,
                'reviews' => $gymListing?->reviews->count() ?? 0,
                'rating' => $gymListing?->reviews->avg('rating') ?? 0,
            ];
        });

        // Get selected gyms data efficiently
        $selectedGyms = collect();
        if (!empty($this->selectedGyms)) {
            $selectedGyms = $transformedGyms->whereIn('id', $this->selectedGyms)->values();
        }

        // Create a new paginator with the transformed data while preserving pagination metadata
        $transformedPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $transformedGyms,
            $gyms->total(),
            $gyms->perPage(),
            $gyms->currentPage(),
            [
                'path' => $gyms->path(),
                'pageName' => $gyms->getPageName(),
            ]
        );

        return [
            'gyms' => $transformedPaginator,
            'total' => $gyms->total(),
            'selectedGyms' => $selectedGyms
        ];
    }

    private function applyFilters(Builder $query)
    {
        // Search filter with full-text search optimization
        if (!empty($this->search)) {
            $searchTerm = trim($this->search);
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('address', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }

        // Location filter
        if (!empty($this->location)) {
            $locationTerm = trim($this->location);
            $query->where('address', 'like', '%' . $locationTerm . '%');
        }

        // Rating filter with optimized subquery
        if ($this->rating > 0) {
            $query->whereHas('gymListings.reviews', function ($q) {
                $q->selectRaw('gym_listing_id, AVG(rating) as avg_rating')
                  ->groupBy('gym_listing_id')
                  ->having('avg_rating', '>=', $this->rating);
            });
        }

        // Facilities filter with optimized join
        if (!empty($this->selectedFacilities)) {
            $query->whereHas('gymListings.facilities', function ($q) {
                $q->whereIn('facilities.id', $this->selectedFacilities);
            });
        }

        // Price range filter (apply after getting min price)
        // This will be handled in the transformation step for efficiency
    }

    private function applySorting(Builder $query)
    {
        switch ($this->sortBy) {
            case 'lowest_price':
                $query->orderByRaw('(SELECT MIN(amount) FROM gym_package_subscriptions WHERE gym_id = gyms.id) ASC NULLS LAST');
                break;
            case 'highest_rated':
                $query->orderByRaw('(SELECT AVG(rating) FROM gym_reviews r JOIN gym_listings gl ON r.gym_listing_id = gl.id WHERE gl.gym_id = gyms.id) DESC NULLS LAST');
                break;
            case 'most_popular':
                $query->orderByRaw('(SELECT COUNT(*) FROM gym_reviews r JOIN gym_listings gl ON r.gym_listing_id = gl.id WHERE gl.gym_id = gyms.id) DESC NULLS LAST');
                break;
            case 'recently_added':
                $query->orderBy('created_at', 'desc');
                break;
            default: // nearest
                $query->orderBy('name', 'asc');
                break;
        }
    }
} 