<?php

namespace App\Livewire\Settings\Tax;

use App\Models\Tax;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;


class TaxList extends Component
{
    use WithPagination, LivewireAlert;

    public $search = '';
    public $sortField = 'tax_name';
    public $sortDirection = 'asc';
    public $nonSortableFields = ['created_at'];
    public $perPage = 5;

    protected $listeners = ['deleteTax'];

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search'])) {
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

    public function handleDeleteTax(Tax $tax)
    {
        $this->alert('warning', __('products.confirm_delete_tax'), [
            'position' => 'center',
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => __('common.confirm'),
                'onConfirmed' => 'deleteTax',
                'showCancelButton' => true,
                'allowOutsideClick' => false,
                'allowEscapeKey' => false,
                'allowEnterKey' => false,
                'stopKeydownPropagation' => false,
                'keydownListenerTarget' => 'document',
                'data' => [
                'tax_id' => $tax->id,
                ],
                'customClass' => [
                'popup' => 'text-sm leading-relaxed lg:w-1/2 lg:max-w-full'
            ]
        ]);
    }

    public function deleteTax($data)
    {
        $tax = Tax::findOrFail($data['tax_id']);
        $tax->delete();
        $this->resetPage();
        $this->alert('success', __('products.tax_deleted'));
    }

    public function render()
    {
        return view('livewire.settings.tax.tax-list', [
            'taxes' => Tax::when($this->search, function($query) {
                $query->where('tax_name', 'like', '%' . $this->search . '%')
                    ->orWhere('tax_percent', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage)
        ]);
    }
} 