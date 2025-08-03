<?php

namespace App\Livewire\OfflinePaymentRequests;

use Livewire\Component;
use App\Models\OfflinePaymentDetail;
use App\Models\GymPackageSubscription;
use App\Models\PackagePayment;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\DB;

class OfflinePaymentRequests extends Component
{
    use WithPagination, LivewireAlert;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $nonSortableFields = ['actions'];

    protected $listeners = [
        'approvePayment',
        'rejectPayment',
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

    public function handleApprove($requestId)
    {
        $this->alert('warning', __('settings.payment_gateways.offline.confirm_approve'), [
            'position' => 'center',
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'approvePayment',
            'showCancelButton' => true,
            'cancelButtonText' => __('settings.payment_gateways.offline.cancel'),
            'confirmButtonText' => __('settings.payment_gateways.offline.approve'),
            'data' => [
                'request_id' => $requestId,
            ],
            'customClass' => [
                'popup' => 'text-sm leading-relaxed lg:w-1/2 lg:max-w-full'
            ]
        ]);
    }

    public function approvePayment($data)
    {
        try {
            DB::beginTransaction();

            $request = OfflinePaymentDetail::with(['gym', 'package', 'paymentGateway'])
                ->findOrFail($data['request_id']);

            // Deactivate any existing active subscription
            GymPackageSubscription::where('gym_id', $request->gym_id)
                ->where('is_active', true)
                ->update([
                    'is_active' => false,
                ]);

            // Create subscription record
            $gymSubscription = GymPackageSubscription::create([
                'gym_id' => $request->gym_id,
                'package_id' => $request->package_id,
                'billing_cycle' => $request->billing_cycle,
                'starts_on' => now(),
                'ends_on' => $request->package->package_type === 'lifetime' ? null : now()->addDays(30),
                'expires_on' => $request->package->package_type === 'lifetime' ? null : now()->addDays(30 + 7),
                'is_active' => true,
                'amount' => $request->amount,
                'status' => 'completed',
                'payment_gateway' => 'offline',
                'offline_payment_method' => $request->paymentGateway->offline_method_name,
            ]);

            // Create payment record
            PackagePayment::create([
                'gym_id' => $request->gym_id,
                'package_id' => $request->package_id,
                'subscription_id' => $gymSubscription->id,
                'amount' => $request->amount,
                'payment_gateway' => 'offline',
                'payment_status' => 'completed',
                'transaction_id' => 'OFFLINE-' . uniqid(),
                'remarks' => $request->description,
                'receipt_path' => $request->document_path,
                'paid_on' => now(),
            ]);

            // Update offline payment request
            $request->update([
                'is_accepted' => true,
                'accepted_at' => now(),
            ]);

            DB::commit();
            $this->alert('success', __('settings.payment_gateways.offline.approved'));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', __('settings.payment_gateways.offline.approve_failed') . ' ' . $e->getMessage());
        }
    }

    public function handleReject($requestId)
    {
        $this->alert('warning', __('settings.payment_gateways.offline.confirm_reject'), [
            'position' => 'center',
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'rejectPayment',
            'showCancelButton' => true,
            'cancelButtonText' => __('settings.payment_gateways.offline.cancel'),
            'confirmButtonText' => __('settings.payment_gateways.offline.reject'),
            'data' => [
                'request_id' => $requestId,
            ],
            'customClass' => [
                'popup' => 'text-sm leading-relaxed lg:w-1/2 lg:max-w-full'
            ]
        ]);
    }

    public function rejectPayment($data)
    {
        try {
            $request = OfflinePaymentDetail::findOrFail($data['request_id']);
            $request->update([
                'is_accepted' => false,
                'accepted_at' => now(),
            ]);
            $this->alert('success', __('settings.payment_gateways.offline.rejected'));
        } catch (\Exception $e) {
            $this->alert('error', __('settings.payment_gateways.offline.reject_failed') . ' ' . $e->getMessage());
        }
    }

    public function render()
    {
        $requests = OfflinePaymentDetail::with(['gym', 'package', 'paymentGateway'])
            ->when($this->search, function($query) {
                $query->whereHas('gym', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('package', function($q) {
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
        return view('livewire.offline-payment-requests.offline-payment-requests', [
            'requests' => $requests
        ]);
    }
} 