<?php

namespace App\Livewire\Membership;

use App\Models\User;
use App\Models\UserMembership;
use App\Models\Frequency;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class UserMemberships extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $membershipId = '';
    public $frequencyId = '';
    public $startDate = '';
    public $expiryDate = '';
    public $dateFilterType = ''; // 'start' or 'expiry'
    public $showMoreFilters = false;
    public $membershipStatus = '';
    public $autoRenewal = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'membershipId' => ['except' => ''],
        'frequencyId' => ['except' => ''],
        'startDate' => ['except' => ''],
        'expiryDate' => ['except' => ''],
        'dateFilterType' => ['except' => ''],
        'membershipStatus' => ['except' => ''],
        'autoRenewal' => ['except' => ''],
    ];

    public function mount()
    {
        // Initialize any default values if needed
        $this->showMoreFilters = false;
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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingMembershipId()
    {
        $this->resetPage();
    }

    public function updatingFrequencyId()
    {
        $this->resetPage();
    }

    public function updatingStartDate()
    {
        $this->resetPage();
    }

    public function updatingExpiryDate()
    {
        $this->resetPage();
    }

    public function updatingMembershipStatus()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset([
            'search',
            'membershipId', 
            'frequencyId', 
            'startDate', 
            'expiryDate', 
            'dateFilterType',
            'membershipStatus',
            'autoRenewal'
        ]);
        $this->resetPage();
    }

    public function getMembershipStatuses()
    {
        return [
            'active' => __('membership.user_membership_status.active'),
            'expired' => __('membership.user_membership_status.expired'),
            'cancelled' => __('membership.user_membership_status.cancelled'),
            'pending' => __('membership.user_membership_status.pending'),
        ];
    }

    public function render()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_user_memberships'), 403);
        $query = UserMembership::query()
            ->select([
                'user_memberships.*',
                'users.name as user_name',
                'users.email as user_email',
                'users.phone_number',
                'memberships.name as membership_name',
                'frequencies.name as frequency_name'
            ])
            ->join('users', 'user_memberships.user_id', '=', 'users.id')
            ->join('memberships', 'user_memberships.membership_id', '=', 'memberships.id')
            ->join('membership_frequencies', 'user_memberships.membership_frequency_id', '=', 'membership_frequencies.id')
            ->join('frequencies', 'membership_frequencies.frequency_id', '=', 'frequencies.id')
            ->whereHas('user.roles', function($query) {
                $query->where('name', 'member-' . gym()->id);
            });

        // Apply filters
        $this->applyFilters($query);

        // Apply sorting
        $this->applySorting($query);

        $users = $query->paginate(10);

        return view('livewire.membership.user-memberships', [
            'users' => $users,
            'memberships' => \App\Models\Membership::select('id', 'name')->get(),
            'frequencies' => Frequency::select('id', 'name')->get(),
            'membershipStatuses' => $this->getMembershipStatuses()
        ]);
    }

    private function applyFilters($query)
    {
        if ($this->search) {
            $query->where(function($q) {
                $q->where('users.name', 'like', '%' . $this->search . '%')
                  ->orWhere('users.email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->membershipId) {
            $query->where('user_memberships.membership_id', $this->membershipId);
        }

        if ($this->frequencyId) {
            $query->where('membership_frequencies.frequency_id', $this->frequencyId);
        }

        if ($this->membershipStatus) {
            $query->where('user_memberships.membership_status', $this->membershipStatus);
        }

        if ($this->autoRenewal === '1') {
            $query->where('user_memberships.auto_renewal', 1);
        } elseif ($this->autoRenewal === '0') {
            $query->where('user_memberships.auto_renewal', 0);
        }

        if ($this->startDate) {
            $query->whereDate('user_memberships.membership_start_date', '>=', $this->startDate);
        }

        if ($this->expiryDate) {
            $query->whereDate('user_memberships.membership_expiry_date', '<=', $this->expiryDate);
        }
    }

    private function applySorting($query)
    {
        if ($this->sortField === 'name') {
            $query->orderBy('users.name', $this->sortDirection);
        } else {
            $query->orderBy('user_memberships.' . $this->sortField, $this->sortDirection);
        }
    }
} 