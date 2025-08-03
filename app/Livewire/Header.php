<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;

class Header extends Component
{
    public $profilePhotoPath;

    public function mount()
    {
        $user = Auth::user();
        $this->profilePhotoPath = $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('images/avatar.svg');
    }

    #[On('profile-photo-updated')]
    public function updateProfilePhoto($data)
    {
        $this->profilePhotoPath = asset('storage/' . $data['photo_path']);
    }

    public function logout()
    {
        Session::flush();
        Auth::guard('web')->logout();
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.header');
    }
}
