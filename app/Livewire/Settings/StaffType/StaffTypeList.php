<?php

namespace App\Livewire\Settings\StaffType;

use App\Models\StaffType;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class StaffTypeList extends Component
{
    use WithPagination, LivewireAlert;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 5;

    protected $listeners = ['deleteStaffType'];

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search'])) {
            $this->resetPage();
        }
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

    public function handleDeleteStaffType(StaffType $staffType)
    {
        $this->alert('warning', __('staff.delete_staff_type_confirmation'), [
            'position' => 'center',
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => __('common.confirm'),
            'onConfirmed' => 'deleteStaffType',
            'showCancelButton' => true,
            'allowOutsideClick' => false,
            'allowEscapeKey' => false,
            'allowEnterKey' => false,
            'stopKeydownPropagation' => false,
            'keydownListenerTarget' => 'document',
            'data' => [
                'staff_type_id' => $staffType->id,
            ],
            'customClass' => [
                'popup' => 'text-sm leading-relaxed lg:w-1/2 lg:max-w-full'
            ]
        ]);
    }

    public function deleteStaffType($data)
    {
        $staffType = StaffType::findOrFail($data['staff_type_id']);
        $staffType->delete();
        $this->resetPage();
        $this->alert('success', __('staff.staff_type_deleted_successfully'));
    }

    public function render()
    {
        $query = StaffType::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.settings.staff-type.staff-type-list', [
            'staffTypes' => $query->paginate($this->perPage),
        ]);
    }
} 