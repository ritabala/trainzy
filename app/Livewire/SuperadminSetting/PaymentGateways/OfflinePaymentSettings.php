<?php

namespace App\Livewire\SuperAdminSetting\PaymentGateways;

use Livewire\Component;
use App\Models\GlobalPaymentGateway;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class OfflinePaymentSettings extends Component
{
    use WithPagination, LivewireAlert;

    public $showModal = false;
    public $editingId = null;
    public $name = '';
    public $description = '';
    public $isActive = true;
    public $search = '';
    public $sortField = 'offline_method_name';
    public $sortDirection = 'asc';
    public $nonSortableFields = ['actions'];

    protected $listeners = [
        'deletePaymentMethod',
        'closeModal' => 'closeModal'
    ];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:global_payment_gateways,offline_method_name,' . $this->editingId,
            'description' => 'nullable|string',
            'isActive' => 'boolean'
        ];
    }

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

    public function render()
    {
        $methods = GlobalPaymentGateway::whereNotNull('offline_method_name')
            ->when($this->search, function($query) {
                $query->where('offline_method_name', 'like', '%' . $this->search . '%')
                    ->orWhere('offline_method_description', 'like', '%' . $this->search . '%');
            })
            ->when($this->sortField, function($query) {
                $query->orderBy($this->sortField, $this->sortDirection);
            })
            ->paginate(10);

        return view('livewire.superadmin-setting.payment-gateways.offline-payment-settings', [
            'methods' => $methods
        ]);
    }

    public function openModal($methodId = null)
    {
        if ($methodId) {
            $method = GlobalPaymentGateway::findOrFail($methodId);
            $this->editingId = $method->id;
            $this->name = $method->offline_method_name;
            $this->description = $method->offline_method_description;
            $this->isActive = $method->offline_method_status == 1 ? true : false;
        } else {
            $this->reset(['editingId', 'name', 'description', 'isActive']);
        }
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['editingId', 'name', 'description', 'isActive']);
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $method = GlobalPaymentGateway::findOrFail($this->editingId);
            $method->update([
                'offline_method_name' => $this->name,
                'offline_method_description' => $this->description,
                'offline_method_status' => $this->isActive
            ]);
            $message = __('settings.payment_gateways.offline.updated');
        } else {
            GlobalPaymentGateway::create([
                'offline_method_name' => $this->name,
                'offline_method_description' => $this->description,
                'offline_method_status' => $this->isActive
            ]);
            $message = __('settings.payment_gateways.offline.created');
        }

        $this->closeModal();
        $this->alert('success', $message);
    }

    public function toggleStatus($methodId)
    {
        $method = GlobalPaymentGateway::findOrFail($methodId);
        $method->update(['offline_method_status' => !$method->offline_method_status]);
        
        $this->alert('success', __('settings.payment_gateways.offline.updated'));
    }

    public function handleDelete($methodId)
    {
        $this->alert('warning', __('settings.payment_gateways.offline.confirm_delete'), [
            'position' => 'center',
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'deletePaymentMethod',
            'showCancelButton' => true,
            'cancelButtonText' => __('settings.payment_gateways.offline.cancel'),
            'confirmButtonText' => __('settings.payment_gateways.offline.delete_tooltip'),
            'data' => [
                'method_id' => $methodId,
            ],
            'customClass' => [
                'popup' => 'text-sm leading-relaxed lg:w-1/2 lg:max-w-full'
            ]
        ]);
    }

    public function deletePaymentMethod($data)
    {
        try {
            $method = GlobalPaymentGateway::findOrFail($data['method_id']);
            $method->delete();
            $this->alert('success', __('settings.payment_gateways.offline.deleted'));
        } catch (\Exception $e) {
            $this->alert('error', __('settings.payment_gateways.offline.delete_failed') . ' ' . $e->getMessage());
        }
    }
} 