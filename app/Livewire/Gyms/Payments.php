<?php

namespace App\Livewire\Gyms;

use Livewire\Component;
use App\Models\PackagePayment;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Payments extends Component
{
    use WithPagination, LivewireAlert;

    public $search = '';
    public $paymentStatus = '';
    public $paymentGateway = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $nonSortableFields = ['actions'];
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'paymentStatus' => ['except' => ''],
        'paymentGateway' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
    ];

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search', 'paymentStatus', 'paymentGateway'])) {
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
        $query = PackagePayment::query()
            ->with(['gym', 'package', 'subscription'])
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->whereHas('gym', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('package', function ($q) {
                        $q->where('package_name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('transaction_id', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->paymentStatus !== '', function ($query) {
                return $query->where('payment_status', $this->paymentStatus);
            })
            ->when($this->paymentGateway !== '', function ($query) {
                return $query->where('payment_gateway', $this->paymentGateway);
            });

        $payments = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.gyms.payments', [
            'payments' => $payments,
            'paymentStatuses' => [
                '' => __('common.all_status'),
                'pending' => __('common.pending'),
                'completed' => __('common.completed'),
                'failed' => __('common.failed'),
                'refunded' => __('common.refunded'),
            ],
            'paymentGateways' => [
                '' => __('package.all_gateways'),
                'stripe' => __('common.stripe'),
                'offline' => __('package.offline'),
            ],
        ]);
    }
}
