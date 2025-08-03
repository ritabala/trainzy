<?php

namespace App\Livewire\Packages;

use App\Models\Package;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Module;
use App\Models\GlobalCurrency;
use Illuminate\Support\Facades\Cache;
use App\Models\GlobalPaymentGateway;

class CreateEditPackage extends Component
{
    use WithFileUploads;

    public $package;
    public $packageId;
    public $action = 'create';

    // Package fields
    public $name;
    public $description;
    public $price;
    public $duration;
    public $duration_type;
    public $is_active = true;
    public $plan_type = 'paid';
    public $package_name;
    public $package_type = 'standard';
    public $currency_id;
    public $monthly_price;
    public $annual_price;
    public $lifetime_price;
    public $max_members;
    public $max_staff;
    public $max_classes;
    public $selected_modules = [];
    public $selected_additional_modules = [];
    public $global_currencies;
    public $all_modules;
    public $all_additional_modules;
    public $trial_days;
    public $trial_message;
    public $notification_before_days;
    public $monthly_price_status;
    public $annual_price_status;
    public $lifetime_price_status;
    public $stripe_monthly_price_id;
    public $stripe_annual_price_id;
    public $stripe_lifetime_price_id;
    public $paymentGateways;
    public $price_status_check;

    protected function rules()
    {
        $baseRules = [
            'package_name' => ['required', 'string', 'max:255'],
            'max_members' => ['required', 'integer', 'min:1'],
            'max_staff' => ['required', 'integer', 'min:1'],
            'max_classes' => ['required', 'integer', 'min:1'],
            'selected_modules' => ['required', 'array'],
            'selected_modules.*' => ['exists:modules,id'],
            'is_active' => ['boolean'],
            'description' => ['nullable', 'string'],
        ];

        // Trial package rules
        if ($this->package_type === 'trial') {
            return array_merge($baseRules, [
                'plan_type' => ['required', 'in:free'],
                'trial_days' => ['required', 'integer', 'min:1'],
                'trial_message' => ['required', 'string', 'max:255'],
                'notification_before_days' => ['required', 'integer', 'min:1'],
            ]);
        }

        // Default package rules (free plan)
        if ($this->plan_type === 'free' && $this->package_type === 'default') {
            return array_merge($baseRules, [
                'plan_type' => ['required', 'in:free'],
            ]);
        }

        // Paid package rules (standard/lifetime)
        if ($this->plan_type === 'paid') {
            $paidRules = array_merge($baseRules, [
                'plan_type' => ['required', 'in:paid'],
                'package_type' => ['required', 'in:standard,lifetime'],
                'currency_id' => ['required', 'exists:global_currencies,id'],
            ]);

            if ($this->package_type === 'standard') {
                $paidRules['monthly_price'] = ['required_without:annual_price', 'nullable', 'numeric', 'min:0'];
                $paidRules['annual_price'] = ['required_without:monthly_price', 'nullable', 'numeric', 'min:0'];
                $paidRules['lifetime_price'] = ['nullable', 'numeric', 'min:0'];
                if ($this->paymentGateways->stripe_status) {
                    $paidRules['stripe_monthly_price_id'] = ['required_with:monthly_price', 'nullable', 'string', 'max:255'];
                    $paidRules['stripe_annual_price_id'] = ['required_with:annual_price', 'nullable', 'string', 'max:255'];
                    $paidRules['stripe_lifetime_price_id'] = ['nullable', 'string', 'max:255'];
                }
                $paidRules['price_status_check'] = [function($attribute, $value, $fail) {
                    if (empty($this->monthly_price_status) && empty($this->annual_price_status)) {
                        $fail(__('package.at_least_one_plan'));
                        $this->addError('monthly_price_status', __('package.at_least_one_plan'));
                        $this->addError('annual_price_status', __('package.at_least_one_plan'));
                    }
                }];
            } elseif ($this->package_type === 'lifetime') {
                $paidRules['lifetime_price'] = ['required', 'numeric', 'min:0'];
                $paidRules['monthly_price'] = ['nullable', 'numeric', 'min:0'];
                $paidRules['annual_price'] = ['nullable', 'numeric', 'min:0'];

                if ($this->paymentGateways->stripe_status) {
                    $paidRules['stripe_monthly_price_id'] = ['nullable', 'string', 'max:255'];
                    $paidRules['stripe_annual_price_id'] = ['nullable', 'string', 'max:255'];
                    $paidRules['stripe_lifetime_price_id'] = ['required_with:lifetime_price', 'nullable', 'string', 'max:255'];
                }
            }

            return $paidRules;
        }

        return $baseRules;
    }

    public function mount($packageId = null)
    {
        $this->paymentGateways = GlobalPaymentGateway::first();
        $this->global_currencies = GlobalCurrency::all();
        $this->all_modules = Module::where('status', 'active')->where('is_additional', false)->get();
        $this->all_additional_modules = Module::where('status', 'active')->where('is_additional', true)->get();
        if ($packageId) {
            $this->package = Package::with('modules')->findOrFail($packageId);
            $this->action = 'edit';
            $this->fillFormData();
        }
    }

    protected function fillFormData()
    {
        if (!$this->package) return;
        $this->plan_type = $this->package->plan_type;
        $this->package_name = $this->package->package_name;
        $this->package_type = $this->package->package_type;
        $this->currency_id = $this->package->currency_id;
        $this->monthly_price_status = ($this->package->monthly_price > 0) ? true : false;
        $this->monthly_price = $this->package->monthly_price;
        $this->annual_price_status = ($this->package->annual_price > 0) ? true : false;
        $this->annual_price = $this->package->annual_price;
        $this->lifetime_price_status = ($this->package->lifetime_price > 0) ? true : false;
        $this->lifetime_price = $this->package->lifetime_price;
        $this->max_members = $this->package->max_members;
        $this->max_staff = $this->package->max_staff;
        $this->max_classes = $this->package->max_classes;
        $this->selected_modules = $this->package->modules->where('is_additional', false)->pluck('id')->toArray();
        $this->selected_additional_modules = $this->package->modules->where('is_additional', true)->pluck('id')->toArray();
        $this->is_active = $this->package->is_active;
        $this->description = $this->package->description;
        $this->trial_days = $this->package->trial_days;
        $this->trial_message = $this->package->trial_message;
        $this->notification_before_days = $this->package->notification_before_days;
        $this->stripe_monthly_price_id = $this->package->stripe_monthly_price_id;
        $this->stripe_annual_price_id = $this->package->stripe_annual_price_id;
        $this->stripe_lifetime_price_id = $this->package->stripe_lifetime_price_id;
    }

    public function save()
    {
        $validated = $this->validate();
        try {
            DB::beginTransaction();

            $packageData = [
                'plan_type' => $this->plan_type,
                'package_name' => $this->package_name,
                'package_type' => $this->package_type,
                'currency_id' => $this->plan_type === 'paid' ? $this->currency_id : null,
                'monthly_price' => $this->plan_type === 'paid' && $this->package_type === 'standard' && $this->monthly_price_status ? $this->monthly_price : null,
                'annual_price' => $this->plan_type === 'paid' && $this->package_type === 'standard' && $this->annual_price_status ? $this->annual_price : null,
                'lifetime_price' => $this->plan_type === 'paid' && $this->package_type === 'lifetime' ? $this->lifetime_price : null,
                'max_members' => $this->max_members,
                'max_staff' => $this->max_staff,
                'max_classes' => $this->max_classes,
                'is_active' => $this->is_active,
                'description' => $this->description,
                'trial_days' => $this->package_type === 'trial' ? $this->trial_days : null,
                'trial_message' => $this->package_type === 'trial' ? $this->trial_message : null,
                'notification_before_days' => $this->package_type === 'trial' ? $this->notification_before_days : null,
                'stripe_monthly_price_id' => $this->plan_type === 'paid' && $this->package_type === 'standard' && $this->monthly_price_status ? $this->stripe_monthly_price_id : null,
                'stripe_annual_price_id' => $this->plan_type === 'paid' && $this->package_type === 'standard' && $this->annual_price_status ? $this->stripe_annual_price_id : null,
                'stripe_lifetime_price_id' => $this->plan_type === 'paid' && $this->package_type === 'lifetime' ? $this->stripe_lifetime_price_id : null,
            ];

            if ($this->action === 'create') {
                $package = Package::create($packageData);
                session()->flash('message', __('package.created'));
            } else {
                $this->package->update($packageData);
                $package = $this->package;
                session()->flash('message', __('package.updated'));
            }

            $all_selected_modules = array_merge($this->selected_modules, $this->selected_additional_modules);
            $package->modules()->sync($all_selected_modules);
            // Clear the package modules cache
            Cache::forget('package_modules_' . $package->id);
            
            // Clear cache for all gyms that have an active subscription to this package
            $activeSubscriptions = $package->subscriptions()
                ->where('is_active', true)
                ->where(function($query) {
                    $query->whereNull('ends_on')
                          ->orWhere('ends_on', '>', now());
                })
                ->get();
                
            foreach ($activeSubscriptions as $subscription) {
                Cache::forget('gym_' . $subscription->gym_id);
            }
            
            DB::commit();
            return redirect()->route('packages.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', __('package.save_error'));
            $this->addError('save', $e->getMessage());
            throw $e;
        }
    }

    public function cancel()
    {
        return redirect()->route('packages.index');
    }

    public function render()
    {
        return view('livewire.packages.create-edit-package');
    }
} 