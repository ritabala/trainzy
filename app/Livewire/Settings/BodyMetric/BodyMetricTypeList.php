<?php

namespace App\Livewire\Settings\BodyMetric;

use App\Models\BodyMetricType;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class BodyMetricTypeList extends Component
{
    use WithPagination, LivewireAlert;

    public $search = '';
    public $sortField = 'display_order';
    public $sortDirection = 'asc';
    public $perPage = 10;

    protected $listeners = ['deleteBodyMetricType'];

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

    public function handleDeleteBodyMetricType(BodyMetricType $bodyMetricType)
    {
        $this->alert('warning', __('body_metrics.delete_body_metric_type_confirm'), [
            'position' => 'center',
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => __('common.confirm'),
            'onConfirmed' => 'deleteBodyMetricType',
            'showCancelButton' => true,
            'allowOutsideClick' => false,
            'allowEscapeKey' => false,
            'allowEnterKey' => false,
            'stopKeydownPropagation' => false,
            'keydownListenerTarget' => 'document',
            'data' => [
                'body_metric_type_id' => $bodyMetricType->id,
            ],
            'customClass' => [
                'popup' => 'text-sm leading-relaxed lg:w-1/2 lg:max-w-full'
            ]
        ]);
    }

    public function deleteBodyMetricType($data)
    {
        try {
            $bodyMetricType = BodyMetricType::findOrFail($data['body_metric_type_id']);
            $bodyMetricType->delete();
            $this->resetPage();
            $this->alert('success', __('body_metrics.body_metric_deleted_successfully'));
        } catch (\Exception $e) {
            $this->alert('error', __('body_metrics.failed_to_delete_body_metric') . $e->getMessage());
        }
    }

    public function render()
    {
        $query = BodyMetricType::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('unit', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.settings.body-metric.body-metric-type-list', [
            'bodyMetricTypes' => $query->paginate($this->perPage),
        ]);
    }
} 