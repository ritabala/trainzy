<?php

namespace App\Livewire\SuperadminSetting\SuperAdminCurrencies;

use Livewire\Component;
use App\Models\GlobalCurrency;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithPagination;

class CurrencyList extends Component
{
    use LivewireAlert, WithPagination;

    public $selectedCurrency;
    public $showEditModal = false;
    public $showAddModal = false;
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $nonSortableFields = ['preview'];

    protected $listeners = [
        'close-edit-currency' => 'closeEditModal',
        'close-add-currency' => 'closeAddModal',
        'deleteCurrency',
        'currencyCreated' => 'handleCurrencyCreated',
        'currencyUpdated' => 'handleCurrencyUpdated'
    ];

    public function updated($propertyName)
    {
        if ($propertyName === 'search') {
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

    public function addCurrency()
    {
        $this->showAddModal = true;
    }

    public function editCurrency($id)
    {
        $this->selectedCurrency = GlobalCurrency::find($id);
        $this->showEditModal = true;
    }

    public function handleDeleteCurrency($id)
    {
        $this->alert('warning', __('settings.currencies.confirm_delete'), [
            'position' => 'center',
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'deleteCurrency',
            'showCancelButton' => true,
            'cancelButtonText' => __('common.cancel'),
            'confirmButtonText' => __('common.yes_delete'),
            'data' => [
                'currency_id' => $id,
            ],
            'customClass' => [
                'popup' => 'text-sm leading-relaxed lg:w-1/2 lg:max-w-full'
            ]
        ]);
    }

    public function deleteCurrency($data)
    {
        try {
            $currency = GlobalCurrency::findOrFail($data['currency_id']);
            
            // Check if this is the gym's default currency
            if ($currency->id === global_settings()->currency_id) {
                $this->alert('error', __('settings.currencies.default_currency_error'));
                return;
            }
            
            $currency->delete();
            $this->alert('success', __('settings.currencies.deleted'));
        } catch (\Exception $e) {
            $this->alert('error', __('settings.currencies.delete_failed') . ' ' . $e->getMessage());
        }
    }

    public function handleCurrencyCreated()
    {
        session()->flash('message', __('settings.currencies.created'));
    }

    public function handleCurrencyUpdated()
    {
        session()->flash('message', __('settings.currencies.updated'));
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
    }

    public function closeAddModal()
    {
        $this->showAddModal = false;
    }

    public function render()
    {
        $currencies = GlobalCurrency::when($this->search, function($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('code', 'like', '%' . $this->search . '%')
                ->orWhere('symbol', 'like', '%' . $this->search . '%');
        })
        ->when($this->sortField, function($query) {
            $query->orderBy($this->sortField, $this->sortDirection);
        })
        ->paginate(10);

        return view('livewire.superadmin-setting.super-admin-currencies.currency-list', [
            'currencies' => $currencies
        ]);
    }

}
