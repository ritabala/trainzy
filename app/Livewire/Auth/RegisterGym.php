<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\Gym;
use App\Models\Package;
use Spatie\Permission\Models\Role;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class RegisterGym extends Component
{
    public $admin_name = '';
    public $admin_email = '';
    public $admin_password = '';
    public $name = '';
    public $phone = '';
    public $address = '';
    public $showPassword = false;

    protected function rules()
    {
        return [
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'admin_password' => ['required', 'string', 'min:8'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
        ];
    }

    public function register()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Create the gym
            $gym = Gym::create([
                'name' => $this->name,
                'email' => $this->admin_email,
                'phone' => $this->phone
            ]);

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

            DB::commit();

            Auth::login($user);

            return redirect()->route('dashboard.index');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            session()->flash('error', __('auth.registration_failed'));
        }
    }

    public function render()
    {
        return view('livewire.auth.register-gym');
    }
}
