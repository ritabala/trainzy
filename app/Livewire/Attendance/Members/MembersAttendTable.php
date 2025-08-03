<?php

namespace App\Livewire\Attendance\Members;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Attendance;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Carbon\Carbon;

class MembersAttendTable extends Component
{
    use WithPagination, LivewireAlert;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'check_in_at';
    public $sortDirection = 'desc';
    public $filterDate = '';
    public $nonSortableFields = ['actions'];

    protected $listeners = ['deleteMemberAttend'];

    public function mount()
    {
        $this->filterDate = now(gym()->timezone)->format('Y-m-d');
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search', 'filterDate'])) {
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

    public function handleDeleteMemberAttend($id)
    {
        $this->alert('warning', __('members.attendance.confirm_delete'), [
            'position' => 'center',
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'deleteMemberAttend',
            'showCancelButton' => true,
            'cancelButtonText' => __('common.cancel'),
            'confirmButtonText' => __('common.yes_delete'),
            'data' => [
                'attend_id' => $id,
            ],
            'customClass' => [
                'popup' => 'text-sm leading-relaxed lg:w-1/2 lg:max-w-full'
            ]
        ]);
    }

    public function deleteMemberAttend($data)
    {
        try {
            $attendance = Attendance::findOrFail($data['attend_id']);
            $attendance->delete();
            $this->alert('success', __('members.attendance.deleted'));
        } catch (\Exception $e) {
            $this->alert('error', __('members.attendance.delete_failed', ['message' => $e->getMessage()]));
        }
    }

    public function render()
    {
        $query = Attendance::query()
            ->with(['user', 'activityClass'])
            ->whereHas('user', function ($query) {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', 'member-' . gym()->id);
                });
            })
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterDate, function ($query) {
                $query->whereDate('check_in_at', Carbon::parse($this->filterDate)->format('Y-m-d'));
            })
            ->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.attendance.members.members-attend-table', [
            'attendances' => $query->paginate($this->perPage)
        ]);
    }

    public function edit($id)
    {
        return redirect()->route('attendance.members.edit', $id);
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->filterDate = '';
        $this->resetPage();
    }

    public function getHasActiveFiltersProperty()
    {
        return !empty($this->search) || !empty($this->filterDate);
    }
} 