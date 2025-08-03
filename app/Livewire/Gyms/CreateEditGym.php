<?php

namespace App\Livewire\Gyms;

use App\Models\Gym;
use App\Models\Currency;
use App\Models\GlobalCurrency;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use DateTimeZone;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Package;

class CreateEditGym extends Component
{
    use WithFileUploads;

    public $gym;
    public $gymId;
    public $action = 'create';
    public $currencies;
    public $timezones;
    // Gym fields
    public $name;
    public $address;
    public $phone;
    public $email;
    public $website;
    public $logo;
    public $timezone;
    public $currency_id;
    public $admin_name;
    public $admin_email;
    public $admin_password;

    protected function rules()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'website' => ['nullable', 'string', 'url', 'max:255'],
            'logo' => ['nullable', 'image', 'max:1024'],
            'timezone' => ['nullable', 'string'],
            'currency_id' => ['required'],
        ];

        if ($this->action === 'create') {
            $rules['admin_name'] = ['required', 'string', 'max:255', 'min:3'];
            $rules['admin_email'] = ['required', 'string', 'email', 'max:255', 'unique:users,email'];
            $rules['admin_password'] = ['required', 'string', 'min:8'];
        }

        return $rules;
    }

    protected function messages()
    {
        return [
            'admin_name.required_if' => __('gym.admin_name_required'),
            'admin_email.required_if' => __('gym.admin_email_required'),
            'admin_password.required_if' => __('gym.admin_password_required'),
        ];
    }

    public function mount($gymId = null)
    {
        if (is_null($gymId)) {
            $this->currencies = GlobalCurrency::all();
        }
        else{
            $this->currencies = Currency::where('gym_id', $gymId)->get();
        }
        
        $this->timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $this->currency_id = global_settings()->currency_id;

        if ($gymId) {
            $this->gym = Gym::findOrFail($gymId);
            $this->action = 'edit';
            $this->fillFormData();
        }
    }

    protected function fillFormData()
    {
        if (!$this->gym) {
            return;
        }

        $this->name = $this->gym->name;
        $this->address = $this->gym->address;
        $this->phone = $this->gym->phone;
        $this->email = $this->gym->email;
        $this->website = $this->gym->website;
        $this->timezone = $this->gym->timezone;
        $this->currency_id = $this->gym->currency_id;
    }

    public function save()
    {
        $validatedData = $this->validate();

        try {
            DB::beginTransaction();

            $gymData = [
                'name' => $this->name,
                'address' => $this->address,
                'phone' => $this->phone,
                'email' => $this->email,
                'website' => $this->website,
                'timezone' => $this->timezone,
                'currency_id' => $this->currency_id,
            ];

            if ($this->logo) {
                $path = $this->logo->store('gym-logos', 'public');
                if ($this->gym && $this->gym->logo) {
                    Storage::disk('public')->delete($this->gym->logo);
                }
                $gymData['logo'] = $path;
            }

            if ($this->action === 'create') {
                $gym = Gym::create($gymData);

                $assignPackage = Package::where('package_type', 'trial')->where('is_active', 1)->first();

                if (!$assignPackage) {
                    $assignPackage = Package::where('package_type', 'default')->first();
                }

                // Format dates for database
                $startsOn = now()->format('Y-m-d');
                $endsOn = $assignPackage->package_type === 'trial' ? now()->addDays($assignPackage->trial_days)->format('Y-m-d') : null;
                $expiresOn = $assignPackage->package_type === 'trial' ? now()->addDays($assignPackage->trial_days)->format('Y-m-d') : null;

                $subscriptionData = [
                    'gym_id' => $gym->id,
                    'package_id' => $assignPackage->id,
                    'billing_cycle' => $assignPackage->package_type === 'trial' ? 'trial' : 'free',
                    'amount' => 0,
                    'starts_on' => $startsOn,
                    'ends_on' => $endsOn,
                    'expires_on' => $expiresOn,
                    'is_active' => 1,
                    'status' => 'completed',
                ];
                    
                $subscription = $gym->packageSubscriptions()->create($subscriptionData);

                $role = Role::firstOrCreate(['name' => 'admin-' . $gym->id, 'guard_name' => 'web', 'display_name' => 'Admin']);
                Role::firstOrCreate(['name' => 'staff-' . $gym->id, 'guard_name' => 'web', 'display_name' => 'Staff']);
                Role::firstOrCreate(['name' => 'member-' . $gym->id, 'guard_name' => 'web', 'display_name' => 'Member']);


                $role->givePermissionTo(Permission::all());

                $user = User::createUserWithRole($this->admin_email, $this->admin_name, $role->name, $gym->id, $this->admin_password);

                session()->flash('message', __('gym.created'));
            } else {
                $this->gym->update($gymData);
                session()->flash('message', __('gym.updated'));
            }

            DB::commit();
            return redirect()->route('gyms.index');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', __('gym.save_error'));
            $this->addError('save', $e->getMessage());
            throw $e;
        }
    }

    public function cancel()
    {
        return redirect()->route('gyms.index');
    }

    public function render()
    {
        return view('livewire.gyms.create-edit-gym');
    }
}
