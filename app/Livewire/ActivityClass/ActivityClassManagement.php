<?php

namespace App\Livewire\ActivityClass;

use Livewire\Component;

class ActivityClassManagement extends Component
{
    public function addActivityClass()
    {
        return redirect()->route('activity-classes.create');
    }

    public function render()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_activity_classes'), 403);
        return view('livewire.activity-class.activity-class-management');
    }
} 