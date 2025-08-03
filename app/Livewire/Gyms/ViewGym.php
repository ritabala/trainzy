<?php

namespace App\Livewire\Gyms;

use Livewire\Component;
use App\Models\Gym;

class ViewGym extends Component
{
    public $gymId;
    public $gym;
    public $activeTab = 'info';

    protected $queryString = ['activeTab'];

    public function mount($gymId)
    {
        $this->gymId = $gymId;
        $this->gym = Gym::findOrFail($this->gymId);
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->dispatch('tab-changed', ['tab' => $tab]);
    }

    public function render()
    {
        return view('livewire.gyms.view-gym');
    }
}
