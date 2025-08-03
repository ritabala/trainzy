<?php

namespace App\Livewire\Gyms;

use Livewire\Component;
use App\Models\GymPackageSubscription;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class PackageSubscriptionHistory extends Component
{
    use WithPagination, LivewireAlert;

    public $gymId;
    public $search = '';
    public $isActive = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $nonSortableFields = ['actions'];
    public $perPage = 10;
    public $showSingleGym = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
    ];

    public function mount($gymId = null)
    {
        $this->gymId = $gymId;
        $this->showSingleGym = $gymId !== null;
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search', 'isActive', 'dateRange'])) {
            $this->resetPage();
        }
    }

    public function sortBy($field)
    {
        if (in_array($field, $this->nonSortableFields)) {
            return;
        }

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $query = GymPackageSubscription::query()
            ->with(['gym', 'package'])
            ->when($this->gymId, function ($query) {
                return $query->where('gym_id', $this->gymId);
            })
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->whereHas('gym', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('package', function ($q) {
                        $q->where('package_name', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->where('status', 'completed')
            ->when($this->isActive !== '', function ($query) {
                return $query->where('is_active', $this->isActive);
            });

        $subscriptions = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.gyms.package-subscription-history', [
            'subscriptions' => $subscriptions,
            'gymId' => $this->gymId
        ]);
    }
}
