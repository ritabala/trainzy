<?php

namespace App\Livewire\Gyms;

use Livewire\Component;

class GymManagement extends Component
{
    public function addGym()
    {
        return redirect()->route('gyms.create');
    }

    public function render()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_gyms'), 403);
        return view('livewire.gyms.gym-management');
    }
} 