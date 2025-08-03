<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class Sidebar extends Component
{

    #[On('refresh-gym')]
    public function render()
    {
        return view('livewire.sidebar');
    }
}
