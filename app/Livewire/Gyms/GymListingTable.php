<?php

namespace App\Livewire\Gyms;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\GymListing;
use App\Models\Gym;
use Illuminate\Support\Facades\Auth;

class GymListingTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $query = GymListing::with(['gym'])
            ->when($this->search, function ($q) {
                $q->whereHas('gym', function ($g) {
                    $g->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $listings = $query->paginate($this->perPage);

        return view('livewire.gyms.gym-listing-table', [
            'listings' => $listings
        ]);
    }
} 