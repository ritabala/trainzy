<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use App\Models\User;

class ViewStaffDetails extends Component
{
    public $userId;
    public $user;
    public $activeTab = 'personal';

    protected $queryString = ['activeTab'];

    public function mount($userId)
    {
        $this->userId = $userId;
        $this->user = User::findOrFail($this->userId);
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->dispatch('tab-changed', ['tab' => $tab]);
    }

    public function render()
    {
        return view('livewire.staff.view-staff-details');
    }
} 