<?php

namespace App\Livewire\Service;

use App\Models\Service;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Validation\Rule;

class CreateEditService extends Component
{
    use LivewireAlert;

    public $serviceId;
    public $name;
    public $is_active = true;

    protected function rules()
    {
        return [
            'name' => ['required', 'min:3', 'max:255', Rule::unique('services', 'name')->ignore($this->serviceId)],
            'is_active' => 'boolean'
        ];
    }

    public function mount($serviceId = null)
    {
        if ($serviceId) {
            // Editing an existing service
            $service = Service::findOrFail($serviceId);
            $this->serviceId = $service->id;
            $this->name = $service->name;
            $this->is_active = $service->is_active;
        }
    }

    public function save()
    {
        $this->validate($this->rules());

        try {
            if ($this->serviceId) {
                // Update Service
                $service = Service::findOrFail($this->serviceId);
                $service->update([
                    'name' => $this->name,
                    'is_active' => $this->is_active,
                ]);
            } else {
                // Create Service
                Service::create([
                    'name' => $this->name,
                    'is_active' => $this->is_active,
                ]);
            }

            session()->flash('message', $this->serviceId ? __('services.updated') : __('services.created'));
            return $this->redirect(route('services.index'));
        } catch (\Exception $e) {
            session()->flash('error', ($this->serviceId ? __('common.failed_to_update') : __('common.failed_to_create')) . ' service: ' . $e->getMessage());
            return;
        }
    }

    public function render()
    {
        return view('livewire.service.create-edit-service');
    }
}
