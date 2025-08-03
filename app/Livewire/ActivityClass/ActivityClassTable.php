<?php

namespace App\Livewire\ActivityClass;

use App\Models\ActivityClass;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ActivityClassTable extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 5;
    public $nonSortableFields = ['actions'];

    protected $listeners = ['deleteActivityClass'];

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
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

    public function handleDeleteActivityClass($id)
    {
        $this->alert('warning', __('activity.confirm_delete'), [
            'position' => 'center',
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => __('common.confirm'),
            'onConfirmed' => 'deleteActivityClass',
            'showCancelButton' => true,
            'data' => [
                'activity_class_id' => $id,
            ],
            'customClass' => [
                'popup' => 'text-sm leading-relaxed lg:w-1/2 lg:max-w-full'
            ]
        ]);
    }

    public function deleteActivityClass($data)
    {
        $activityClass = ActivityClass::findOrFail($data['activity_class_id']);
        $activityClass->delete();
        
        $this->alert('success', __('activity.deleted'));
    }

    public function render()
    {
        $query = ActivityClass::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        $activityClasses = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.activity-class.activity-class-table', [
            'activityClasses' => $activityClasses
        ]);
    }
} 