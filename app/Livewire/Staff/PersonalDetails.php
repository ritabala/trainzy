<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class PersonalDetails extends Component
{
    public $user;
    public $fullName;
    public $email;
    public $phoneNumber;
    public $dateOfBirth;
    public $gender;
    public $address;
    public $city;
    public $state;
    public $emergencyContactName;
    public $emergencyContactPhone;
    public $profilePhoto;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->loadUserData();
    }

    public function loadUserData()
    {
        $this->fullName = $this->user->name;
        $this->email = $this->user->email;
        $this->phoneNumber = $this->user->phone_number;
        $this->dateOfBirth = $this->user->date_of_birth;
        $this->gender = $this->user->gender;
        $this->address = $this->user->address;
        $this->city = $this->user->city;
        $this->state = $this->user->state;
        $this->emergencyContactName = $this->user->emergency_contact_name;
        $this->emergencyContactPhone = $this->user->emergency_contact_phone;
        $this->profilePhoto = $this->user->profile_photo_path;
    }

    public function downloadQrCode($userId)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('download_qr_code'), 403, __('errors.permission_denied'));

        $user = User::findOrFail($userId);
        $qrCode = $user->qr_code;

        return response()->streamDownload(function () use ($qrCode) {
            echo $qrCode;
        }, $user->name . '-qr-code.png', [
            'Content-Type' => 'image/png'
        ]);
    }

    public function render()
    {
        return view('livewire.staff.personal-details');
    }
} 