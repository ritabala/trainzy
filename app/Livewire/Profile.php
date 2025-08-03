<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;


class Profile extends Component
{
    use WithFileUploads, LivewireAlert;

    public $profile_photo;
    public $name;
    public $email;
    public $phone_number;
    public $date_of_birth;
    public $gender;
    public $address;
    public $city;
    public $state;
    public $emergency_contact_name;
    public $emergency_contact_phone;
    public $role;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone_number' => 'required|string|max:20',
        'date_of_birth' => 'required|date',
        'gender' => 'required|string|in:male,female,other',
        'address' => 'required|string|max:500',
        'city' => 'required|string|max:100',
        'state' => 'required|string|max:100',
        'emergency_contact_name' => 'required|string|max:255',
        'emergency_contact_phone' => 'required|string|max:20',
        'profile_photo' => 'nullable|image|max:1024',
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone_number = $user->phone_number;
        $this->date_of_birth = $user->date_of_birth;
        $this->gender = $user->gender;
        $this->address = $user->address;
        $this->city = $user->city;
        $this->state = $user->state;
        $this->emergency_contact_name = $user->emergency_contact_name;
        $this->emergency_contact_phone = $user->emergency_contact_phone;
        $this->profile_photo = $user->profile_photo;
        $this->role = $user->role;
    }

    public function save()
    {
        $this->validate();

        try {
            $user = Auth::user();

            //Added a check to ensure we only process the profile photo if it's a new file upload (instanceof TemporaryUploadedFile) rather than an existing path.
        if ($this->profile_photo && $this->profile_photo instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
            // Delete old profile photo if exists
            $path = $this->profile_photo->store('/profile-photos/'.$this->role, 'public');
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Store new profile photo
            $user->profile_photo_path = $path;
            $user->save();
            
            // Dispatch event to update header image
            $this->dispatch('profile-photo-updated', ['photo_path' => $path]);
        }

        $user->update([
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
        ]);

            $this->alert('success', __('header.profile_updated'));
        } catch (\Exception $e) {
            $this->alert('error', __('header.error_occurred'));
        }
    }

    public function render()
    {
        return view('livewire.profile');
    }
} 