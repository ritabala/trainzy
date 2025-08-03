<?php

namespace App\Livewire\Member;

use Livewire\Component;
use App\Models\User;

class ViewMemberDetails extends Component
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
        return view('livewire.member.view-member-details');
    }
}
