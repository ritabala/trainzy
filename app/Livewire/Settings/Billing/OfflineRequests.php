<?php

namespace App\Livewire\Settings\Billing;

use Livewire\Component;
use App\Models\OfflinePaymentDetail;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class OfflineRequests extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updated($propertyName)
    {
        if ($propertyName === 'search') {
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

    public function render()
    {
        $requests = OfflinePaymentDetail::with(['package', 'paymentGateway'])
            ->where('gym_id', Auth::user()->gym_id)
            ->when($this->search, function($query) {
                $query->whereHas('package', function($q) {
                    $q->where('package_name', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('paymentGateway', function($q) {
                    $q->where('offline_method_name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->sortField, function($query) {
                $query->orderBy($this->sortField, $this->sortDirection);
            })
            ->paginate(10);

        return view('livewire.settings.billing.offline-requests', [
            'requests' => $requests
        ]);
    }
} 