<?php

namespace App\Livewire\Service;

use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ServiceTable extends Component
{
    use WithPagination, LivewireAlert;

    public $search = '';
    public $isActive = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $nonSortableFields = ['actions'];
    public $perPage = 5;

    protected $listeners = ['deleteService'];

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search', 'isActive'])) {
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

    public function handleDeleteService($id)
    {
        $this->alert('warning', __('services.confirm_delete'), [
            'position' => 'center',
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => __('common.confirm'),
            'onConfirmed' => 'deleteService',
            'showCancelButton' => true,
            'allowOutsideClick' => false,
            'allowEscapeKey' => false,
            'allowEnterKey' => false,
            'stopKeydownPropagation' => false,
            'keydownListenerTarget' => 'document',
            'text' => __('services.confirm_delete_text'),
            'data' => [
                'service_id' => $id,
            ],
            'customClass' => [
                'popup' => 'text-sm leading-relaxed lg:w-1/2 lg:max-w-full'
            ]
        ]);
    }

    public function deleteService($data)
    {
        $service = Service::findOrFail($data['service_id']);
        $service->delete();
        
        $this->alert('success', __('services.deleted'));
    }

    public function render()
    {
        $query = Service::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->isActive !== '') {
            $query->where('is_active', $this->isActive);
        }

        $services = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.service.service-table', [
            'services' => $services
        ]);
    }
}
