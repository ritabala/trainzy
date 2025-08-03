<?php

namespace App\Livewire\Member;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class PersonalDetails extends Component
{
    public $user;
    public $section;

    public function mount(User $user, $section = 'basic')
    {
        $this->user = $user;
        $this->section = $section;
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
        return view('livewire.member.personal-details');
    }
} 