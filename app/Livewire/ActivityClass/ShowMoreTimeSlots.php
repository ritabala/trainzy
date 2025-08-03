<?php

namespace App\Livewire\ActivityClass;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('components.layouts.app')]
class ShowMoreTimeSlots extends Component
{
    public $date;
    public $slots = [];
    public $show = false;
    public $activityClassName;

    #[On('openMoreTimeSlots')]
    public function openMoreTimeSlots($date, $slots, $activityClassName)
    {
        $this->date = $date;
        $this->slots = collect($slots);
        $this->activityClassName = $activityClassName;
        $this->show = true;
    }

    public function closeModal()
    {
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.activity-class.show-more-time-slots');
    }
} 