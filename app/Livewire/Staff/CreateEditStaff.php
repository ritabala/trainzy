<?php

namespace App\Livewire\Staff;

use App\Models\User;
use App\Models\Staff;
use App\Models\StaffType;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use App\Traits\HasPackageLimitCheck;

class CreateEditStaff extends Component
{
    use WithFileUploads, HasPackageLimitCheck;

    public $staff;
    public $staffId;
    public $staffTypes;
    public $action = 'create';

    // Common user fields
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $phone_number;
    public $date_of_birth;
    public $gender;
    public $address;
    public $city;
    public $state;
    public $emergency_contact_name;
    public $emergency_contact_phone;
    public $profile_photo;

    // Staff specific fields
    public $staff_type_id;
    public $date_of_joining;
    public $blood_group;
    public $specialization;
    public $certifications;
    public $medical_history;
    public $is_active = true;

    // Role properties
    public $memberRole;
    public $staffRole;

    protected function rules()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email' . ($this->staff ? ',' . $this->staff->id : '')],
            'phone_number' => ['required', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:male,female,other'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'state' => ['nullable', 'string'],
            'emergency_contact_name' => ['nullable', 'string'],
            'emergency_contact_phone' => ['nullable', 'string'],
            'staff_type_id' => ['required', 'exists:staff_types,id'],
            'date_of_joining' => ['required', 'date'],
            'blood_group' => ['nullable', 'in:A+,A-,B+,B-,O+,O-,AB+,AB-'],
            'specialization' => ['nullable', 'string'],
            'certifications' => ['nullable', 'string'], // comma separated values
            'medical_history' => ['nullable', 'string'],
            'profile_photo' => ['nullable', 'image', 'max:1024'],
            'is_active' => ['required', 'boolean'],
        ];

        if ($this->action === 'create') {
            $rules['password'] = ['required', 'string', Password::defaults(), 'confirmed'];
        }

        return $rules;
    }

    public function mount($staffId = null)  
    {
        // Initialize role properties from trait
        $this->memberRole = 'member-' . gym()->id;
        $this->staffRole = ['staff-' . gym()->id, 'admin-' . gym()->id];
        
        $this->staffTypes = StaffType::where('is_active', true)->get();
        
        if ($staffId) {
            $this->staff = User::with('staffDetail')
                ->whereHas('roles', function($query) {
                    $query->whereIn('name', $this->staffRole);
                })
                ->findOrFail($staffId);
                
            $this->action = 'edit';
            $this->fillFormData();
        }
    }

    protected function fillFormData()
    {
        if (!$this->staff) {
            return;
        }

        // Fill common user data
        $this->name = $this->staff->name;
        $this->email = $this->staff->email;
        $this->phone_number = $this->staff->phone_number;
        $this->date_of_birth = $this->staff->date_of_birth;
        $this->gender = $this->staff->gender;
        $this->address = $this->staff->address;
        $this->city = $this->staff->city;
        $this->state = $this->staff->state;
        $this->emergency_contact_name = $this->staff->emergency_contact_name;
        $this->emergency_contact_phone = $this->staff->emergency_contact_phone;
        $this->is_active = $this->staff->is_active;

        // Fill staff specific data from staff_details
        if ($this->staff->staffDetail) {
            $this->staff_type_id = $this->staff->staffDetail->staff_type_id;
            $this->date_of_joining = optional($this->staff->staffDetail->date_of_joining)->format('Y-m-d');
            $this->blood_group = $this->staff->staffDetail->blood_group;
            $this->specialization = $this->staff->staffDetail->specialization;
            $this->certifications = is_array($this->staff->staffDetail->certifications) 
                ? implode(', ', array_filter($this->staff->staffDetail->certifications))
                : '';
            $this->medical_history = $this->staff->staffDetail->medical_history;
        }
    }

    public function save()
    {
        $validatedData = $this->validate();

        // Check package staff limit before proceeding
        if (!$this->canCreateResource('staff', $this->staffId, $this->action === 'edit')) {
            return;
        }

        // Prevent staff from changing their own active status
        if ($this->action === 'edit' && auth()->user()->id === $this->staff->id && $this->is_active !== $this->staff->is_active) {
            $this->addError('is_active', __('staff.change_own'));
            session()->flash('error', __('staff.change_own'));
            return;
        }

        try {
            DB::beginTransaction();

            // Prepare common user data
            $userData = [
                'name' => $this->name,
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'date_of_birth' => $this->date_of_birth,
                'gender' => $this->gender,
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'emergency_contact_name' => $this->emergency_contact_name,
                'emergency_contact_phone' => $this->emergency_contact_phone,
                'is_active' => $this->is_active,
            ];

            if ($this->action === 'create') {
                $userData['password'] = bcrypt($this->password);
                $user = User::create($userData);
                $user->assignRole('staff-' . gym()->id);
            } else {
                // Get current roles before update
                $currentRoles = $this->staff->roles->pluck('name')->toArray();
                $this->staff->update($userData);
                $user = $this->staff;
                
                // Preserve existing roles
                if (!empty($currentRoles)) {
                    $user->syncRoles($currentRoles);
                }
            }

            if ($this->profile_photo) {
                $path = $this->profile_photo->store('profile-photos/staff', 'public');
                if ($user->profile_photo_path) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
                $user->profile_photo_path = $path;
                $user->save();
            }

            // Prepare and save staff-specific data
            $staffData = [
                'staff_type_id' => $this->staff_type_id,
                'date_of_joining' => $this->date_of_joining,
                'blood_group' => $this->blood_group,
                'specialization' => $this->specialization,
                'certifications' => $this->formatCertifications($this->certifications),
                'medical_history' => $this->medical_history
            ];

            if ($this->action === 'create') {
                $user->staffDetail()->create($staffData);
                session()->flash('message', __('staff.created'));
            } else {
                if ($this->staff->staffDetail) {
                    $this->staff->staffDetail()->update($staffData);
                } else {
                    $this->staff->staffDetail()->create($staffData);
                }
                session()->flash('message', __('staff.updated'));
            }

            DB::commit();
            return redirect()->route('staff.index');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', __('staff.save_error'));
            $this->addError('save', $e->getMessage());
            throw $e;
        }
    }

    public function cancel()
    {
        return redirect()->route('staff.index');
    }

    public function updatedIsActive($value)
    {
        
        if ($value) {
            $this->is_active = 1;
        } else {
            $this->is_active = 0;
        }
    }

    protected function formatCertifications($certifications)
    {
        if (empty($certifications)) {
            return null;
        }

        // Split by comma, clean each item, and remove empty values
        $items = array_map('trim', explode(',', $certifications));
        $items = array_filter($items, function($item) {
            return !empty($item) && strlen($item) > 0;
        });

        return !empty($items) ? array_values($items) : null;
    }

    public function render()
    {
        return view('livewire.staff.create-edit-staff');
    }

} 