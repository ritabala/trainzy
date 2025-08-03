<?php

namespace App\Livewire\Gyms;

use App\Models\Gym;
use App\Models\GlobalCurrency;
use App\Models\Package;
use App\Models\GymPackageSubscription;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\DB;

class GymTable extends Component
{
    use WithPagination, LivewireAlert;

    public $search = '';
    public $currencyId = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $selectedCurrencyName = '';
    public $showImpersonateModal = false;
    public $showAssignPackageModal = false;
    public $selectedGymId = null;
    public $gym = null;
    public $currentPackage = null;
    public $billingCycleOptions = [];
    
    // Package assignment properties
    public $packageId;
    public $startsOn;
    public $endsOn;
    public $expiresOn;
    public $billingCycle;
    public $amount;
    public $isActive = true;
    public $paymentReceived = true;

    protected $queryString = [
        'search' => ['except' => ''],
        'currencyId' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    protected $listeners = [
        'refreshGyms' => '$refresh', 
        'deleteGym',
        'package-assigned' => 'closeAssignPackageModal'
    ];

    public function mount()
    {
        $this->updateSelectedCurrencyName();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedCurrencyId()
    {
        $this->resetPage();
        $this->updateSelectedCurrencyName();
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

    public function resetFilters()
    {
        $this->reset(['search', 'currencyId']);
        $this->resetPage();
        $this->updateSelectedCurrencyName();
    }

    public function handleDeleteGym($id)
    {
        $this->alert('warning', __('gym.confirm_delete'), [
            'position' => 'center',
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'deleteGym',
            'showCancelButton' => true,
            'cancelButtonText' => __('common.cancel'),
            'confirmButtonText' => __('common.yes_delete'),
            'data' => [
                'gym_id' => $id,
            ]
        ]);
    }

    public function deleteGym($data)
    {
        try {            
            $gym = Gym::findOrFail($data['gym_id']);
            $gym->delete();
            
            $this->alert('success', __('gym.deleted'));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', __('gym.delete_failed', ['message' => $e->getMessage()]));
        }
    }

    public function impersonateGym($gymId)
    {
        if (!auth()->user()->can('impersonate_admin')) {
            session()->flash('error', __('gym.unauthorized'));
            return;
        }

        $gym = Gym::findOrFail($gymId);
        
        // Get the first admin of the gym
        $gymAdmin = $gym->users()->role('admin-' . $gym->id)->first();
        
        if (!$gymAdmin) {
            session()->flash('error', __('gym.no_admin_found', ['name' => $gym->name]));
            return;
        }
        
        // Store the current user's ID in the session
        session()->put('impersonator_id', Auth::id());
        
        // Logout the current super-admin
        auth()->guard('web')->logout();
        
        // Clear the session and regenerate it
        session()->invalidate();
        session()->regenerate();
        
        // Log in as the gym admin using their ID
        auth()->guard('web')->login($gymAdmin, true);
        
        // Regenerate the session ID for security
        session()->regenerate();
        
        return redirect()->route('dashboard.index');
    }

    public function prepareBillingCycleOptions($packageId, $currencySymbol)
    {
        $options = [];
        $package = Package::findOrFail($packageId);

        switch ($package->package_type) {
            case 'standard':
                if ($package->monthly_price > 0) {
                    $options['monthly'] = [
                        'label' => 'Monthly - ' . $package->currency->symbol . $package->monthly_price,
                        'amount' => $package->monthly_price,
                        'ends_on' => now()->addMonth()->subDay()->format('Y-m-d'),
                        'expires_on' => now()->addMonth()->addDays(6)->format('Y-m-d')
                    ];
                }
                if ($package->annual_price > 0) {
                    $options['annually'] = [
                        'label' => 'Annually - ' . $package->currency->symbol . $package->annual_price,
                        'amount' => $package->annual_price,
                        'ends_on' => now()->addYear()->subDay()->format('Y-m-d'),
                        'expires_on' => now()->addYear()->addDays(6)->format('Y-m-d')
                    ];
                }
                break;

            case 'lifetime':
                $options['lifetime'] = [
                    'label' => 'Lifetime - ' . $package->currency->symbol . $package->lifetime_price,
                    'amount' => $package->lifetime_price,
                    'ends_on' => null,
                    'expires_on' => null
                ];
                break;

            case 'default':
                $options['free'] = [
                    'label' => 'Free',
                    'amount' => 0,
                    'ends_on' => null,
                    'expires_on' => null
                ];
                break;

            case 'trial':
                $options['trial'] = [
                    'label' => 'Trial - ' . $package->trial_days . ' days free',
                    'amount' => 0,
                    'ends_on' => now()->addDays($package->trial_days)->subDay()->format('Y-m-d'),
                    'expires_on' => now()->addDays($package->trial_days)->subDay()->format('Y-m-d')
                ];
                break;
        }

        $this->billingCycleOptions = $options;
    }

    public function openAssignPackageModal($gymId)
    {
        try {
            $this->selectedGymId = $gymId;
            $this->gym = Gym::findOrFail($this->selectedGymId);

            if ($currentSubscription = $this->gym->activePackageSubscription()) {
                $this->currentPackage = $currentSubscription;
                $this->isActive = $currentSubscription->is_active;
            } else {
                $this->currentPackage = null;
                $this->isActive = true;
            }

            $this->showAssignPackageModal = true;
        } catch (\Exception $e) {
            $this->alert('error', __('gym.load_failed', ['message' => $e->getMessage()]));
        }
    }

    public function closeAssignPackageModal()
    {
        $this->showAssignPackageModal = false;
        $this->selectedGymId = null;
        $this->gym = null;
        $this->currentPackage = null;
        $this->reset([
            'packageId',
            'startsOn',
            'endsOn',
            'expiresOn',
            'billingCycle',
            'amount',
            'isActive',
            'billingCycleOptions'
        ]);
        $this->resetValidation();
    }

    public function assignPackage()
    {
        $rules = [
            'packageId' => 'required|exists:packages,id',
            'startsOn' => 'required|date',
            'endsOn' => 'required|date|after:startsOn',
            'expiresOn' => 'required|date|after_or_equal:endsOn',
            'billingCycle' => 'required|in:monthly,annually,lifetime,free,trial',
            'isActive' => 'boolean',
            'paymentReceived' => 'boolean',
        ];

        if ($this->packageId) {
            $package = Package::find($this->packageId);
            if ($package && $package->plan_type === 'paid') {
                $rules['amount'] = 'required|decimal:2|min:0.00';
            }
            if ($package && $package->package_type === 'lifetime') {
                $rules['endsOn'] = 'nullable|date|after:startsOn';
                $rules['expiresOn'] = 'nullable|date|after_or_equal:endsOn';
            }
        }

        $validated = $this->validate($rules);

        try {
            DB::beginTransaction();

            $gym = Gym::findOrFail($this->selectedGymId);            
            $package = Package::findOrFail($this->packageId);

            $gym->packageSubscriptions()
                ->where('is_active', true)
                ->update(['is_active' => false]);

            // Format dates for database
            $startsOn = \Carbon\Carbon::parse($this->startsOn)->format('Y-m-d');
            $endsOn = $this->endsOn ? \Carbon\Carbon::parse($this->endsOn)->format('Y-m-d') : null;
            $expiresOn = $this->expiresOn ? \Carbon\Carbon::parse($this->expiresOn)->format('Y-m-d') : null;

            $subscriptionData = [
                'gym_id' => $this->selectedGymId,
                'package_id' => $this->packageId,
                'billing_cycle' => $this->billingCycle,
                'amount' => (float) $this->amount,
                'starts_on' => $startsOn,
                'ends_on' => $endsOn,
                'expires_on' => $expiresOn,
                'is_active' => $this->isActive,
                'status' => 'completed',
            ];
                
            $subscription = $gym->packageSubscriptions()->create($subscriptionData);

            if ($this->paymentReceived) {
                $paymentData = [
                    'gym_id' => $this->selectedGymId,
                    'package_id' => $this->packageId,
                    'subscription_id' => $subscription->id,
                    'amount' => (float) $this->amount,
                    'payment_gateway' => 'offline',
                    'payment_status' => 'completed',
                    'transaction_id' => 'offline.' . now()->timestamp,
                    'paid_on' => now(),
                ];

                $gym->packagePayments()->create($paymentData);
            }

            DB::commit();

            $this->dispatch('package-assigned', [
                'gym_id' => $this->selectedGymId,
                'package_id' => $this->packageId,
                'subscription_id' => $subscription->id
            ]);

            $this->alert('success', __('gym.package_assigned_successfully'));
            $this->closeAssignPackageModal();
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->alert('error', __('gym.package_assignment_failed', ['message' => $e->getMessage()]));
            $this->addError('save', $e->getMessage());
        }
    }

    private function updateSelectedCurrencyName()
    {
        if ($this->currencyId) {
            $currency = Currency::find($this->currencyId);
            $this->selectedCurrencyName = $currency ? $currency->name : '';
        } else {
            $this->selectedCurrencyName = '';
        }
    }

    public function updatedBillingCycle($value)
    {
        if ($value && $this->packageId) {
            $this->setBillingCycleDetails($value);
        }
    }

    public function updatedPackageId($value)
    {
        if ($value) {
            $package = Package::findOrFail($value);
            $currency = $this->gym ? $this->gym->currency : GlobalCurrency::first();
            
            $this->prepareBillingCycleOptions($value, $currency->symbol);
            $this->startsOn = now()->format('Y-m-d');
            
            // If there's only one billing cycle option, automatically select it
            if (count($this->billingCycleOptions) === 1) {
                $this->billingCycle = array_key_first($this->billingCycleOptions);
                $this->setBillingCycleDetails($this->billingCycle);
            } else {
                $this->billingCycle = null;
                // Only override the billing cycle if there's a current package and we have multiple options
                if ($this->currentPackage) {
                    $this->billingCycle = $this->currentPackage->billing_cycle;
                    $this->setBillingCycleDetails($this->billingCycle);
                }
            }
        } else {
            $this->resetBillingCycleDetails();
        }
    }

    private function setBillingCycleDetails($billingCycle)
    {
        if (!isset($this->billingCycleOptions[$billingCycle])) {
            return;
        }

        $details = $this->billingCycleOptions[$billingCycle];
        $this->amount = $details['amount'];
        $this->endsOn = $details['ends_on'];
        $this->expiresOn = $details['expires_on'];
    }

    private function resetBillingCycleDetails()
    {
        $this->billingCycleOptions = [];
        $this->billingCycle = null;
        $this->amount = null;
        $this->endsOn = null;
        $this->expiresOn = null;
    }

    public function updatedStartsOn($value)
    {
        if (!$value || !$this->packageId || !$this->billingCycle) return;

        $startDate = \Carbon\Carbon::parse($value);
        $package = Package::find($this->packageId);
        
        if ($package->package_type === 'trial') {
            $this->endsOn = $startDate->copy()->addDays($package->trial_days)->subDay()->format('Y-m-d');
            $this->expiresOn = $this->endsOn;
        } else if ($package->package_type === 'standard') {
            $details = $this->billingCycleOptions[$this->billingCycle];
            $this->endsOn = $startDate->copy()->addMonth()->subDay()->format('Y-m-d');
            $this->expiresOn = $startDate->copy()->addMonth()->addDays(6)->format('Y-m-d');
        }
    }

    public function render()
    {
        $query = Gym::query()
            ->with(['currency', 'packageSubscriptions' => function($query) {
                $query->where('is_active', true)
                    ->with('package');
            }])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%')
                        ->orWhere('website', 'like', '%' . $this->search . '%')
                        ->orWhere('timezone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->currencyId, function ($query) {
                $query->where('currency_id', $this->currencyId);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        // Get packages based on selected gym's currency
        $packagesQuery = Package::where('is_active', true);
        
        // if ($this->selectedGymId && $this->gym) {
        //     $packagesQuery->where(function($query) {
        //         $query->where('currency_id', $this->gym->currency_id)
        //             ->orWhere('package_type', 'default')
        //             ->orWhere('package_type', 'trial');
        //     });
        // }

        return view('livewire.gyms.gym-table', [
            'gyms' => $query->paginate(10),
            'currencies' => GlobalCurrency::orderBy('name')->get(),
            'packages' => $packagesQuery->get()
        ]);
    }
}
