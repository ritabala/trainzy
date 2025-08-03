<?php

namespace App\Livewire\StaffSchedule;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('components.layouts.app')]
class ShowMoreSlots extends Component
{
    public $date;
    public $slots = [];
    public $show = false;

    #[On('openMoreSlots')]
    public function openMoreSlots($date, $slots)
    {
        $this->date = $date;
        $this->slots = collect($slots);
        $this->show = true;
    }

    public function closeModal()
    {
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.staff-schedule.show-more-slots');
    }
}
