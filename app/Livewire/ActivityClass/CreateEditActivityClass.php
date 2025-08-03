<?php

namespace App\Livewire\ActivityClass;

use App\Models\ActivityClass;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Traits\HasPackageLimitCheck;

class CreateEditActivityClass extends Component
{
    use LivewireAlert, HasPackageLimitCheck;

    public $activityClassId;
    public $name;
    public $description;
    public $duration;

    protected $rules = [
        'name' => 'required|min:3|max:255',
        'description' => 'nullable',
        'duration' => 'required|integer|min:1'
    ];

    public function mount($activityClassId = null)
    {
        if ($activityClassId) {
            // Editing an existing activity class
            $activityClass = ActivityClass::findOrFail($activityClassId);
            $this->activityClassId = $activityClass->id;
            $this->name = $activityClass->name;
            $this->description = $activityClass->description;
            $this->duration = $activityClass->duration;
        }
    }

    public function save()
    {
        $this->validate();

        if (!$this->canCreateResource('classes', $this->activityClassId, $this->activityClassId !== null)) {
            return;
        }

        try {
            if ($this->activityClassId) {
                // Update Activity Class
                $activityClass = ActivityClass::findOrFail($this->activityClassId);
                $activityClass->update([
                    'name' => $this->name,
                    'description' => $this->description,
                    'duration' => $this->duration,
                ]);
            } else {
                // Create Activity Class
                ActivityClass::create([
                    'name' => $this->name,
                    'description' => $this->description,
                    'duration' => $this->duration,
                ]);
            }

            session()->flash('message', $this->activityClassId ? __('activity.updated') : __('activity.created'));
            return $this->redirect(route('activity-classes.index'));
        } catch (\Exception $e) {
            session()->flash('error', ($this->activityClassId ? __('common.failed_to_update') : __('common.failed_to_create')) . ' ' . __('activity.activity_class') . ': ' . $e->getMessage());
            return;
        }
    }

    public function render()
    {
        return view('livewire.activity-class.create-edit-activity-class');
    }
} 