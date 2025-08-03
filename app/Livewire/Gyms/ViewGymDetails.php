<?php

namespace App\Livewire\Gyms;

use Livewire\Component;
use App\Models\Gym;

class ViewGymDetails extends Component
{
    public $gymId;
    public $gym;


    public function mount($gymId)
    {
        $this->gymId = $gymId;
        $this->gym = Gym::findOrFail($this->gymId);
    }

    public function render()
    {
        return view('livewire.gyms.view-gym-details');
    }
}
