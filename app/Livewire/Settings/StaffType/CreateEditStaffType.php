<?php

namespace App\Livewire\Settings\StaffType;

use App\Models\StaffType;
use Livewire\Component;
use Illuminate\Support\Str;

class CreateEditStaffType extends Component
{
    public $name;
    public $slug;
    public $description;
    public $is_active = true;
    public $editingStaffTypeId;
    public $isEditing = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255',
        'description' => 'nullable|string',
        'is_active' => 'boolean',
    ];

    public function mount($staffType = null)
    {
        if ($staffType) {
            $this->editStaffType($staffType);
        }
    }

    public function editStaffType($staffType)
    {
        $this->isEditing = true;
        $this->editingStaffTypeId = $staffType['id'];
        $this->name = $staffType['name'];
        $this->slug = $staffType['slug'];
        $this->description = $staffType['description'];
        $this->is_active = $staffType['is_active'];
    }

    public function store()
    {
        $this->validate();

        try {
            StaffType::create([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);

            session()->flash('message', __('staff.staff_type_created'));
            return $this->redirect(route('settings.staff_types.index'));
        } catch (\Exception $e) {
            session()->flash('error', __('staff.failed_to_create_staff_type') . ' ' . $e->getMessage());
            return;
        }
    }

    public function update()
    {
        $this->validate();

        try {
            $staffType = StaffType::find($this->editingStaffTypeId);
            $staffType->update([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);

            session()->flash('message', __('staff.staff_type_updated'));
            return $this->redirect(route('settings.staff_types.index'));
        } catch (\Exception $e) {
            session()->flash('error', __('staff.failed_to_update_staff_type') . ' ' . $e->getMessage());
            return;
        }
    }

    public function render()
    {
        return view('livewire.settings.staff-type.create-edit-staff-type');
    }
} 