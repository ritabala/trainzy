<?php

namespace App\Livewire\Settings\Product;

use App\Models\Product;
use App\Models\Tax;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ProductList extends Component
{
    use WithPagination, LivewireAlert;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $nonSortableFields = ['expiry_date'];
    public $perPage = 5;

    protected $listeners = ['deleteProduct'];

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

    public function handleDeleteProduct(Product $product)
    {
        $this->alert('warning', __('products.confirm_delete'), [
            'position' => 'center',
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => __('common.confirm'),
            'onConfirmed' => 'deleteProduct',
            'showCancelButton' => true,
            'allowOutsideClick' => false,
            'allowEscapeKey' => false,
            'allowEnterKey' => false,
            'stopKeydownPropagation' => false,
            'keydownListenerTarget' => 'document',
            'data' => [
                'product_id' => $product->id,
            ],
            'customClass' => [
                'popup' => 'text-sm leading-relaxed lg:w-1/2 lg:max-w-full'
            ]
        ]);
    }

    public function deleteProduct($data)
    {
        $product = Product::findOrFail($data['product_id']);
        $product->delete();
        $this->resetPage();
        $this->alert('success', __('products.deleted'));
    }

    public function render()
    {
        $query = Product::with('taxes')
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('product_code', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.settings.product.product-list', [
            'products' => $query->paginate($this->perPage),
        ]);
    }
} 