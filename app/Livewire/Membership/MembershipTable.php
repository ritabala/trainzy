<?php

namespace App\Livewire\Membership;

use App\Models\Membership;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class MembershipTable extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $isActive = '';
    public $perPage = 5;
    public $nonSortableFields = ['actions', 'price', 'services', 'is_active'];


    protected $listeners = ['deleteMembership'];

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'isActive' => ['except' => ''],
        'perPage' => ['except' => 5],
    ];

    public function sortBy($field)
    {

        if (!in_array($field, $this->nonSortableFields)) {
            if ($this->sortField === $field) {
                $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                $this->sortField = $field;
                $this->sortDirection = 'asc';
            }
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function handleDeleteMembership($id)
    {
        $this->alert('warning', __('membership.confirm_delete'), [
            'position' => 'center',
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => __('common.confirm'),
            'onConfirmed' => 'deleteMembership',
            'showCancelButton' => true,
            'data' => [
                'membership_id' => $id,
            ],
            'customClass' => [
                'popup' => 'text-sm leading-relaxed lg:w-1/2 lg:max-w-full'
            ]
        ]);
    }

    public function deleteMembership($data)
    {
        try {
            $membership = Membership::findOrFail($data['membership_id']);
            
            // Check if membership is assigned to any users
            if ($membership->userMemberships()->exists()) {
                $this->alert('error', __('membership.cannot_delete_assigned'));
                return;
            }
            
            $membership->delete();

            $this->alert('success', __('membership.deleted'));
        } catch (\Exception $e) {
            $this->alert('error', __('membership.failed_to_delete'));
        }
    }

    public function render()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_memberships'), 403);
        return view('livewire.membership.membership-table', [
            'memberships' => Membership::query()
                ->with(['membershipFrequencies.frequency', 'membershipServices.service'])
                ->when($this->search, fn($query) =>
                $query->where('name', 'like', '%' . $this->search . '%'))
                ->when($this->isActive !== '', fn($query) =>
                $query->where('is_active', $this->isActive))
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage)
        ]);
    }
}
